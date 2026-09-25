# Catatan Implementasi OMD Order

## Master Model & Produk

Menu OMD :

- Data Master
  - Data PPIC
  - Data Produksi

Setiap halaman Data PPIC/Data Produksi memiliki dua form dalam satu halaman:

1. Tambah Model: hanya menerima nama Model. Nomor dibuat otomatis per kelompok data.
2. Tambah Produk: memilih Model yang sudah dibuat, lalu memasukkan nama Produk.

Relasi bisnis:

`1 Model -> banyak Produk`

Jenis NG dan Quantity **bukan master data**. Keduanya merupakan data transaksi pada saat User membuat Order Repair.

## Pemisahan PPIC dan Produksi

`users.user_group` menentukan kelompok User:

- `ppic`
- `produksi`

`master_models.data_scope` dan `products.data_scope` memakai nilai yang sama. User hanya menerima Model/Produk dari kelompoknya sendiri.

## Alur Order Repair Box

User PPIC/Produksi:

1. Membuka Buat Order Repair.
2. Sistem menampilkan Model sesuai kelompok akun.
3. Setelah Model dipilih, Produk hanya menampilkan produk milik Model tersebut.
4. User mengisi Jenis NG (P/H/C), Quantity, dan keterangan.
5. Order dikirim ke OMD.

OMD kemudian memproses order melalui workflow yang sudah ada: Submitted -> Verified -> In Repair -> Completed -> Confirmed.

## Catatan Pengembangan

- Data Electric belum dibuat karena requirement belum dikonfirmasi.
- Data model/produk Produksi tidak diisi contoh karena daftar Produksi belum diberikan.
- Data Master saat ini dikelola OMD Leader agar perubahan master tidak dilakukan oleh operator/User.
- Kolom `repair_orders.model` tetap dipertahankan untuk kompatibilitas dengan transaksi lama, tetapi order baru menyimpan relasi utama pada `master_model_id`.
