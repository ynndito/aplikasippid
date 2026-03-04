-- Database: db_ppid
-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS db_ppid CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE db_ppid;

-- Tabel dokumen
CREATE TABLE IF NOT EXISTS dokumen (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    tahun INT NOT NULL,
    tanggal DATE NOT NULL,
    nama_file VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel admin
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin (username: admin, password: admin)
INSERT INTO admin (username, password) VALUES ('admin', 'admin');

-- Tabel permohonan
CREATE TABLE IF NOT EXISTS permohonan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telepon VARCHAR(50) NOT NULL,
    informasi TEXT NOT NULL,
    cara_penerimaan VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending' NOT NULL,
    catatan_admin TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

