<?php
// Setup Database Script
$host = 'localhost';
$username = 'root';
$password = '';

// Create connection without database
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Setup Error</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .error { background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; border: 1px solid #f5c6cb; }
        code { background-color: #f5f5f5; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class='error'>
        <h2>❌ Koneksi Database Gagal</h2>
        <p><strong>Error:</strong> " . $conn->connect_error . "</p>
        <p>Pastikan:</p>
        <ul>
            <li>MySQL/MariaDB sudah running</li>
            <li>Username: <code>root</code></li>
            <li>Password: <code>(kosong)</code></li>
            <li>Host: <code>localhost</code></li>
        </ul>
        <p><a href='javascript:history.back()'>← Kembali</a></p>
    </div>
</body>
</html>");
}

// Read SQL file
$sql_file = __DIR__ . '/database.sql';
if (!file_exists($sql_file)) {
    die("<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Setup Error</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; }
        .error { background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <div class='error'>
        <h2>❌ File database.sql Tidak Ditemukan</h2>
        <p>Pastikan file <code>database.sql</code> ada di folder root aplikasi.</p>
    </div>
</body>
</html>");
}

$sql_content = file_get_contents($sql_file);

// Execute SQL
$success = true;
$errors = [];

// Split queries
$queries = array_filter(
    array_map('trim', explode(';', $sql_content)),
    function($q) { return !empty($q) && strpos($q, '--') !== 0; }
);

foreach ($queries as $query) {
    if (!empty(trim($query))) {
        if (!$conn->query($query)) {
            $success = false;
            $errors[] = $conn->error;
        }
    }
}

$conn->close();

// Display result
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Setup Database</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 600px;
            padding: 40px;
            text-align: center;
        }
        
        .success {
            color: #155724;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .error-box {
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }
        
        .error-box h3 {
            margin-bottom: 10px;
        }
        
        .error-box pre {
            background-color: #fff;
            padding: 10px;
            border-radius: 3px;
            overflow-x: auto;
            font-size: 12px;
            color: #721c24;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            text-align: left;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .info strong {
            display: block;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        
        .btn:hover {
            background-color: #5568d3;
        }
        
        .btn-secondary {
            background-color: #6c757d;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($success): ?>
            <h1>✅ Setup Berhasil!</h1>
            
            <div class="success">
                <h2 style="margin: 0;">Database telah dibuat dengan sukses</h2>
            </div>
            
            <div class="info">
                <strong>📌 Informasi Default User:</strong>
                Username: <strong>admin</strong><br>
                Password: <strong>admin123</strong>
                
                <strong>📌 Informasi Default Masjid:</strong>
                Nama Masjid: <strong>Masjid Anda</strong><br>
                Alamat: <strong>Jalan Jalan, Kota, Negara</strong>
            </div>
            
            <div class="info" style="background-color: #cfe2ff; color: #084298; border-color: #b6d4fe;">
                <strong>💡 Langkah Berikutnya:</strong>
                1. Klik tombol "Login" di bawah<br>
                2. Login dengan username: <strong>admin</strong> dan password: <strong>admin123</strong><br>
                3. Ubah pengaturan nama masjid dan alamat via menu ⚙️ Pengaturan
            </div>
            
            <a href="login.php" class="btn">🔐 Ke Halaman Login</a>
            <a href="index.php" class="btn btn-secondary" onclick="alert('Silakan login terlebih dahulu')">📊 Dashboard</a>
        
        <?php else: ?>
            <h1>❌ Setup Gagal</h1>
            
            <div class="error-box">
                <h3>Ada kesalahan saat membuat database:</h3>
                <pre><?php echo implode("\n", $errors); ?></pre>
            </div>
            
            <div class="info">
                <strong>Kemungkinan penyebab:</strong>
                • Database atau tabel sudah ada sebelumnya<br>
                • Masalah permission MySQL<br>
                • Syntax error di SQL file
                
                <strong style="display: block; margin-top: 15px;">Solusi:</strong>
                1. Hapus database 'aruskas' manual dari phpMyAdmin<br>
                2. Coba setup lagi<br>
                3. Atau import database.sql manual melalui phpMyAdmin
            </div>
            
            <a href="javascript:location.reload()" class="btn">🔄 Coba Lagi</a>
            <a href="login.php" class="btn btn-secondary">◀ Kembali</a>
        
        <?php endif; ?>
    </div>
</body>
</html>
