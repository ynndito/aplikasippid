# Sistem Informasi PPID - Backend PHP Native

## Instalasi

1. **Setup Database**
   - Buka phpMyAdmin atau MySQL client
   - Import file `database.sql` untuk membuat database dan tabel
   - Database akan dibuat dengan nama `db_ppid`
   - Default admin: username `admin`, password `admin`

2. **Konfigurasi Koneksi Database**
   - Edit file `koneksi.php` jika diperlukan
   - Default: host `localhost`, user `root`, password kosong

3. **Setup Folder Uploads**
   - Folder `uploads/` sudah dibuat
   - Pastikan folder memiliki permission write (chmod 755 atau 777)

4. **Akses Aplikasi**
   - Halaman publik: `http://localhost/aplikasippid/ppid/index.php`
   - Login admin: `http://localhost/aplikasippid/ppid/login.php`

## Struktur File

```
/ppid/
├── index.php          (halaman publik / daftar dokumen)
├── detail.php         (preview dokumen)
├── download.php       (download file)
├── login.php          (login admin)
├── dashboard.php      (dashboard admin)
├── tambah.php         (tambah dokumen)
├── edit.php           (edit dokumen)
├── hapus.php          (hapus dokumen)
├── koneksi.php        (koneksi database)
├── database.sql       (file SQL untuk setup database)
└── uploads/           (folder file PDF)
```

## Fitur

### Guest (Publik)
- Melihat daftar dokumen dari database
- Search dokumen menggunakan method GET
- Filter kategori menggunakan GET
- Preview PDF dokumen
- Download file langsung

### Admin
- Login dengan username dan password
- Dashboard menampilkan tabel dokumen
- Tambah dokumen (upload PDF)
- Edit dokumen
- Hapus dokumen
- Logout

## Teknologi

- PHP Native (Procedural)
- MySQL Database
- Tailwind CSS (CDN)
- Session untuk authentication

## Catatan

- Semua file menggunakan PHP procedural biasa
- Tidak menggunakan framework atau library tambahan
- Menggunakan method POST dan GET (tidak menggunakan AJAX/Fetch)
- Session digunakan untuk autentikasi admin

