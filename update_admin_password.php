<?php
/**
 * FILE UNTUK UPDATE PASSWORD ADMIN DI DATABASE
 * Jalankan file ini melalui browser: http://localhost/aruskas/update_admin_password.php
 */

require_once 'config/database.php';

$message = "";
$success = false;

// Hash password yang benar untuk "admin123"
$correct_hash = '$2y$10$sDzXF4QZDy0p1SlEjf5cSOIJXvDk3LljR4VvzvLPqZOWrUlgXabNu';

// Cek apakah sudah ada user admin
$result = $conn->query("SELECT * FROM users WHERE username='admin'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Verify password dengan hash yang ada
    $verify = password_verify('admin123', $user['password']);
    
    if ($verify) {
        $message = "✅ Password SUDAH BENAR untuk admin123";
        $success = true;
    } else {
        // Update dengan hash yang benar
        $update_query = "UPDATE users SET password='$correct_hash' WHERE username='admin'";
        
        if ($conn->query($update_query)) {
            $message = "✅ Password admin BERHASIL DIUPDATE!<br>";
            $message .= "Username: <strong>admin</strong><br>";
            $message .= "Password: <strong>admin123</strong><br>";
            $message .= "<br>Coba login di: <a href='login.php'>login.php</a>";
            $success = true;
        } else {
            $message = "❌ Error: " . $conn->error;
        }
    }
} else {
    $message = "❌ User admin tidak ditemukan. Database mungkin belum di-import.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Admin Password - Arus Kas Masjid</title>
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
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            max-width: 500px;
            width: 100%;
            padding: 2rem;
            text-align: center;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }
        
        .message {
            padding: 1.5rem;
            border-radius: 4px;
            margin: 1.5rem 0;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .code-block {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 1rem;
            margin: 1rem 0;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            line-height: 1.6;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 1rem;
            transition: background-color 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .btn:hover {
            background-color: #5568d3;
        }
        
        .footer {
            color: #999;
            font-size: 0.85rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #ecf0f1;
        }
        
        strong {
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Update Admin Password</h1>
        
        <div class="message <?php echo $success ? 'success' : 'error'; ?>">
            <?php echo $message; ?>
        </div>
        
        <?php if ($success && password_verify('admin123', $correct_hash)): ?>
            <div style="margin-top: 1rem;">
                <h3>✅ Password Sudah Valid</h3>
                <p>Anda bisa login dengan:</p>
                <div class="code-block">
                    Username: <strong>admin</strong><br>
                    Password: <strong>admin123</strong>
                </div>
                <a href="login.php" class="btn">🔓 Kembali ke Login</a>
            </div>
        <?php else: ?>
            <div style="margin-top: 1rem;">
                <h3>ℹ️ Informasi Hash Password</h3>
                <p>Hash password yang digunakan:</p>
                <div class="code-block">
                    <?php echo $correct_hash; ?>
                </div>
                <p style="margin-top: 1rem; color: #666; font-size: 0.9rem;">
                    Ini adalah hash bcrypt yang valid untuk password "admin123"
                </p>
            </div>
        <?php endif; ?>
        
        <div class="footer">
            <p>💰 Sistem Arus Kas Masjid</p>
            <p style="margin-top: 0.5rem;">Update password admin - 2026</p>
        </div>
    </div>
</body>
</html>
