# 💰 Sistem Arus Kas Masjid - PHP Native

Sistem Arus Kas Masjid adalah aplikasi berbasis web yang dibangun dengan PHP Native untuk mencatat dan mengelola keuangan masjid.
 
## 📋 Fitur Utama

✅ **Sistem Login** - Keamanan dengan password hashing menggunakan bcrypt  
✅ **Dashboard** - Ringkasan total pemasukan, pengeluaran, dan saldo  
✅ **Input Transaksi** - Mencatat pemasukan dan pengeluaran  
✅ **Laporan** - Melihat semua transaksi dengan filter tanggal dan tipe  
✅ **Edit & Hapus** - Mengelola transaksi yang sudah dicatat  
✅ **Responsive Design** - Tampilan yang bagus di desktop dan mobile  

---

## 🗂️ Struktur Folder

```
aruskas/
├── config/
│   ├── database.php      # Konfigurasi koneksi database
│   └── session.php       # Manajemen session & autentikasi
├── class/
│   ├── User.php          # Class untuk manajemen user
│   └── Transaksi.php     # Class untuk manajemen transaksi
├── pages/
│   ├── header.php        # Template header
│   └── footer.php        # Template footer
├── assets/               # Folder untuk CSS, JS, images (opsional)
├── database.sql          # File setup database
├── login.php             # Halaman login
├── logout.php            # Proses logout
├── index.php             # Dashboard
├── tambah_transaksi.php  # Form tambah transaksi
├── edit_transaksi.php    # Kelola & edit transaksi
└── laporan.php           # Laporan transaksi
```

---

## 📦 Struktur Database

### Tabel: users
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel: transaksi
```sql
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    tipe ENUM('masuk','keluar') NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal),
    INDEX idx_tipe (tipe)
);
```

---

## 🚀 Cara Setup

### 1. **Persiapan**
Pastikan Anda sudah menginstal:
- XAMPP (atau server lokal PHP)
- MySQL/MariaDB
- Browser modern

### 2. **Copy File ke Folder Web Root**
```bash
cp -r aruskas /Applications/XAMPP/xamppfiles/htdocs/
# Atau drag-drop folder aruskas ke folder htdocs
```

### 3. **Setup Database**

**Cara A: Menggunakan Setup Script (Termudah)** ⭐
- **PENTING: Pastikan MySQL sudah running di XAMPP Control Panel!**
- Buka browser dan akses: `http://localhost/aruskas/setup.php`
- Database akan dibuat secara otomatis
- Ikuti petunjuk di layar
- Login dengan username: `admin` password: `admin123`

**Cara B: Menggunakan phpMyAdmin**
- Buka http://localhost/phpmyadmin
- Klik "New" untuk buat database baru
- Masukkan nama: `aruskas`
- Pilih encoding: `utf8_general_ci`
- Klik "Create"
- Pilih database `aruskas`
- Klik tab "Import"
- Upload file `database.sql` dari folder aruskas
- Klik "Go"

**Cara C: Menggunakan Command Line**
```bash
mysql -u root < /Applications/XAMPP/xamppfiles/htdocs/aruskas/database.sql
```

### 4. **Konfigurasi Database (Opsional)**
Jika konfigurasi database berbeda, edit file `config/database.php`:

```php
define('DB_HOST', 'localhost');    // Host database
define('DB_USER', 'root');         // Username database
define('DB_PASS', '');             // Password database (kosong jika tidak ada)
define('DB_NAME', 'aruskas');      // Nama database
```

### 5. **Akses Aplikasi**
Buka browser dan kunjungi:
```
http://localhost/aruskas
```

---

## 🔐 Login Default

Setelah setup database, gunakan akun default:

| Field | Nilai |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

⚠️ **PENTING**: Ubah password default setelah login pertama kali!

---

## 📖 Panduan Penggunaan

### 📊 Dashboard
- Menampilkan ringkasan total pemasukan, pengeluaran, dan saldo
- Menampilkan 5 transaksi terbaru
- Tombol cepat ke laporan lengkap

### ➕ Tambah Transaksi
1. Klik menu "Tambah Transaksi"
2. Isi form dengan data:
   - **Tanggal**: Tanggal transaksi
   - **Tipe**: Pilih "Pemasukan" atau "Pengeluaran"
   - **Jumlah**: Nominal uang (angka saja)
   - **Keterangan**: Deskripsi transaksi
3. Klik "Simpan Transaksi"

### 📋 Laporan
- Menampilkan semua transaksi
- Filter berdasarkan tipe (masuk/keluar)
- Filter berdasarkan rentang tanggal
- Tombol edit & hapus untuk setiap transaksi

### ✏️ Edit Transaksi
1. Di halaman Laporan, klik tombol "Edit"
2. Ubah data yang diperlukan
3. Klik "Simpan Perubahan"

### 🗑️ Hapus Transaksi
1. Di halaman Laporan, klik tombol "Hapus"
2. Konfirmasi penghapusan
3. Transaksi akan dihapus

---

## 🔧 Fitur Lanjutan

### Class User (`class/User.php`)
- `login($username, $password)` - Login user
- `get_user($id)` - Ambil data user
- `register($username, $password, $nama, $email)` - Daftar user baru

### Class Transaksi (`class/Transaksi.php`)
- `add($tanggal, $keterangan, $tipe, $jumlah)` - Tambah transaksi
- `edit($id, $tanggal, $keterangan, $tipe, $jumlah)` - Edit transaksi
- `delete($id)` - Hapus transaksi
- `get($id)` - Ambil data transaksi
- `get_all($order)` - Ambil semua transaksi
- `get_by_type($tipe)` - Filter berdasarkan tipe
- `get_by_date($start, $end)` - Filter berdasarkan tanggal
- `total_masuk()` - Hitung total pemasukan
- `total_keluar()` - Hitung total pengeluaran
- `saldo()` - Hitung saldo akhir

---

## 🛡️ Keamanan

✅ Password dienkripsi dengan bcrypt  
✅ SQL Injection Prevention dengan `real_escape_string()`  
✅ Session-based authentication  
✅ CSRF protection dengan form validation  
✅ XSS prevention dengan `htmlspecialchars()`  

---

## 🐛 Troubleshooting

### Gagal Login
- Pastikan database sudah di-import dengan benar
- Cek username & password: `admin` / `admin123`

### Database Connection Error
- Pastikan MySQL/MariaDB sedang berjalan
- Cek konfigurasi di `config/database.php`
- Pastikan database `aruskas` sudah dibuat

### Error 404
- Pastikan folder `aruskas` sudah di-copy ke `htdocs`
- Cek URL: `http://localhost/aruskas`

### Halaman Kosong
- Aktifkan error reporting di `config/database.php`
- Cek error logs di XAMPP

---

## 📝 Catatan Pengembangan

Fitur yang bisa ditambahkan di masa depan:
- ✨ Manajemen kategori pemasukan/pengeluaran
- ✨ Export laporan ke PDF/Excel
- ✨ Grafik/chart visualisasi data
- ✨ Manajemen multiple user dengan role
- ✨ Approval workflow untuk transaksi besar
- ✨ Backup & restore database otomatis

---

## 📄 Lisensi

Sistem ini bebas digunakan untuk keperluan masjid dan organisasi nirlaba.

---

## 💬 Dukungan

Jika ada pertanyaan atau masalah, silakan hubungi developer.

---

**Dibuat dengan ❤️ untuk kemudahan pengelolaan keuangan masjid**

---

## © Copyright

Copyright © 2026 [Annuh Liwan Nahar](https://annuhliwan.my.id). All rights reserved.
