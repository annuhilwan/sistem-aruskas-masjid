<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

$page_title = 'Laporan';

$transaksi = new Transaksi($conn);

// Filter berdasarkan parameter
$tipe_filter = $_GET['tipe'] ?? '';
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

$semua_transaksi = [];

// Logika filter yang benar
if ($tanggal_awal && $tanggal_akhir) {
    // Filter berdasarkan tanggal
    $semua_transaksi = $transaksi->get_by_date($tanggal_awal, $tanggal_akhir);
    
    // Jika ada tipe filter, filter lagi berdasarkan tipe
    if ($tipe_filter) {
        $semua_transaksi = array_filter($semua_transaksi, function($t) use ($tipe_filter) {
            return $t['tipe'] === $tipe_filter;
        });
    }
} elseif ($tipe_filter) {
    // Filter hanya berdasarkan tipe
    $semua_transaksi = $transaksi->get_by_type($tipe_filter);
} else {
    // Tidak ada filter, tampilkan semua
    $semua_transaksi = $transaksi->get_all('DESC');
}

// Hitung total berdasarkan data yang sudah difilter
$total_masuk_filtered = 0;
$total_keluar_filtered = 0;

foreach ($semua_transaksi as $t) {
    if ($t['tipe'] == 'masuk') {
        $total_masuk_filtered += $t['jumlah'];
    } else {
        $total_keluar_filtered += $t['jumlah'];
    }
}

$saldo_filtered = $total_masuk_filtered - $total_keluar_filtered;

// Grand total (tanpa filter - untuk reference)
$total_masuk = $transaksi->total_masuk();
$total_keluar = $transaksi->total_keluar();
$saldo = $transaksi->saldo();
?>

<?php include 'pages/header.php'; ?>

    <div class="main-content">
        <div class="container">
            <div class="card">
                <h2>Laporan Transaksi</h2>
                
                <!-- Filter -->
                <form method="GET" style="margin-bottom: 2rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="tipe_filter">Filter Tipe</label>
                            <select id="tipe_filter" name="tipe">
                                <option value="">-- Semua Tipe --</option>
                                <option value="masuk" <?php echo $tipe_filter == 'masuk' ? 'selected' : ''; ?>>Pemasukan</option>
                                <option value="keluar" <?php echo $tipe_filter == 'keluar' ? 'selected' : ''; ?>>Pengeluaran</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_awal">Tanggal Awal</label>
                            <input type="date" id="tanggal_awal" name="tanggal_awal" value="<?php echo $tanggal_awal; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_akhir">Tanggal Akhir</label>
                            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>">
                        </div>
                    </div>
                    
                    <div class="btn-group">
                        <button type="submit" class="btn">Filter</button>
                        <a href="laporan.php" class="btn" style="background-color: #95a5a6;">Reset</a>
                        <a href="print_laporan.php?tipe=<?php echo urlencode($tipe_filter); ?>&tanggal_awal=<?php echo urlencode($tanggal_awal); ?>&tanggal_akhir=<?php echo urlencode($tanggal_akhir); ?>" class="btn" style="background-color: #3498db;" target="_blank">📄 Print / Save PDF</a>
                    </div>
                </form>
                
                <!-- Ringkasan -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <div style="background-color: #d4edda; padding: 1rem; border-radius: 4px; border-left: 4px solid #28a745;">
                        <small style="color: #155724;">Total Pemasukan <?php echo ($tipe_filter || $tanggal_awal || $tanggal_akhir) ? '(Filtered)' : ''; ?></small>
                        <div class="currency" style="font-size: 1.5rem; font-weight: bold; color: #155724;">
                            Rp <?php echo number_format($total_masuk_filtered, 0, ',', '.'); ?>
                        </div>
                    </div>
                    
                    <div style="background-color: #f8d7da; padding: 1rem; border-radius: 4px; border-left: 4px solid #dc3545;">
                        <small style="color: #721c24;">Total Pengeluaran <?php echo ($tipe_filter || $tanggal_awal || $tanggal_akhir) ? '(Filtered)' : ''; ?></small>
                        <div class="currency" style="font-size: 1.5rem; font-weight: bold; color: #721c24;">
                            Rp <?php echo number_format($total_keluar_filtered, 0, ',', '.'); ?>
                        </div>
                    </div>
                    
                    <div style="background-color: #d1ecf1; padding: 1rem; border-radius: 4px; border-left: 4px solid #17a2b8;">
                        <small style="color: #0c5460;">Saldo <?php echo ($tipe_filter || $tanggal_awal || $tanggal_akhir) ? '(Filtered)' : 'Akhir'; ?></small>
                        <div class="currency" style="font-size: 1.5rem; font-weight: bold; color: #0c5460;">
                            Rp <?php echo number_format($saldo_filtered, 0, ',', '.'); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Transaksi -->
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
                                    <td>
                                        <a href="edit_transaksi.php?id=<?php echo $t['id']; ?>&action=edit" class="btn btn-warning" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;">Edit</a>
                                        <a href="edit_transaksi.php?id=<?php echo $t['id']; ?>&action=delete" class="btn btn-danger" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 2rem;">Tidak ada transaksi</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php include 'pages/footer.php'; ?>
