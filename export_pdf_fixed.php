<?php
/**
 * EXPORT LAPORAN PDF - VERSI FIXED
 * File yang lebih robust untuk export laporan ke format yang bisa dibuka
 */

// Disable output buffering
@ob_end_clean();

// Set headers sebelum apapun
require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

// Ambil parameter
$tipe_filter = isset($_GET['tipe']) ? $_GET['tipe'] : '';
$tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

try {
    $transaksi = new Transaksi($conn);
    
    // Filter data
    $semua_transaksi = [];
    
    if ($tanggal_awal && $tanggal_akhir) {
        $semua_transaksi = $transaksi->get_by_date($tanggal_awal, $tanggal_akhir);
        
        if ($tipe_filter) {
            $semua_transaksi = array_filter($semua_transaksi, function($t) use ($tipe_filter) {
                return $t['tipe'] === $tipe_filter;
            });
        }
    } elseif ($tipe_filter) {
        $semua_transaksi = $transaksi->get_by_type($tipe_filter);
    } else {
        $semua_transaksi = $transaksi->get_all('DESC');
    }
    
    // Hitung total
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
    
    // Set content type untuk PDF
    header('Content-Type: application/pdf; charset=utf-8');
    header('Content-Disposition: attachment; filename="Laporan_Arus_Kas_' . date('Y-m-d_His') . '.pdf"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Mulai output HTML ke PDF
    ob_start();
    ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Arus Kas Masjid</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 20px;
        }
        
        .header p {
            margin: 5px 0;
            font-size: 12px;
            color: #666;
        }
        
        .filter-info {
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        
        table th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #667eea;
        }
        
        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .ringkasan {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin-top: 20px;
        }
        
        .ringkasan h3 {
            margin: 0 0 15px 0;
            font-size: 14px;
        }
        
        .ringkasan-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
            font-size: 12px;
        }
        
        .ringkasan-item.total {
            border-top: 2px solid #667eea;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            color: #667eea;
            border-bottom: none;
        }
        
        .text-right {
            text-align: right;
        }
        
        .tipe-masuk {
            color: #155724;
            font-weight: bold;
        }
        
        .tipe-keluar {
            color: #721c24;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ARUS KAS MASJID</h1>
        <p>Tanggal Cetak: <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>
    
    <div class="filter-info">
        <strong>Filter:</strong>
        <?php
        if ($tanggal_awal && $tanggal_akhir) {
            echo 'Tanggal ' . date('d/m/Y', strtotime($tanggal_awal)) . ' s/d ' . date('d/m/Y', strtotime($tanggal_akhir));
        }
        if ($tipe_filter) {
            if ($tanggal_awal || $tanggal_akhir) echo ' + ';
            echo 'Tipe: ' . ucfirst($tipe_filter);
        }
        if (empty($tanggal_awal) && empty($tanggal_akhir) && empty($tipe_filter)) {
            echo 'Semua Data';
        }
        ?>
    </div>
    
    <?php if (count($semua_transaksi) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 43%;">Keterangan</th>
                    <th style="width: 15%;">Tipe</th>
                    <th style="width: 25%; text-align: right;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($semua_transaksi as $t): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($t['tanggal'])); ?></td>
                        <td><?php echo htmlspecialchars($t['keterangan']); ?></td>
                        <td class="tipe-<?php echo $t['tipe']; ?>">
                            <?php echo ucfirst($t['tipe']); ?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($t['jumlah'], 0, ',', '.'); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: #999;">Tidak ada data transaksi</p>
    <?php endif; ?>
    
    <div class="ringkasan">
        <h3>RINGKASAN</h3>
        <div class="ringkasan-item">
            <span>Total Pemasukan:</span>
            <span><?php echo number_format($total_masuk_filtered, 0, ',', '.'); ?></span>
        </div>
        <div class="ringkasan-item">
            <span>Total Pengeluaran:</span>
            <span><?php echo number_format($total_keluar_filtered, 0, ',', '.'); ?></span>
        </div>
        <div class="ringkasan-item total">
            <span>SALDO:</span>
            <span><?php echo number_format($saldo_filtered, 0, ',', '.'); ?></span>
        </div>
    </div>
</body>
</html>
    <?php
    
    $html_content = ob_get_clean();
    
    // Simple HTML to PDF conversion menggunakan browser print
    // Ini akan output HTML yang bisa dibuka di browser dan di-print ke PDF
    echo $html_content;
    
    $conn->close();
    exit;

} catch (Exception $e) {
    header('Content-Type: text/html; charset=utf-8');
    echo '<h2>Error Export PDF</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
    exit;
}
?>
