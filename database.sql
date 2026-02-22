-- Buat Database
CREATE DATABASE IF NOT EXISTS aruskas;
USE aruskas;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    email VARCHAR(100),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Transaksi
CREATE TABLE transaksi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal DATE NOT NULL,
    keterangan VARCHAR(255) NOT NULL,
    tipe ENUM('masuk','keluar') NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tanggal (tanggal),
    INDEX idx_tipe (tipe)
);

-- Tabel Settings
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value) VALUES 
('nama_masjid', 'Masjid Anda'),
('alamat_masjid', 'Jalan Jalan, Kota, Negara');

-- Insert user default (username: admin, password: admin123)
-- Password hash dibuat dengan: password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO users (username, password, nama_lengkap, email) VALUES 
('admin', '$2y$10$sDzXF4QZDy0p1SlEjf5cSOIJXvDk3LljR4VvzvLPqZOWrUlgXabNu', 'Administrator', 'admin@masjid.com');
