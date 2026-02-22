<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/User.php';
require_once 'class/Settings.php';

redirect_if_logged_in();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = new User($conn);
    $user_data = $user->login($username, $password);
    
    if ($user_data) {
        $_SESSION['user_id'] = $user_data['id'];
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['nama_lengkap'] = $user_data['nama_lengkap'];
        
        header("Location: index.php");
        exit;
    } else {
        $error = 'Username atau password salah!';
    }
}

// Get settings
$settings = new Settings($conn);
$nama_masjid = $settings->get_setting('nama_masjid') ?? 'Masjid Anda';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Arus Kas Masjid</title>
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
            color: #333;
        }
        
        .login-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            padding: 3rem 2rem;
            text-align: center;
        }
        
        .login-container h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #667eea;
        }
        
        .login-container p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #ecf0f1;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            border: 1px solid #f5c6cb;
        }
        
        .btn {
            width: 100%;
            padding: 0.75rem;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #5568d3;
        }
        
        .info-box {
            background-color: #d1ecf1;
            color: #0c5460;
            padding: 1rem;
            border-radius: 4px;
            margin-top: 2rem;
            border: 1px solid #bee5eb;
            font-size: 0.9rem;
            text-align: left;
        }
        
        .info-box strong {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>💰 Arus Kas</h1>
        <p><?php echo htmlspecialchars($nama_masjid); ?></p>
        
        <?php if ($error): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn">Login</button>
        </form>
        
        <div class="info-box">
            <strong>Demo Login:</strong>
            Username: <strong>admin</strong><br>
            Password: <strong>admin123</strong>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #ecf0f1; color: #999; font-size: 12px;">
            <p>Copyright © 2026 <a href="https://annuhliwan.my.id" target="_blank" style="color: #667eea; text-decoration: none;">Annuh Liwan Nahar</a>. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
