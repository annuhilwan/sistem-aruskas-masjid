<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

$page_title = 'Tambah Transaksi';

$transaksi_obj = new Transaksi($conn);
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'] ?? '';
    $keterangan = $_POST['keterangan'] ?? '';
    $tipe = $_POST['tipe'] ?? '';
    $jumlah = $_POST['jumlah'] ?? '';
    
    // Validasi
    if (empty($tanggal) || empty($keterangan) || empty($tipe) || empty($jumlah)) {
        $error = 'Semua field harus diisi!';
    } elseif (!is_numeric($jumlah) || $jumlah <= 0) {
        $error = 'Jumlah harus berupa angka positif!';
    } else {
        $result = $transaksi_obj->add($tanggal, $keterangan, $tipe, $jumlah);
        
        if ($result) {
            $success = 'Transaksi berhasil ditambahkan!';
            // Reset form
            $tanggal = date('Y-m-d');
            $keterangan = '';
            $tipe = 'masuk';
            $jumlah = '';
        } else {
            $error = 'Gagal menambahkan transaksi!';
        }
    }
}

// Set default tanggal hari ini
$tanggal = $_POST['tanggal'] ?? date('Y-m-d');
?>

<?php include 'pages/header.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <h2>➕ Tambah Transaksi Baru</h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        <div class="form-group">
                            <label for="tanggal">Tanggal Transaksi *</label>
                            <input type="date" id="tanggal" name="tanggal" value="<?php echo $tanggal; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tipe">Tipe Transaksi *</label>
                            <select id="tipe" name="tipe" required>
                                <option value="masuk">Pemasukan</option>
                                <option value="keluar">Pengeluaran</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="jumlah">Jumlah (Rp) *</label>
                            <input type="number" id="jumlah" name="jumlah" min="1" step="0.01" placeholder="Contoh: 100000" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="keterangan">Keterangan *</label>
                        <textarea id="keterangan" name="keterangan" rows="4" placeholder="Contoh: Zakat bulanan, Pembangunan masjid, dll" required></textarea>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">✅ Simpan Transaksi</button>
                        <a href="laporan.php" class="btn" style="background-color: #95a5a6;">Kembali</a>
                    </div>
                </form>
                
                <hr style="margin: 2rem 0; border: none; border-top: 1px solid #ecf0f1;">
                
                <h3>💡 Contoh Pemasukan:</h3>
                <ul style="margin-left: 1.5rem; margin-bottom: 1.5rem;">
                    <li>Zakat Fitrah</li>
                    <li>Zakat Maal</li>
                    <li>Infaq</li>
                    <li>Sedekah</li>
                    <li>Donasi</li>
                    <li>Iuran Jamaah</li>
                </ul>
                
                <h3>💡 Contoh Pengeluaran:</h3>
                <ul style="margin-left: 1.5rem;">
                    <li>Gaji Imam</li>
                    <li>Listrik & Air</li>
                    <li>Pemeliharaan Bangunan</li>
                    <li>Pembangunan/Renovasi</li>
                    <li>Dana Sosial</li>
                    <li>Operasional</li>
                </ul>
            </div>
        </div>
    </div>

<?php include 'pages/footer.php'; ?>
