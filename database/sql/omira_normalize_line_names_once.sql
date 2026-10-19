-- OMIRA - Perbaikan nama Line sekali jalan (MySQL/MariaDB)
-- Jalankan sekali pada database OMIRA yang SUDAH di-backup.
-- Tidak perlu php artisan migrate atau php artisan db:seed.
-- Hanya mengubah nama Line, atau memindah referensi ID lama ke ID tujuan.
-- Nomor/order_number yang sudah disimpan TIDAK diubah.
-- Semua perbaikan dijalankan dalam satu transaction; jika gagal: ROLLBACK.

DELIMITER $$
DROP PROCEDURE IF EXISTS `omira_fix_line_names_once`$$
CREATE PROCEDURE `omira_fix_line_names_once`()
BEGIN
    DECLARE finished TINYINT DEFAULT 0;
    DECLARE v_plant VARCHAR(100);
    DECLARE v_old VARCHAR(100);
    DECLARE v_new VARCHAR(100);
    DECLARE v_plant_count INT DEFAULT 0;
    DECLARE v_plant_id BIGINT UNSIGNED;
    DECLARE v_old_count INT DEFAULT 0;
    DECLARE v_new_count INT DEFAULT 0;
    DECLARE v_old_id BIGINT UNSIGNED;
    DECLARE v_new_id BIGINT UNSIGNED;
    DECLARE v_ref_count INT DEFAULT 0;
    DECLARE v_ref_offset INT DEFAULT 0;
    DECLARE v_ref_table VARCHAR(128);
    DECLARE v_ref_col VARCHAR(128);
    DECLARE v_str TEXT;

    DECLARE line_map CURSOR FOR
        SELECT mapping.plant, mapping.old_name, mapping.new_name
        FROM (
            SELECT 1 AS seq, 'Body' AS plant, 'AS Body' AS old_name, 'Assy Body' AS new_name
            UNION ALL SELECT 2, 'Body', 'INJ', 'Injection'
            UNION ALL SELECT 3, 'Body', 'PT', 'Painting'
            UNION ALL SELECT 4, 'Electric', 'AS Electric', 'Assy Electric'
            UNION ALL SELECT 5, 'Unit', 'DC', 'Die Casting'
            UNION ALL SELECT 6, 'Unit', 'MA', 'Machining'
            UNION ALL SELECT 7, 'Unit', 'AS Unit', 'Assy Unit'
        ) AS mapping
        ORDER BY mapping.seq;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;
    OPEN line_map;

    each_line: LOOP
        FETCH line_map INTO v_plant, v_old, v_new;
        IF finished = 1 THEN
            LEAVE each_line;
        END IF;

        SELECT COUNT(*), MIN(id) INTO v_plant_count, v_plant_id
        FROM `plants` WHERE BINARY `name` = BINARY v_plant;

        IF v_plant_count <> 1 THEN
            SET v_str = CONCAT('Plant [', v_plant, '] tidak ditemukan atau duplikat. Tidak ada data yang diubah.');
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_str;
        END IF;

        SELECT COUNT(*), MIN(id) INTO v_old_count, v_old_id
        FROM `lines`
        WHERE `plant_id` = v_plant_id AND BINARY `name` = BINARY v_old;

        SELECT COUNT(*), MIN(id) INTO v_new_count, v_new_id
        FROM `lines`
        WHERE `plant_id` = v_plant_id AND BINARY `name` = BINARY v_new;

        IF v_old_count > 1 OR v_new_count > 1 THEN
            SET v_str = CONCAT('Nama Line duplikat pada Plant [', v_plant, ']: ', v_old, ' / ', v_new);
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_str;
        END IF;

        IF v_old_count = 0 THEN
            ITERATE each_line; -- Sudah memakai nama lengkap / belum ada Line lama.
        END IF;

        IF v_new_count = 0 THEN
            -- Hanya ada Line lama; ubah NAMANYA tanpa mengganti ID.
            UPDATE `lines`
            SET `name` = v_new, `updated_at` = NOW()
            WHERE `id` = v_old_id;
            ITERATE each_line;
        END IF;

        -- Ada Line lama dan baru. Pindahkan SELURUH referensi Line lama
        -- termasuk yang memakai FK serta tabel dengan kolom line_id.
        -- Jika bentrok constraint unik, MySQL akan error dan transaksi ROLLBACK.
        -- Khusus FY/abnormality, scope_key juga menyimpan ID Line.
        UPDATE `fiscal_year_targets`
        SET `scope_key` = CONCAT('line:', v_new_id), `updated_at` = NOW()
        WHERE `line_id` = v_old_id AND `scope_key` = CONCAT('line:', v_old_id);

        UPDATE `monitoring_abnormalities`
        SET `scope_key` = CONCAT('line:', v_new_id), `updated_at` = NOW()
        WHERE `line_id` = v_old_id AND `scope_key` = CONCAT('line:', v_old_id);

        SELECT COUNT(*) INTO v_ref_count
        FROM information_schema.COLUMNS c
        JOIN information_schema.TABLES t
          ON t.TABLE_SCHEMA = c.TABLE_SCHEMA AND t.TABLE_NAME = c.TABLE_NAME
        WHERE c.TABLE_SCHEMA = DATABASE()
          AND t.TABLE_TYPE = 'BASE TABLE'
          AND c.TABLE_NAME <> 'lines'
          AND (
              c.COLUMN_NAME = 'line_id'
              OR EXISTS (
                  SELECT 1 FROM information_schema.KEY_COLUMN_USAGE k
                  WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
                    AND k.TABLE_NAME = c.TABLE_NAME
                    AND k.COLUMN_NAME = c.COLUMN_NAME
                    AND k.REFERENCED_TABLE_SCHEMA = DATABASE()
                    AND k.REFERENCED_TABLE_NAME = 'lines'
                    AND k.REFERENCED_COLUMN_NAME = 'id'
              )
          );

        IF v_ref_count = 0 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kolom relasi Line tidak ditemukan; perbaikan dibatalkan.';
        END IF;

        SET v_ref_offset = 0;
        WHILE v_ref_offset < v_ref_count DO
            SELECT c.TABLE_NAME, c.COLUMN_NAME INTO v_ref_table, v_ref_col
            FROM information_schema.COLUMNS c
            JOIN information_schema.TABLES t
              ON t.TABLE_SCHEMA = c.TABLE_SCHEMA AND t.TABLE_NAME = c.TABLE_NAME
            WHERE c.TABLE_SCHEMA = DATABASE()
              AND t.TABLE_TYPE = 'BASE TABLE'
              AND c.TABLE_NAME <> 'lines'
              AND (
                  c.COLUMN_NAME = 'line_id'
                  OR EXISTS (
                      SELECT 1 FROM information_schema.KEY_COLUMN_USAGE k
                      WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
                        AND k.TABLE_NAME = c.TABLE_NAME
                        AND k.COLUMN_NAME = c.COLUMN_NAME
                        AND k.REFERENCED_TABLE_SCHEMA = DATABASE()
                        AND k.REFERENCED_TABLE_NAME = 'lines'
                        AND k.REFERENCED_COLUMN_NAME = 'id'
                  )
              )
            ORDER BY c.TABLE_NAME, c.COLUMN_NAME
            LIMIT v_ref_offset, 1;

            SET @omira_new_line_id = v_new_id;
            SET @omira_old_line_id = v_old_id;
            SET @omira_sql = CONCAT(
                'UPDATE `', REPLACE(v_ref_table, '`', '``'), '`',
                ' SET `', REPLACE(v_ref_col, '`', '``'), '` = ?',
                ' WHERE `', REPLACE(v_ref_col, '`', '``'), '` = ?'
            );
            PREPARE omira_stmt FROM @omira_sql;
            EXECUTE omira_stmt USING @omira_new_line_id, @omira_old_line_id;
            DEALLOCATE PREPARE omira_stmt;
            SET v_ref_offset = v_ref_offset + 1;
        END WHILE;

        -- Jangan hapus Line jika masih ada relasi; ON DELETE CASCADE berbahaya.
        -- Periksa kembali dinamis sebelum DELETE.
        SET v_ref_offset = 0;
        WHILE v_ref_offset < v_ref_count DO
            SELECT c.TABLE_NAME, c.COLUMN_NAME INTO v_ref_table, v_ref_col
            FROM information_schema.COLUMNS c
            JOIN information_schema.TABLES t
              ON t.TABLE_SCHEMA = c.TABLE_SCHEMA AND t.TABLE_NAME = c.TABLE_NAME
            WHERE c.TABLE_SCHEMA = DATABASE()
              AND t.TABLE_TYPE = 'BASE TABLE'
              AND c.TABLE_NAME <> 'lines'
              AND (
                  c.COLUMN_NAME = 'line_id'
                  OR EXISTS (
                      SELECT 1 FROM information_schema.KEY_COLUMN_USAGE k
                      WHERE k.TABLE_SCHEMA = c.TABLE_SCHEMA
                        AND k.TABLE_NAME = c.TABLE_NAME
                        AND k.COLUMN_NAME = c.COLUMN_NAME
                        AND k.REFERENCED_TABLE_SCHEMA = DATABASE()
                        AND k.REFERENCED_TABLE_NAME = 'lines'
                        AND k.REFERENCED_COLUMN_NAME = 'id'
                  )
              )
            ORDER BY c.TABLE_NAME, c.COLUMN_NAME
            LIMIT v_ref_offset, 1;

            SET @omira_old_line_id = v_old_id;
            SET @omira_remaining = 0;
            SET @omira_sql = CONCAT(
                'SELECT COUNT(*) INTO @omira_remaining FROM `', REPLACE(v_ref_table, '`', '``'),
                '` WHERE `', REPLACE(v_ref_col, '`', '``'), '` = ?'
            );
            PREPARE omira_stmt FROM @omira_sql;
            EXECUTE omira_stmt USING @omira_old_line_id;
            DEALLOCATE PREPARE omira_stmt;
            IF @omira_remaining > 0 THEN
                SET v_str = CONCAT('Referensi Line ID ', v_old_id, ' masih ada di ', v_ref_table);
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = v_str;
            END IF;
            SET v_ref_offset = v_ref_offset + 1;
        END WHILE;

        DELETE FROM `lines` WHERE `id` = v_old_id;
    END LOOP;
    CLOSE line_map;
    COMMIT;
END$$
DELIMITER ;

-- Semua 7 perubahan dilakukan atomik dalam satu transaksi.
CALL `omira_fix_line_names_once`();
DROP PROCEDURE IF EXISTS `omira_fix_line_names_once`;

-- Verifikasi: hasil akhir seharusnya 10 Line aktif (sesuai kondisi project awal).
SELECT p.name AS plant, l.id, l.name AS line, l.is_active
FROM `lines` l
JOIN `plants` p ON p.id = l.plant_id
WHERE p.name IN ('Body', 'Electric', 'Unit')
ORDER BY p.name, l.name, l.id;
