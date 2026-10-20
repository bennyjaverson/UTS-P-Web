# UTS Web - Manajemen Stok Barang

Mini web app untuk mencatat stok barang. Dibuat untuk Tugas UTS Web (HTML, CSS, JavaScript, jQuery, Bootstrap, dan PHP).

## Identitas

- Nama: Benny Javerson D A
- NIM: 252410103106
- Tema: No. 6 - Manajemen Stok Barang Sederhana

## Fitur

- Form tambah stok barang (nama barang, kategori, harga, stok).
- Kalau barang dengan nama dan kategori yang sama sudah ada, stoknya ditambah. Kalau belum ada, barang baru disimpan.
- Daftar barang ditampilkan dalam tabel Bootstrap.
- Label **Stok Menipis** muncul otomatis kalau stok 5 atau kurang.
- Validasi sisi klien (jQuery) dan sisi server (PHP).
- Alert sukses dan error yang hilang sendiri (efek `fadeOut` jQuery).

## Teknologi

- HTML5, CSS3, JavaScript, jQuery 3.7.1
- Bootstrap 5.0.2
- PHP (mysqli) dan MySQL
- Laragon sebagai server lokal

## Struktur File

```
.
├── index.php      halaman utama (form, validasi server, tabel)
├── koneksi.php    koneksi ke database
├── style.css      styling tambahan di atas Bootstrap
├── script.js      validasi form dan efek alert (jQuery)
├── toko.sql       export database
└── README.md
```

## Struktur Database

Database: `toko`

Tabel yang dipakai oleh aplikasi:

```sql
create table barang (
    id_barang int auto_increment primary key,
    nama_barang varchar(255) not null,
    kategori varchar(100) not null,
    harga int not null,
    stok int not null
);
```

Tabel `transaksi` dan `detail_transaksi` sudah dibuat di database sebagai persiapan pengembangan berikutnya, tetapi belum dipakai di aplikasi ini.

## Cara Menjalankan

1. Jalankan **Laragon**, lalu klik **Start All** (Apache dan MySQL).
2. Buka **Database** di Laragon (HeidiSQL), lalu import file `toko.sql` supaya database `toko` dan tabelnya terbentuk.
3. Letakkan folder proyek di `laragon/www/`.
4. Buka browser dan akses `http://localhost/nama-folder-proyek/`.

Pengaturan koneksi ada di `koneksi.php` (host `localhost`, user `root`, password kosong, database `toko`).

## Logika Utama

1. Form dikirim, lalu jQuery mengecek isian. Kalau ada yang salah, pengiriman dibatalkan dan muncul alert merah.
2. PHP mengecek ulang di server. Setiap kesalahan dikumpulkan di array `$errors`.
3. Kalau tidak ada kesalahan, PHP mengecek apakah barang (nama dan kategori) sudah ada, lalu menjalankan `update` stok atau `insert` barang baru.
4. Halaman diarahkan ke `index.php?status=sukses` dan alert hijau muncul, lalu hilang sendiri.
5. Tabel diambil dengan query `select ... order by id_barang`, dan barang dengan stok 5 ke bawah diberi label **Stok Menipis**.

## Validasi

| Field | Aturan |
|---|---|
| Nama barang | Wajib diisi |
| Kategori | Wajib dipilih |
| Harga | Angka bulat lebih dari 0 |
| Stok | Angka bulat lebih dari 0 |