<?php
/**
 * Export Laporan ke Format HTML (Bisa disave sebagai PDF via browser)
 * Akses: http://localhost/aruskas/export_laporan_html.php
 */

require_once 'config/database.php';
require_once 'config/session.php';
require_once 'class/Transaksi.php';

check_login();

// Ambil parameter
$tipe_filter = $_GET['tipe'] ?? '';
$tanggal_awal = $_GET['tanggal_awal'] ?? '';
$tanggal_akhir = $_GET['tanggal_akhir'] ?? '';

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

// Cek tipe export
$export_type = $_GET['format'] ?? 'view';

if ($export_type == 'pdf') {
    // Set header untuk download PDF
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="Laporan_Arus_Kas_' . date('Y-m-d_His') . '.pdf"');
} else {
    // Set header untuk view HTML
    header('Content-Type: text/html; charset=utf-8');
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arus Kas Masjid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
            line-height: 1.6;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #666;
            font-size: 12px;
        }
        
        .filter-info {
            background-color: #f9f9f9;
            padding: 10px 15px;
            border-left: 4px solid #667eea;
            margin-bottom: 20px;
            font-size: 12px;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        table th {
            background-color: #667eea;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #667eea;
        }
        
        table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .tipe-masuk {
            background-color: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .tipe-keluar {
            background-color: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .text-right {
            text-align: right;
        }
        
        .ringkasan {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid #667eea;
        }
        
        .ringkasan h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 14px;
        }
        
        .ringkasan-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 1px solid #ddd;
        }
        
        .ringkasan-item:last-child {
            border-bottom: none;
            font-weight: bold;
            font-size: 14px;
            padding-top: 8px;
            border-top: 2px solid #667eea;
            margin-top: 10px;
            color: #667eea;
        }
        
        .ringkasan-item label {
            color: #555;
        }
        
        .ringkasan-item value {
            color: #333;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #999;
        }
        
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
        }
        
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 0;
            }
            
            .footer {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            
            table {
                font-size: 12px;
            }
            
            table th, table td {
                padding: 8px;
            }
            
            .header h1 {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>💰 LAPORAN ARUS KAS MASJID</h1>
            <p>Tanggal Cetak: <?php echo date('d/m/Y H:i:s'); ?></p>
        </div>
        
        <!-- Filter Info -->
        <div class="filter-info">
            <?php
            $filter_text = '<strong>Filter:</strong> ';
            if ($tanggal_awal && $tanggal_akhir) {
                $filter_text .= 'Tanggal ' . date('d/m/Y', strtotime($tanggal_awal)) . ' s/d ' . date('d/m/Y', strtotime($tanggal_akhir));
            }
            if ($tipe_filter) {
                if ($tanggal_awal || $tanggal_akhir) $filter_text .= ' + ';
                $filter_text .= 'Tipe: ' . ucfirst($tipe_filter);
            }
            if (empty($tanggal_awal) && empty($tanggal_akhir) && empty($tipe_filter)) {
                $filter_text .= 'Semua Data';
            }
            echo $filter_text;
            ?>
        </div>
        
        <!-- Tabel Transaksi -->
        <?php if (count($semua_transaksi) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 12%;">Tanggal</th>
                        <th style="width: 45%;">Keterangan</th>
                        <th style="width: 12%;">Tipe</th>
                        <th style="width: 26%; text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($semua_transaksi as $t): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($t['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($t['keterangan']); ?></td>
                            <td>
                                <span class="tipe-<?php echo $t['tipe']; ?>">
                                    <?php echo ucfirst($t['tipe']); ?>
                                </span>
                            </td>
                            <td class="text-right">
                                Rp <?php echo number_format($t['jumlah'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p>Tidak ada data transaksi</p>
            </div>
        <?php endif; ?>
        
        <!-- Ringkasan -->
        <div class="ringkasan">
            <h3>📊 RINGKASAN</h3>
            <div class="ringkasan-item">
                <label>Total Pemasukan:</label>
                <value>Rp <?php echo number_format($total_masuk_filtered, 0, ',', '.'); ?></value>
            </div>
            <div class="ringkasan-item">
                <label>Total Pengeluaran:</label>
                <value>Rp <?php echo number_format($total_keluar_filtered, 0, ',', '.'); ?></value>
            </div>
            <div class="ringkasan-item">
                <label>SALDO:</label>
                <value>Rp <?php echo number_format($saldo_filtered, 0, ',', '.'); ?></value>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Laporan ini dihasilkan oleh Sistem Arus Kas Masjid</p>
            <p>Cetak sebagai PDF: Tekan Ctrl+P (atau Cmd+P di Mac) → Save as PDF</p>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
