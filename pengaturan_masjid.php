<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Settings.php';

check_login();

$page_title = 'Pengaturan Masjid';
require_once 'pages/header.php';

$settings = new Settings($conn);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_masjid = $_POST['nama_masjid'] ?? '';
    $alamat_masjid = $_POST['alamat_masjid'] ?? '';
    
    if (empty($nama_masjid) || empty($alamat_masjid)) {
        $error_message = 'Semua field harus diisi!';
    } else {
        $result1 = $settings->update_setting('nama_masjid', $nama_masjid);
        $result2 = $settings->update_setting('alamat_masjid', $alamat_masjid);
        
        if ($result1['success'] && $result2['success']) {
            $success_message = '✅ Pengaturan berhasil disimpan!';
        } else {
            $error_message = 'Gagal menyimpan pengaturan!';
        }
    }
}

// Get current settings
$current_settings = $settings->get_all_settings();
?>

<div class="main-content">
    <div class="container">
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <h2>⚙️ Pengaturan Masjid</h2>
            
            <?php if (!empty($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nama_masjid">Nama Masjid</label>
                    <input type="text" id="nama_masjid" name="nama_masjid" 
                           value="<?php echo htmlspecialchars($current_settings['nama_masjid'] ?? ''); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="alamat_masjid">Alamat Masjid</label>
                    <textarea id="alamat_masjid" name="alamat_masjid" rows="4" required><?php echo htmlspecialchars($current_settings['alamat_masjid'] ?? ''); ?></textarea>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-success">💾 Simpan Pengaturan</button>
                    <a href="index.php" class="btn">❌ Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'pages/footer.php'; ?>
