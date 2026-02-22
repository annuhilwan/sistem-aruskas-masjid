<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Arus Kas Masjid - Informasi</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem;
            color: #333;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 3rem;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 1rem;
            text-align: center;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        
        .section {
            margin-bottom: 2rem;
            border-bottom: 1px solid #ecf0f1;
            padding-bottom: 2rem;
        }
        
        .section:last-child {
            border-bottom: none;
        }
        
        h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
            padding-left: 1rem;
        }
        
        .step {
            background-color: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
            border-left: 4px solid #3498db;
        }
        
        .step h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .code {
            background-color: #2c3e50;
            color: #ecf0f1;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:hover {
            background-color: #5568d3;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        .alert {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert.success {
            background-color: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin: 1rem 0;
        }
        
        .feature-card {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 4px;
            text-align: center;
            border-top: 3px solid #667eea;
        }
        
        .feature-card h4 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        
        .feature-card p {
            color: #666;
            font-size: 0.9rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }
        
        table th {
            background-color: #667eea;
            color: white;
            padding: 0.75rem;
            text-align: left;
        }
        
        table td {
            padding: 0.75rem;
            border-bottom: 1px solid #ecf0f1;
        }
        
        table tr:hover {
            background-color: #f8f9fa;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 Sistem Arus Kas Masjid</h1>
        <p class="subtitle">Aplikasi PHP Native untuk Mengelola Keuangan Masjid</p>
        
        <div class="alert success">
            ✅ <strong>Instalasi Berhasil!</strong> Sistem siap digunakan.
        </div>
        
        <!-- Quick Start -->
        <div class="section">
            <h2>🚀 Mulai Cepat</h2>
            
            <div class="btn-group">
                <a href="login.php" class="btn">📝 Login ke Aplikasi</a>
                <a href="#dokumentasi" class="btn btn-secondary">📚 Baca Dokumentasi</a>
            </div>
            
            <div class="alert">
                <strong>Login Default:</strong><br>
                Username: <code>admin</code><br>
                Password: <code>admin123</code>
            </div>
        </div>
        
        <!-- Fitur Utama -->
        <div class="section">
            <h2>✨ Fitur Utama</h2>
            
            <div class="features">
                <div class="feature-card">
                    <h4>📊 Dashboard</h4>
                    <p>Ringkasan total pemasukan, pengeluaran & saldo</p>
                </div>
                
                <div class="feature-card">
                    <h4>➕ Input Transaksi</h4>
                    <p>Mencatat pemasukan & pengeluaran dengan mudah</p>
                </div>
                
                <div class="feature-card">
                    <h4>📋 Laporan</h4>
                    <p>Melihat transaksi dengan filter tanggal & tipe</p>
                </div>
                
                <div class="feature-card">
                    <h4>✏️ Edit & Hapus</h4>
                    <p>Mengelola data transaksi yang sudah dicatat</p>
                </div>
                
                <div class="feature-card">
                    <h4>🔐 Keamanan</h4>
                    <p>Password terenkripsi & proteksi SQL Injection</p>
                </div>
                
                <div class="feature-card">
                    <h4>📱 Responsive</h4>
                    <p>Tampilannya bagus di desktop & mobile</p>
                </div>
            </div>
        </div>
        
        <!-- Setup Database -->
        <div class="section">
            <h2>📦 Verifikasi Database</h2>
            
            <p style="margin-bottom: 1rem;">Pastikan database sudah di-setup dengan benar. Jika belum, ikuti langkah di bawah:</p>
            
            <div class="step">
                <h3>Opsi 1: Menggunakan phpMyAdmin (Termudah)</h3>
                <ol style="margin-left: 1.5rem; margin-bottom: 0.5rem;">
                    <li>Buka http://localhost/phpmyadmin</li>
                    <li>Klik <strong>"New"</strong> untuk membuat database</li>
                    <li>Masukkan nama: <strong>aruskas</strong></li>
                    <li>Klik <strong>"Create"</strong></li>
                    <li>Pilih database <strong>aruskas</strong></li>
                    <li>Klik tab <strong>"Import"</strong></li>
                    <li>Upload file <strong>database.sql</strong></li>
                    <li>Klik <strong>"Go"</strong></li>
                </ol>
            </div>
            
            <div class="step">
                <h3>Opsi 2: Menggunakan Terminal</h3>
                <div class="code">mysql -u root &lt; database.sql</div>
            </div>
        </div>
        
        <!-- Struktur Folder -->
        <div class="section">
            <h2>🗂️ Struktur Folder</h2>
            
            <div class="code" style="text-align: left;">
aruskas/
├── config/               # Konfigurasi
│   ├── database.php
│   ├── session.php
│   └── helpers.php
├── class/                # Class/Model
│   ├── User.php
│   └── Transaksi.php
├── pages/                # Template
│   ├── header.php
│   └── footer.php
├── assets/               # Folder untuk CSS/JS (opsional)
├── database.sql          # File setup database
├── login.php             # Halaman login
├── index.php             # Dashboard
├── tambah_transaksi.php  # Tambah transaksi
├── edit_transaksi.php    # Edit/Kelola
└── laporan.php           # Laporan
            </div>
        </div>
        
        <!-- Dokumentasi -->
        <div class="section" id="dokumentasi">
            <h2>📚 Dokumentasi</h2>
            
            <p>Dokumentasi lengkap tersedia dalam file:</p>
            
            <table>
                <tr>
                    <th>File</th>
                    <th>Isi</th>
                </tr>
                <tr>
                    <td><strong>README.md</strong></td>
                    <td>Dokumentasi lengkap & panduan penggunaan</td>
                </tr>
                <tr>
                    <td><strong>INSTALASI.md</strong></td>
                    <td>Panduan instalasi cepat step-by-step</td>
                </tr>
                <tr>
                    <td><strong>API_DOCS.md</strong></td>
                    <td>Dokumentasi class & fungsi untuk developer</td>
                </tr>
            </table>
        </div>
        
        <!-- Menu Utama -->
        <div class="section">
            <h2>🎯 Menu Utama Aplikasi</h2>
            
            <table>
                <tr>
                    <th>Menu</th>
                    <th>Fungsi</th>
                </tr>
                <tr>
                    <td><strong>📊 Dashboard</strong></td>
                    <td>Melihat ringkasan keuangan & transaksi terbaru</td>
                </tr>
                <tr>
                    <td><strong>➕ Tambah Transaksi</strong></td>
                    <td>Input pemasukan atau pengeluaran baru</td>
                </tr>
                <tr>
                    <td><strong>📋 Laporan</strong></td>
                    <td>Melihat semua transaksi dengan filter</td>
                </tr>
                <tr>
                    <td><strong>✏️ Kelola Transaksi</strong></td>
                    <td>Edit atau hapus transaksi yang ada</td>
                </tr>
            </table>
        </div>
        
        <!-- Support -->
        <div class="section">
            <h2>💡 Tips & Bantuan</h2>
            
            <div class="step">
                <h3>🔒 Ubah Password Admin</h3>
                <p style="margin-bottom: 0.5rem;">Untuk keamanan, gunakan database tools untuk edit tabel users. Hash password menggunakan bcrypt online tool.</p>
            </div>
            
            <div class="step">
                <h3>💾 Backup Database</h3>
                <div class="code">mysqldump -u root aruskas > backup_aruskas.sql</div>
            </div>
            
            <div class="step">
                <h3>🔄 Reset Database</h3>
                <p style="margin-bottom: 0.5rem;">Hapus database dan import ulang database.sql di phpMyAdmin.</p>
            </div>
            
            <div class="step">
                <h3>🐛 Masalah Koneksi?</h3>
                <ol style="margin-left: 1.5rem;">
                    <li>Pastikan MySQL/MariaDB sedang berjalan</li>
                    <li>Cek username & password di config/database.php</li>
                    <li>Cek nama database sudah <strong>aruskas</strong></li>
                </ol>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="text-align: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #ecf0f1; color: #666;">
            <p>💰 <strong>Sistem Arus Kas Masjid</strong> v1.0</p>
            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Dibuat dengan ❤️ untuk kemudahan pengelolaan keuangan masjid</p>
        </div>
    </div>
</body>
</html>
