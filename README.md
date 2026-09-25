# OMD Order

Sistem Laravel untuk digitalisasi proses order dan repair OMD Workshop.

## Setup

```bash
composer install
copy .env.example .env
php artisan key:generate
```

Atur koneksi MySQL/MariaDB pada `.env`, lalu:

```bash
php artisan migrate --seed
```

Jalankan:

```bash
php artisan serve
```

Jika PHP Laragon belum masuk PATH Windows, gunakan executable PHP Laragon, contoh:

```bash
"C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64\php.exe" artisan migrate --seed
```

## Akun Seeder

Semua akun contoh memakai password `password`.

| Email | Role | Kelompok |
|---|---|---|
| user@omd.local | User | PPIC |
| produksi@omd.local | User | Produksi |
| member@omd.local | OMD Member | - |
| leader@omd.local | OMD | - |

## Master Model & Produk

OMD Leader membuka:

`Data Master -> Data PPIC` atau `Data Master -> Data Produksi`.

Setiap halaman memiliki form Model dan Produk. Satu Model dapat mempunyai banyak Produk.

Jenis NG (P/H/C) dan Quantity bukan master data; keduanya diisi saat transaksi Order Repair.
