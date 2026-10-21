# OMIRA Laravel — Docker Tahap 1

Tahap ini menyiapkan image Laravel/PHP/Apache dan container MySQL. Database MySQL yang dibuat masih kosong; impor `omira.sql` dilakukan pada Tahap 2. Jangan menjalankan `migrate:fresh` atau seeder terhadap database yang berisi data asli.

## File yang ditambahkan/diubah

- `Dockerfile` — image PHP 8.3 + Apache, ekstensi Laravel/PhpSpreadsheet/DomPDF, Composer, dan asset Vite.
- `compose.yaml` — service aplikasi dan MySQL 8.4 dengan volume persisten.
- `docker/apache-vhost.conf` — Apache mengarah ke `public/` Laravel.
- `docker/entrypoint.sh` — menyiapkan direktori runtime dan permission storage.
- `.dockerignore` — mencegah file lokal, cache, SQL dump, dan ZIP masuk ke build image.
- `.env.docker.example` — template environment khusus Docker.
- `.gitignore` — tambahan pengecualian `.env.docker` dan SQL dump root.

## Menjalankan di Windows

Jalankan perintah dari folder utama project, misalnya `C:\laragon\www\omira`. Docker Desktop harus sudah terbuka dan status Engine berjalan.

1. Salin template environment:

   ```cmd
   copy .env.docker.example .env.docker
   ```

2. Buka file khusus Docker dengan Notepad:

   ```cmd
   notepad .env.docker
   ```

   Ganti `DB_PASSWORD` dan `DB_ROOT_PASSWORD` dengan password berbeda yang kuat. Untuk memudahkan konfigurasi Compose, gunakan huruf dan angka saja. Simpan file. Jangan mengunggah `.env.docker` ke GitHub.

3. Bangun image:

   ```cmd
   docker compose --env-file .env.docker build
   ```

4. Buat APP_KEY. Jalankan perintah ini dan salin nilai `base64:...` yang keluar:

   ```cmd
   docker compose --env-file .env.docker run --rm --no-deps app php artisan key:generate --show
   ```

   Buka lagi `notepad .env.docker`, tempel nilai tersebut setelah `APP_KEY=`, lalu simpan.

5. Jalankan container:

   ```cmd
   docker compose --env-file .env.docker up -d
   ```

6. Periksa status:

   ```cmd
   docker compose --env-file .env.docker ps
   ```

   Service `app` dan `db` seharusnya berstatus `Up`; `db` perlu sehat/`healthy`.

7. Tahap 1 selesai jika container aktif. Buka `http://localhost:8080` untuk memeriksa respons web. Karena database masih kosong, fitur login/dashboard yang membaca tabel mungkin belum dapat dipakai sampai SQL diimpor pada Tahap 2.

## Perintah berguna

Lihat log:

```cmd
docker compose --env-file .env.docker logs --tail=100 app
```

Hentikan container tanpa menghapus database:

```cmd
docker compose --env-file .env.docker down
```

**Jangan** menjalankan `docker compose down -v` jika ingin mempertahankan data di volume; opsi `-v` menghapus volume database.

## Keamanan

- Jangan commit `.env`, `.env.docker`, password, APP_KEY, atau SQL export asli.
- `.env.docker.example` hanya template dan tidak berisi kredensial nyata.
- Jangan menjalankan `php artisan migrate:fresh` atau seeder otomatis pada database hasil import.
- Database belum diimpor pada Tahap 1. Tahap 2 akan menangani impor SQL secara terpisah.

## Catatan audit

Project tidak memiliki `package-lock.json`; build frontend menggunakan `npm install` berdasarkan `package.json`. Untuk build yang lebih reproducible pada tahap berikutnya, sebaiknya tambahkan `package-lock.json` yang dibuat dan diuji pada project asli.
