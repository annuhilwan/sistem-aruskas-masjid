<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Publik - Saldo Kas Masjid</title>
    <?php
    require_once 'config/database.php';
    require_once 'class/Settings.php';
    
    $settings = new Settings($conn);
    $nama_masjid = $settings->get_setting('nama_masjid') ?? 'Masjid Anda';
    $alamat_masjid = $settings->get_setting('alamat_masjid') ?? 'Alamat Masjid';
    ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            max-width: 600px;
            width: 100%;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            text-align: center;
        }
        
        .header {
            margin-bottom: 40px;
        }
        
        .masjid-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .header p {
            color: #999;
            font-size: 14px;
        }
        
        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            margin: 20px auto;
            border-radius: 2px;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .label {
            color: #999;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .saldo-display {
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            margin-bottom: 30px;
            word-break: break-word;
        }
        
        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 40px 0;
        }
        
        .stat-box {
            padding: 20px;
            border-radius: 8px;
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
        }
        
        .stat-box.masuk {
            border-left-color: #28a745;
            background-color: #f0fdf4;
        }
        
        .stat-box.keluar {
            border-left-color: #dc3545;
            background-color: #fef2f2;
        }
        
        .stat-box.saldo {
            border-left-color: #667eea;
            background-color: #f0f3ff;
            grid-column: 1 / -1;
        }
        
        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #333;
            word-break: break-word;
        }
        
        .stat-box.masuk .stat-value {
            color: #28a745;
        }
        
        .stat-box.keluar .stat-value {
            color: #dc3545;
        }
        
        .stat-box.saldo .stat-value {
            color: #667eea;
            font-size: 24px;
        }
        
        .update-info {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #999;
            font-size: 12px;
        }
        
        .update-info strong {
            color: #667eea;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #ccc;
        }
        
        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #28a745;
            margin-right: 6px;
            vertical-align: middle;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.4);
            }
            70% {
                box-shadow: 0 0 0 8px rgba(40, 167, 69, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }
        
        .badge-status {
            display: inline-block;
            padding: 4px 12px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .info-box {
            background-color: #e8f4f8;
            border: 1px solid #b8e0f0;
            border-radius: 6px;
            padding: 12px;
            margin: 20px 0;
            font-size: 12px;
            color: #0c5460;
            line-height: 1.6;
        }
        
        .info-box strong {
            color: #0a3d4d;
        }
        
        @media (max-width: 600px) {
            .card {
                padding: 30px 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
            
            .saldo-display {
                font-size: 28px;
            }
            
            .stat-value {
                font-size: 18px;
            }
            
            .stats {
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <!-- Header -->
            <div class="header">
                <span class="masjid-icon">🕌</span>
                <h1><?php echo htmlspecialchars($nama_masjid); ?></h1>
                <p><?php echo htmlspecialchars($alamat_masjid); ?></p>
            </div>
            
            <div class="divider"></div>
            
            <!-- Status -->
            <div class="badge-status">
                <span class="status-indicator"></span>
                Data Real-time
            </div>
            
            <!-- Content -->
            <div class="content">
                <?php
                // Database connection
                require_once 'class/Transaksi.php';
                
                try {
                    $transaksi = new Transaksi($conn);
                    
                    $total_masuk = $transaksi->total_masuk();
                    $total_keluar = $transaksi->total_keluar();
                    $saldo = $transaksi->saldo();
                    
                    // Get last update
                    $sql = "SELECT MAX(updated_at) as last_update FROM transaksi";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $last_update = $row['last_update'] ? date('d/m/Y H:i:s', strtotime($row['last_update'])) : 'Belum ada data';
                    
                    // Check if data available
                    $sql_count = "SELECT COUNT(*) as total FROM transaksi";
                    $result_count = $conn->query($sql_count);
                    $row_count = $result_count->fetch_assoc();
                    $transaction_count = $row_count['total'];
                    
                ?>
                
                <!-- Main Saldo Display -->
                <div class="label">Saldo Akhir Kas</div>
                <div class="saldo-display">
                    Rp <?php echo number_format($saldo, 0, ',', '.'); ?>
                </div>
                
                <!-- Detailed Stats -->
                <div class="stats">
                    <div class="stat-box masuk">
                        <div class="stat-label">📥 Total Pemasukan</div>
                        <div class="stat-value">Rp <?php echo number_format($total_masuk, 0, ',', '.'); ?></div>
                    </div>
                    
                    <div class="stat-box keluar">
                        <div class="stat-label">📤 Total Pengeluaran</div>
                        <div class="stat-value">Rp <?php echo number_format($total_keluar, 0, ',', '.'); ?></div>
                    </div>
                    
                    <div class="stat-box saldo">
                        <div class="stat-label">💰 Perhitungan Saldo</div>
                        <div class="stat-value">
                            Rp <?php echo number_format($total_masuk, 0, ',', '.'); ?> 
                            <br>
                            <span style="font-size: 14px; color: #999;">−</span>
                            <br>
                            Rp <?php echo number_format($total_keluar, 0, ',', '.'); ?>
                            <br>
                            <span style="font-size: 14px; color: #999;">= Rp <?php echo number_format($saldo, 0, ',', '.'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Info Box -->
                <div class="info-box">
                    <strong>📊 Informasi Data:</strong><br>
                    • Total Transaksi: <strong><?php echo $transaction_count; ?> record</strong><br>
                    • Update Terakhir: <strong><?php echo $last_update; ?></strong>
                </div>
                
                <?php
                } catch (Exception $e) {
                    echo '<div class="info-box" style="background-color: #ffe8e8; border-color: #ffb3b3; color: #721c24;">';
                    echo '<strong>⚠️ Error:</strong> Tidak dapat memuat data. Silahkan hubungi administrator.';
                    echo '</div>';
                }
                ?>
                
                <!-- Update Info -->
                <div class="update-info">
                    <span class="status-indicator"></span>
                    <strong>Data diperbarui otomatis</strong>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p>Dashboard Publik - Informasi untuk Jemaah</p>
                <p style="margin-top: 8px;">© 2026 <a href="https://annuhliwan.my.id" target="_blank" style="color: #667eea; text-decoration: none;">Annuh Liwan Nahar</a>. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
