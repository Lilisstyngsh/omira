# Menjalankan hasil perubahan OMIRA di Windows

Branch ini berisi implementasi inline editing Plant & Line, tes regresi, dan helper untuk preview lokal Windows. Pilih branch `codex/inline-plant-line-preview` di GitHub, lalu gunakan Code → Download ZIP. Mulai dari folder hasil ekstraksi baru; dependensi dan database demo dibuat melalui langkah di bawah. Tidak ada kredensial atau konfigurasi .env dari lingkungan cloud yang ditambahkan.

1. Pasang PHP 8.4 dan Composer 2. Laravel Herd untuk Windows dapat digunakan untuk menyediakan PHP/Composer. Pasang Node.js 22 versi minimal 22.12 atau Node.js 24. Ekstensi PHP yang diperlukan mencakup pdo_sqlite, mbstring, XML/DOM, gd, zip, fileinfo, dan openssl.
2. Di GitHub buka branch `codex/inline-plant-line-preview`, klik Code → Download ZIP, lalu ekstrak ZIP ke folder baru. Jangan menimpa instalasi OMIRA atau database yang sedang dipakai.
3. Buka folder hasil ekstraksi yang berisi file `artisan`, `composer.json`, dan `setup-local.php` (nama folder mengikuti nama archive GitHub). Klik kolom alamat File Explorer, ketik `powershell`, lalu Enter.
4. Pastikan alat terdeteksi:

```powershell
php -v
composer --version
node -v
npm.cmd -v
```

Jika alat baru dipasang belum terdeteksi, tutup dan buka kembali terminal. Jika PHP/Composer tidak tersedia, pastikan instalasi dan PATH Windows sudah benar.

5. Jalankan perintah berikut satu per satu:

```powershell
composer install --no-interaction --prefer-dist
composer check-platform-reqs
npm.cmd install --package-lock=false
php setup-local.php
php artisan key:generate
php artisan config:clear
php artisan migrate --seed
npm.cmd run build
php artisan serve
```

`setup-local.php` hanya membuat .env jika belum ada; database SQLite demo berada di `database/database.sqlite`. Langkah di atas untuk folder hasil ekstraksi baru dan database demo kosong. Jangan menjalankan seeding atau key:generate berulang pada instalasi/database yang sudah dipakai: seeder mengatur ulang password akun bawaan, dan penggantian APP_KEY bisa membuat data terenkripsi lama tidak terbaca.

Jika Composer melaporkan ekstensi hilang, aktifkan ekstensi yang disebut pada php.ini yang digunakan terminal (`php --ini`), lalu ulangi instalasi. Jangan menggunakan --ignore-platform-reqs. Jika port server sudah digunakan, jalankan `php artisan serve --port=8005`.

6. Biarkan terminal server tetap terbuka. Buka alamat HTTP yang ditampilkan oleh `php artisan serve` pada Chrome atau Edge di komputer Windows tersebut.
7. Login menggunakan akun demo lokal yang sudah tersedia di source seeder:
   - Email: `dwi.h@aiia.co.id`
   - Password: `password`
8. Buka Data Master → Plant & Line. Pilih Electric, klik area baris Line, ubah nama, lalu Enter. Klik area nama Plant untuk mengeditnya. Klik ruang kosong card untuk memilih Plant. Escape atau klik keluar membatalkan edit yang belum disimpan. Ikon X tetap meminta konfirmasi hapus.
9. Untuk menutup server, tekan Ctrl+C di terminal. Untuk membuka lagi, masuk folder yang sama dan jalankan hanya `php artisan serve`.

Tidak perlu menjalankan Vite dev server untuk melihat preview: `npm.cmd run build` sudah membuat aset. Untuk pengembangan frontend selanjutnya, buka terminal kedua dan jalankan `npm.cmd run dev`.

Browser memerlukan internet untuk CDN SweetAlert dan Font Awesome. Tidak diperlukan hosting untuk preview lokal. Akun demo bawaan hanya untuk pengujian lokal; jangan digunakan pada website publik. Staging dapat disiapkan setelah hosting/server dipilih dengan database terpisah, HTTPS, APP_DEBUG=false, serta kredensial yang disimpan melalui pengaturan hosting.

Validasi cloud untuk perubahan ini: 16 tes regresi backend lulus (100 assertions), 12 skenario wajib UI berhasil, build berhasil. Pengujian browser memakai salinan SQLite terisolasi. Instalasi di Windows belum dijalankan dari mesin cloud ini. Tes bawaan ExampleTest untuk guest / sebelumnya gagal karena mengharapkan 200 sementara route memerlukan login dan mengembalikan 302; tes itu tidak diubah.
