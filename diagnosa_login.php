<?php
/**
 * SCRIPT DIAGNOSA MASALAH LOGIN
 * Jalankan file ini di browser untuk debug: http://localhost/aruskas/diagnosa_login.php
 */

require_once 'config/database.php';

$diagnostics = array();

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Diagnosa Login - Arus Kas Masjid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 2rem;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 1rem;
        }
        
        .section {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        
        .section h2 {
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }
        
        .status {
            padding: 0.5rem;
            margin: 0.5rem 0;
            border-radius: 3px;
            font-size: 0.9rem;
        }
        
        .status.ok {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status.error {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status.info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        
        code {
            background: #f0f0f0;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            font-weight: bold;
        }
        
        pre {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
            margin: 1rem 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
            font-size: 0.85rem;
        }
        
        table th {
            background: #667eea;
            color: white;
            padding: 0.75rem;
            text-align: left;
        }
        
        table td {
            padding: 0.5rem 0.75rem;
            border-bottom: 1px solid #ecf0f1;
        }
        
        table tr:hover {
            background: #f9f9f9;
        }
        
        .fix-button {
            background: #27ae60;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }
        
        .fix-button:hover {
            background: #229954;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 DIAGNOSA MASALAH LOGIN</h1>";

// 1. Check Database Connection
echo "<div class='section'>
    <h2>1️⃣  Koneksi Database</h2>";

if ($conn->connect_error) {
    echo "<div class='status error'>❌ ERROR: Koneksi database gagal</div>";
    echo "<div class='status error'>" . $conn->connect_error . "</div>";
} else {
    echo "<div class='status ok'>✅ Database terhubung</div>";
    echo "<div class='status info'>Host: " . DB_HOST . "</div>";
    echo "<div class='status info'>User: " . DB_USER . "</div>";
    echo "<div class='status info'>Database: " . DB_NAME . "</div>";
}

echo "</div>";

// 2. Check users table
echo "<div class='section'>
    <h2>2️⃣  Tabel Users</h2>";

$result = $conn->query("SELECT * FROM users");

if (!$result) {
    echo "<div class='status error'>❌ ERROR: Tabel users tidak ditemukan</div>";
    echo "<div class='status error'>" . $conn->error . "</div>";
} else {
    if ($result->num_rows == 0) {
        echo "<div class='status error'>❌ Tabel users KOSONG (tidak ada data user)</div>";
    } else {
        echo "<div class='status ok'>✅ Tabel users ada dengan " . $result->num_rows . " user</div>";
        
        echo "<table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>";
        
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row['id'] . "</td>
                <td><code>" . $row['username'] . "</code></td>
                <td>" . $row['nama_lengkap'] . "</td>
                <td>" . $row['email'] . "</td>
            </tr>";
        }
        
        echo "</tbody>
        </table>";
    }
}

echo "</div>";

// 3. Check admin user
echo "<div class='section'>
    <h2>3️⃣  User Admin</h2>";

$admin_check = $conn->query("SELECT * FROM users WHERE username='admin'");

if ($admin_check->num_rows == 0) {
    echo "<div class='status error'>❌ User 'admin' TIDAK DITEMUKAN</div>";
    echo "<p>Solusi: Buka <a href='update_admin_password.php'>update_admin_password.php</a> atau import database ulang</p>";
} else {
    $admin = $admin_check->fetch_assoc();
    echo "<div class='status ok'>✅ User 'admin' ditemukan</div>";
    
    echo "<table>
        <tr>
            <td><strong>ID</strong></td>
            <td>" . $admin['id'] . "</td>
        </tr>
        <tr>
            <td><strong>Username</strong></td>
            <td><code>" . $admin['username'] . "</code></td>
        </tr>
        <tr>
            <td><strong>Nama Lengkap</strong></td>
            <td>" . $admin['nama_lengkap'] . "</td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td>" . $admin['email'] . "</td>
        </tr>
        <tr>
            <td><strong>Password Hash</strong></td>
            <td><code style='font-size: 0.7rem;'>" . substr($admin['password'], 0, 50) . "...</code></td>
        </tr>
        <tr>
            <td><strong>Created At</strong></td>
            <td>" . $admin['created_at'] . "</td>
        </tr>
    </table>";
}

echo "</div>";

// 4. Test Password Verification
echo "<div class='section'>
    <h2>4️⃣  Verifikasi Password 'admin123'</h2>";

if ($admin_check->num_rows > 0) {
    $admin = $admin_check->fetch_assoc();
    
    // Test password_verify
    $test_password = 'admin123';
    $is_valid = password_verify($test_password, $admin['password']);
    
    if ($is_valid) {
        echo "<div class='status ok'>✅ Password 'admin123' COCOK dengan hash di database</div>";
        echo "<div class='status ok'>Anda harusnya bisa login!</div>";
    } else {
        echo "<div class='status error'>❌ Password 'admin123' TIDAK COCOK dengan hash</div>";
        echo "<div class='status error'>Hash password di database tidak valid untuk 'admin123'</div>";
        echo "<p style='margin-top: 1rem;'><strong>Solusi:</strong></p>";
        echo "<ol>
            <li>Buka: <a href='update_admin_password.php'>update_admin_password.php</a></li>
            <li>Atau buka: <a href='generate_password_hash.php'>generate_password_hash.php</a></li>
            <li>Copy hash yang benar dan update di phpMyAdmin</li>
        </ol>";
    }
} else {
    echo "<div class='status error'>❌ User admin tidak ditemukan, tidak bisa test password</div>";
}

echo "</div>";

// 5. Test Login Function
echo "<div class='section'>
    <h2>5️⃣  Test Fungsi Login</h2>";

require_once 'class/User.php';

$user_obj = new User($conn);
$test_login = $user_obj->login('admin', 'admin123');

if ($test_login) {
    echo "<div class='status ok'>✅ Fungsi login() BERHASIL</div>";
    echo "<table>
        <tr>
            <td><strong>ID</strong></td>
            <td>" . $test_login['id'] . "</td>
        </tr>
        <tr>
            <td><strong>Username</strong></td>
            <td>" . $test_login['username'] . "</td>
        </tr>
        <tr>
            <td><strong>Nama Lengkap</strong></td>
            <td>" . $test_login['nama_lengkap'] . "</td>
        </tr>
    </table>";
} else {
    echo "<div class='status error'>❌ Fungsi login() GAGAL</div>";
    echo "<div class='status error'>Kemungkinan: password hash tidak cocok</div>";
}

echo "</div>";

// 6. Recommendations
echo "<div class='section'>
    <h2>6️⃣  Rekomendasi Perbaikan</h2>";

if (!$admin_check || $admin_check->num_rows == 0) {
    echo "<div class='status error'>1. User admin tidak ada - PERLU DIBUAT</div>";
    echo "<a href='update_admin_password.php' class='fix-button'>🔧 Buat/Update Admin Password</a>";
} else {
    if ($is_valid) {
        echo "<div class='status ok'>✅ Semuanya OK! Coba login di: <a href='login.php'>login.php</a></div>";
    } else {
        echo "<div class='status error'>❌ Password hash tidak cocok - PERLU DIPERBAIKI</div>";
        echo "<a href='update_admin_password.php' class='fix-button'>🔧 Update Password Admin</a>";
        echo " atau ";
        echo "<a href='generate_password_hash.php' class='fix-button'>🔧 Generate Hash Baru</a>";
    }
}

echo "</div>";

// 7. Manual Test
echo "<div class='section'>
    <h2>7️⃣  Manual Test Password</h2>";

echo "<p>Jika ingin test dengan password lain, buka console PHP dan jalankan:</p>";
echo "<pre>";
echo "php -r \"echo password_hash('admin123', PASSWORD_BCRYPT) . PHP_EOL;\"";
echo "</pre>";

echo "<p>Atau akses: <a href='generate_password_hash.php'>generate_password_hash.php</a></p>";

echo "</div>";

echo "    </div>
</body>
</html>";

$conn->close();
?>
