<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/User.php';

check_login();

$user_id = $_GET['id'] ?? 0;

if (empty($user_id)) {
    header("Location: daftar_user.php");
    exit;
}

$page_title = 'Edit User';

$user = new User($conn);
$user_data = $user->get_user($user_id);

if (!$user_data) {
    header("Location: daftar_user.php");
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $_POST['nama_lengkap'] ?? '';
    $email = $_POST['email'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    
    // Validasi
    if (empty($nama_lengkap) || empty($email)) {
        $error = 'Nama lengkap dan email harus diisi!';
    } else {
        $result = $user->update_user($user_id, $nama_lengkap, $email);
        
        if ($result['success']) {
            // Update password jika ada
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $error = 'Password minimal 6 karakter!';
                } else {
                    $user->change_password($user_id, $new_password);
                }
            }
            
            if (empty($error)) {
                header("Location: daftar_user.php?status=updated");
                exit;
            }
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
            <h2>✏️ Edit User</h2>
            
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" value="<?php echo htmlspecialchars($user_data['username']); ?>" disabled>
                    <small style="color: #999;">Username tidak dapat diubah</small>
                </div>
                
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user_data['nama_lengkap']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                </div>
                
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid #ecf0f1;">
                
                <h3 style="font-size: 1rem; color: #2c3e50; margin-bottom: 1rem;">Ubah Password (Opsional)</h3>
                
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password">
                    <small style="color: #999;">Kosongkan jika tidak ingin mengubah password</small>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-success">✅ Simpan Perubahan</button>
                    <a href="daftar_user.php" class="btn">❌ Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'pages/footer.php'; ?>
