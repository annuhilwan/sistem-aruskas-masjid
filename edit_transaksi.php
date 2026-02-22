<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

$page_title = 'Kelola Transaksi';

$transaksi_obj = new Transaksi($conn);
$success = '';
$error = '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? '';

// Proses Edit
if ($action == 'edit' && $id) {
    $transaksi_data = $transaksi_obj->get($id);
    
    if (!$transaksi_data) {
        header("Location: laporan.php");
        exit;
    }
    
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
            $result = $transaksi_obj->edit($id, $tanggal, $keterangan, $tipe, $jumlah);
            
            if ($result) {
                $success = 'Transaksi berhasil diperbarui!';
                $transaksi_data = $transaksi_obj->get($id);
            } else {
                $error = 'Gagal memperbarui transaksi!';
            }
        }
    }
}

// Proses Delete
if ($action == 'delete' && $id) {
    if ($transaksi_obj->delete($id)) {
        $success = 'Transaksi berhasil dihapus!';
        $action = 'list';
    } else {
        $error = 'Gagal menghapus transaksi!';
    }
}

// Get all transaksi
$semua_transaksi = $transaksi_obj->get_all('DESC');
?>

<?php include 'pages/header.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <h2><?php echo $action == 'edit' ? '✏️ Edit Transaksi' : '✏️ Kelola Transaksi'; ?></h2>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($action == 'edit' && $transaksi_data): ?>
                    <!-- Form Edit -->
                    <form method="POST">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                            <div class="form-group">
                                <label for="tanggal">Tanggal Transaksi *</label>
                                <input type="date" id="tanggal" name="tanggal" value="<?php echo $transaksi_data['tanggal']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="tipe">Tipe Transaksi *</label>
                                <select id="tipe" name="tipe" required>
                                    <option value="masuk" <?php echo $transaksi_data['tipe'] == 'masuk' ? 'selected' : ''; ?>>Pemasukan</option>
                                    <option value="keluar" <?php echo $transaksi_data['tipe'] == 'keluar' ? 'selected' : ''; ?>>Pengeluaran</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="jumlah">Jumlah (Rp) *</label>
                                <input type="number" id="jumlah" name="jumlah" value="<?php echo $transaksi_data['jumlah']; ?>" min="1" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan">Keterangan *</label>
                            <textarea id="keterangan" name="keterangan" rows="4" required><?php echo htmlspecialchars($transaksi_data['keterangan']); ?></textarea>
                        </div>
                        
                        <div class="btn-group">
                            <button type="submit" class="btn btn-success">✅ Simpan Perubahan</button>
                            <a href="edit_transaksi.php" class="btn" style="background-color: #95a5a6;">Batal</a>
                        </div>
                    </form>
                    
                <?php else: ?>
                    <!-- Daftar Transaksi -->
                    <?php if (count($semua_transaksi) > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($semua_transaksi as $t): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($t['tanggal'])); ?></td>
                                        <td><?php echo htmlspecialchars($t['keterangan']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $t['tipe']; ?>">
                                                <?php echo ucfirst($t['tipe']); ?>
                                            </span>
                                        </td>
                                        <td class="text-right currency">
                                            Rp <?php echo number_format($t['jumlah'], 0, ',', '.'); ?>
                                        </td>
                                        <td style="display: flex; gap: 0.5rem;">
                                            <a href="edit_transaksi.php?id=<?php echo $t['id']; ?>&action=edit" class="btn btn-warning" style="font-size: 0.85rem; padding: 0.5rem 0.75rem; text-decoration: none; display: inline-block;">Edit</a>
                                            <a href="edit_transaksi.php?id=<?php echo $t['id']; ?>&action=delete" class="btn btn-danger" style="font-size: 0.85rem; padding: 0.5rem 0.75rem; text-decoration: none; display: inline-block;" onclick="return confirm('Yakin ingin menghapus transaksi ini?');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #999; padding: 2rem;">Tidak ada transaksi</p>
                    <?php endif; ?>
                    
                <?php endif; ?>
                
                <div style="margin-top: 1rem;">
                    <a href="tambah_transaksi.php" class="btn btn-success">➕ Tambah Transaksi Baru</a>
                    <a href="laporan.php" class="btn" style="background-color: #95a5a6;">Lihat Laporan</a>
                </div>
            </div>
        </div>
    </div>

<?php include 'pages/footer.php'; ?>
