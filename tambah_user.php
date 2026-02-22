<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/User.php';

check_login();

$page_title = 'Tambah User';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $email = $_POST['email'] ?? '';
    
    // Validasi
    if (empty($username) || empty($password) || empty($nama_lengkap) || empty($email)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password != $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } else {
        $user = new User($conn);
        $result = $user->add_user($username, $password, $nama_lengkap, $email);
        
        if ($result['success']) {
            header("Location: daftar_user.php?status=added");
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

require_once 'pages/header.php';
?>

<div class="main-content">
    <div class="container">
        <div class="card" style="max-width: 500px; margin: 0 auto;">
            <h2>➕ Tambah User Baru</h2>
            
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-success">✅ Simpan User</button>
                    <a href="daftar_user.php" class="btn">❌ Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'pages/footer.php'; ?>
