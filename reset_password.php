<?php
/**
 * RESET ADMIN PASSWORD - SOLUSI PERMANEN
 * Jalankan di browser: http://localhost/aruskas/reset_password.php
 */

// Direct database connection untuk bypass potential issues
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'aruskas';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("❌ Koneksi database gagal: " . $conn->connect_error);
}

$message = "";
$success = false;

// Generate password hash yang PASTI benar untuk "admin123"
$password_plain = "admin123";
$password_hashed = password_hash($password_plain, PASSWORD_BCRYPT, ['cost' => 10]);

// Debug: Verify hash langsung
$verify = password_verify($password_plain, $password_hashed);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] == 'reset') {
        
        // Cek apakah user admin sudah ada
        $check = $conn->query("SELECT id FROM users WHERE username='admin'");
        
        if ($check->num_rows > 0) {
            // Update existing admin
            $update = $conn->query("UPDATE users SET password='$password_hashed' WHERE username='admin'");
            if ($update) {
                $message = "✅ Password admin BERHASIL DIRESET<br>";
                $message .= "Username: <strong>admin</strong><br>";
                $message .= "Password: <strong>admin123</strong>";
                $success = true;
            } else {
                $message = "❌ Gagal update password: " . $conn->error;
            }
        } else {
            // Insert new admin if not exists
            $insert = $conn->query("INSERT INTO users (username, password, nama_lengkap, email) VALUES ('admin', '$password_hashed', 'Administrator', 'admin@masjid.com')");
            if ($insert) {
                $message = "✅ User admin BERHASIL DIBUAT<br>";
                $message .= "Username: <strong>admin</strong><br>";
                $message .= "Password: <strong>admin123</strong>";
                $success = true;
            } else {
                $message = "❌ Gagal membuat user: " . $conn->error;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Admin Password</title>
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
            padding: 2rem;
        }
        
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 2rem;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 1rem;
            text-align: center;
            font-size: 1.5rem;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.9rem;
        }
        
        .status {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .info-box {
            background: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
            margin: 1rem 0;
            border-left: 4px solid #667eea;
        }
        
        .info-box strong {
            color: #333;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .info-box code {
            background: white;
            padding: 0.5rem;
            display: block;
            font-family: monospace;
            color: #667eea;
            margin-top: 0.5rem;
        }
        
        button {
            width: 100%;
            padding: 0.75rem;
            background: #27ae60;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }
        
        button:hover {
            background: #229954;
        }
        
        button:active {
            transform: scale(0.98);
        }
        
        .links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #ecf0f1;
        }
        
        .links a {
            display: inline-block;
            margin: 0.5rem 0.25rem;
            padding: 0.5rem 1rem;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.85rem;
            transition: background 0.3s;
        }
        
        .links a:hover {
            background: #5568d3;
        }
        
        .hash-info {
            background: #f0f0f0;
            padding: 1rem;
            border-radius: 4px;
            margin: 1rem 0;
            word-break: break-all;
            font-family: monospace;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Reset Admin Password</h1>
        <p class="subtitle">Solusi permanen untuk masalah login</p>
        
        <?php if (!empty($message)): ?>
            <div class="status <?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
            
            <?php if ($success): ?>
                <div class="info-box">
                    <strong>✅ Sekarang bisa login dengan:</strong>
                    <code>Username: admin</code>
                    <code>Password: admin123</code>
                </div>
                
                <div class="links">
                    <a href="login.php">🔓 Buka Login</a>
                    <a href="info.php">ℹ️ Info Sistem</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (!$success): ?>
            <div class="info-box">
                <strong>ℹ️ Informasi:</strong>
                <p>Fitur ini akan reset password admin dengan:</p>
                <code>Username: admin</code>
                <code>Password: admin123</code>
            </div>
            
            <div class="status info">
                ℹ️ Klik tombol di bawah untuk reset password admin sekarang
            </div>
            
            <div class="hash-info">
                <strong>Hash yang akan digunakan:</strong><br>
                <?php echo $password_hashed; ?><br><br>
                <strong>Verifikasi:</strong> <?php echo ($verify ? '✅ VALID' : '❌ INVALID'); ?>
            </div>
            
            <form method="POST">
                <input type="hidden" name="action" value="reset">
                <button type="submit">🔧 Reset Password Admin Sekarang</button>
            </form>
            
            <div class="links">
                <a href="login.php">🔓 Login</a>
                <a href="diagnosa_login.php">🔍 Diagnosa</a>
                <a href="info.php">ℹ️ Info</a>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #ecf0f1; color: #999; font-size: 0.85rem;">
            <p>💰 Sistem Arus Kas Masjid</p>
        </div>
    </div>
</body>
</html>
