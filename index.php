<?php
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

$page_title = 'Dashboard';

$transaksi = new Transaksi($conn);
$total_masuk = $transaksi->total_masuk();
$total_keluar = $transaksi->total_keluar();
$saldo = $transaksi->saldo();
$semua_transaksi = $transaksi->get_all('DESC');

// Ambil 5 transaksi terakhir
$transaksi_terbaru = array_slice($semua_transaksi, 0, 5);
?>

<?php include 'pages/header.php'; ?>

    <div class="main-content">
        <div class="container">
            <h2>Dashboard</h2>
            
            <!-- Statistik -->
            <div class="stats">
                <div class="stat-box masuk">
                    <h3>Total Pemasukan</h3>
                    <div class="value">Rp <?php echo number_format($total_masuk, 0, ',', '.'); ?></div>
                </div>
                
                <div class="stat-box keluar">
                    <h3>Total Pengeluaran</h3>
                    <div class="value">Rp <?php echo number_format($total_keluar, 0, ',', '.'); ?></div>
                </div>
                
                <div class="stat-box saldo">
                    <h3>Saldo Akhir</h3>
                    <div class="value">Rp <?php echo number_format($saldo, 0, ',', '.'); ?></div>
                </div>
            </div>
            
            <!-- Transaksi Terbaru -->
            <div class="card">
                <h2>5 Transaksi Terbaru</h2>
                
                <?php if (count($transaksi_terbaru) > 0): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transaksi_terbaru as $t): ?>
                                <tr>
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
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 2rem;">Belum ada transaksi</p>
                <?php endif; ?>
                
                <div style="text-align: center; margin-top: 1rem;">
                    <a href="laporan.php" class="btn">Lihat Semua Transaksi</a>
                </div>
            </div>
        </div>
    </div>

<?php include 'pages/footer.php'; ?>
