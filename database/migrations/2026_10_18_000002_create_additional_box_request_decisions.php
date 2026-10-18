<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private const TABLE = 'additional_box_request_decisions';

    public function up(): void
    {
        if (!Schema::hasTable(self::TABLE)) {
            // Clean installs: create the same schema with short constraint names.
            Schema::create(self::TABLE, function (Blueprint $table) {
                $table->id();
                $table->foreignId('additional_box_request_id');
                $table->foreignId('user_scrap_limit_cycle_id')->unique('ab_dec_cycle_unique');
                $table->foreignId('product_id')->nullable();
                $table->string('model_name', 100);
                $table->string('product_name', 150);
                $table->unsignedInteger('scrap_qty');
                $table->unsignedInteger('limit_qty');
                $table->enum('decision', ['yes', 'no']);
                $table->text('reason')->nullable();
                $table->unsignedInteger('quantity')->nullable();
                $table->timestamps();
                $table->index(['additional_box_request_id', 'decision'], 'ab_dec_request_status_idx');
                $table->foreign('additional_box_request_id', 'ab_dec_request_fk')
                    ->references('id')->on('additional_box_requests')->cascadeOnDelete();
                $table->foreign('user_scrap_limit_cycle_id', 'ab_dec_cycle_fk')
                    ->references('id')->on('user_scrap_limit_cycles');
                $table->foreign('product_id', 'ab_dec_product_fk')
                    ->references('id')->on('products')->nullOnDelete();
            });
            return;
        }

        // MySQL may already have created and populated the table before the old
        // migration failed. Never DROP or truncate it: reconcile in place instead.
        $required = [
            'id', 'additional_box_request_id', 'user_scrap_limit_cycle_id', 'product_id',
            'model_name', 'product_name', 'scrap_qty', 'limit_qty', 'decision',
            'reason', 'quantity', 'created_at', 'updated_at',
        ];
        foreach ($required as $column) {
            if (!Schema::hasColumn(self::TABLE, $column)) {
                throw new \RuntimeException("Kolom {$column} belum ada di " . self::TABLE . '. Backup dan periksa struktur tabel.');
            }
        }

        // Validate existing decision rows before enabling referential integrity.
        $references = [
            'additional_box_request_id' => ['additional_box_requests', 'ab_dec_request_fk', 'cascade'],
            'user_scrap_limit_cycle_id' => ['user_scrap_limit_cycles', 'ab_dec_cycle_fk', 'restrict'],
            'product_id' => ['products', 'ab_dec_product_fk', 'set null'],
        ];
        foreach ($references as $column => [$target]) {
            $orphan = DB::table(self::TABLE . ' as d')
                ->leftJoin($target . ' as parent', 'd.' . $column, '=', 'parent.id')
                ->whereNotNull('d.' . $column)
                ->whereNull('parent.id')
                ->exists();
            if ($orphan) {
                throw new \RuntimeException("Ada data {$column} tanpa referensi di {$target}. Data tidak dihapus; perbaiki referensi dahulu.");
            }
        }

        $indexes = Schema::getIndexes(self::TABLE);
        $hasIndex = static function (array $columns, bool $unique = false) use ($indexes): bool {
            foreach ($indexes as $index) {
                if (($index['columns'] ?? []) === $columns && (!$unique || ($index['unique'] ?? false))) {
                    return true;
                }
            }
            return false;
        };

        if (!$hasIndex(['user_scrap_limit_cycle_id'], true)) {
            $duplicate = DB::table(self::TABLE)
                ->select('user_scrap_limit_cycle_id')
                ->groupBy('user_scrap_limit_cycle_id')
                ->havingRaw('COUNT(*) > 1')->exists();
            if ($duplicate) {
                throw new \RuntimeException('Terdapat keputusan ganda untuk siklus yang sama. Data dipertahankan; periksa duplikasi sebelum migrasi.');
            }
            Schema::table(self::TABLE, fn (Blueprint $table) =>
                $table->unique('user_scrap_limit_cycle_id', 'ab_dec_cycle_unique'));
        }
        if (!$hasIndex(['additional_box_request_id', 'decision'])) {
            Schema::table(self::TABLE, fn (Blueprint $table) =>
                $table->index(['additional_box_request_id', 'decision'], 'ab_dec_request_status_idx'));
        }

        // Laravel 12 schema introspection works for MySQL as well as SQLite.
        // Re-read after each DDL because MySQL commits schema changes immediately.
        foreach ($references as $column => [$target, $name, $deleteBehavior]) {
            $foreignKeys = Schema::getForeignKeys(self::TABLE);
            $existing = null;
            foreach ($foreignKeys as $foreignKey) {
                if (($foreignKey['columns'] ?? []) === [$column]) {
                    $existing = $foreignKey;
                    break;
                }
            }
            if ($existing !== null) {
                if (($existing['foreign_table'] ?? null) !== $target ||
                    ($existing['foreign_columns'] ?? []) !== ['id']) {
                    throw new \RuntimeException("Foreign key {$column} mengarah ke tabel yang berbeda. Periksa constraint sebelum melanjutkan.");
                }
                continue;
            }
            Schema::table(self::TABLE, function (Blueprint $table) use ($column, $target, $name, $deleteBehavior) {
                $foreign = $table->foreign($column, $name)->references('id')->on($target);
                if ($deleteBehavior === 'cascade') $foreign->cascadeOnDelete();
                if ($deleteBehavior === 'set null') $foreign->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(self::TABLE);
    }
};
