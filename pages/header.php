<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' : ''; ?>Sistem Arus Kas Masjid</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        
        .navbar {
            background-color: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .navbar > div {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .navbar h1 {
            font-size: 1.5rem;
            margin: 0;
            white-space: nowrap;
        }
        
        .navbar p {
            margin: 0;
            font-size: 0.95rem;
            color: #bdc3c7;
            font-weight: 500;
        }
        
        .navbar-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .navbar-right a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .navbar-right a:hover {
            background-color: rgba(255,255,255,0.1);
        }
        
        .navbar-right .logout {
            background-color: #e74c3c;
        }
        
        .navbar-right .logout:hover {
            background-color: #c0392b;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        .sidebar {
            position: fixed;
            left: 0;
            top: 60px;
            width: 250px;
            height: calc(100vh - 60px);
            background-color: #ecf0f1;
            padding: 1rem 0;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar a {
            display: block;
            padding: 1rem 1.5rem;
            color: #2c3e50;
            text-decoration: none;
            border-left: 4px solid transparent;
            transition: all 0.3s;
        }
        
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #bdc3c7;
            border-left-color: #3498db;
            color: #fff;
        }
        
        .main-content {
            margin-left: 250px;
            padding-top: 2rem;
        }
        
        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .card h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 0.5rem;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-box.masuk {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stat-box.keluar {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        .stat-box.saldo {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }
        
        .stat-box h3 {
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            opacity: 0.9;
        }
        
        .stat-box .value {
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: #e74c3c;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .btn-success {
            background-color: #27ae60;
        }
        
        .btn-success:hover {
            background-color: #229954;
        }
        
        .btn-warning {
            background-color: #f39c12;
        }
        
        .btn-warning:hover {
            background-color: #d68910;
        }
        
        .btn-group {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        table th {
            background-color: #34495e;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        table td {
            padding: 1rem;
            border-bottom: 1px solid #ecf0f1;
        }
        
        table tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge.masuk {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge.keluar {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar {
                flex-direction: column;
                gap: 1rem;
            }
            
            .stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php
    // Load settings
    if (!isset($conn)) {
        require_once dirname(__DIR__) . '/config/database.php';
    }
    if (!class_exists('Settings')) {
        require_once dirname(__DIR__) . '/class/Settings.php';
    }
    $settings_obj = new Settings($conn);
    $nama_masjid = $settings_obj->get_setting('nama_masjid') ?? 'Masjid Anda';
    ?>
    
    <div class="navbar">
        <div>
            <h1>💰 Arus Kas Masjid</h1>
            <p><?php echo htmlspecialchars($nama_masjid); ?></p>
        </div>
        <div class="navbar-right">
            <span>Halo, <?php echo $_SESSION['username']; ?></span>
            <a href="pengaturan_masjid.php" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem;">⚙️ Pengaturan</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
    </div>
    
    <div class="sidebar">
        <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">📊 Dashboard</a>
        <a href="tambah_transaksi.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tambah_transaksi.php' ? 'active' : ''; ?>">➕ Tambah Transaksi</a>
        <a href="laporan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>">📋 Laporan</a>
        <a href="edit_transaksi.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'edit_transaksi.php' ? 'active' : ''; ?>">✏️ Kelola Transaksi</a>
        <a href="daftar_user.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'daftar_user.php' ? 'active' : ''; ?>">👥 Kelola User</a>
    </div>
</body>
</html>
