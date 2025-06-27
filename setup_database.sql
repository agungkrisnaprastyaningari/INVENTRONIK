-- Create database if not exists
CREATE DATABASE IF NOT EXISTS inven;
USE inven;

-- Drop existing tables if they exist
DROP TABLE IF EXISTS keluar;
DROP TABLE IF EXISTS masuk;
DROP TABLE IF EXISTS barang;
DROP TABLE IF EXISTS user;

-- Create user table
CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'employee') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create barang table
CREATE TABLE barang (
    idbarang INT AUTO_INCREMENT PRIMARY KEY,
    namabarang VARCHAR(100) NOT NULL,
    merek VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    harga DECIMAL(15,2) NOT NULL,
    status ENUM('aktif', 'dihapus') NOT NULL DEFAULT 'aktif'
);

-- Create masuk table (barang masuk)
CREATE TABLE masuk (
    idmasuk INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    pemasok VARCHAR(100) NOT NULL,
    qty INT NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    status_pembayaran ENUM('Pending', 'Paid') DEFAULT 'Pending',
    metode_pembayaran VARCHAR(50),
    nomor_invoice VARCHAR(50) UNIQUE,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE RESTRICT
);

-- Create keluar table (barang keluar)
CREATE TABLE keluar (
    idkeluar INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    penerima VARCHAR(100) NOT NULL,
    qty INT NOT NULL,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang) ON DELETE RESTRICT
);

-- Insert default admin account
INSERT INTO user (email, password, name, role) VALUES
('admin@admin.com', 'admin123', 'Administrator', 'admin');

-- Insert sample data for barang
INSERT INTO barang (namabarang, merek, model, stock, harga, status) VALUES
('Laptop', 'Lenovo', 'ThinkPad X1', 10, 15000000, 'aktif'),
('Monitor', 'Samsung', '24 inch LED', 15, 2500000, 'aktif'),
('Keyboard', 'Logitech', 'K380', 20, 450000, 'aktif');

-- Insert sample data for barang masuk
INSERT INTO masuk (idbarang, pemasok, qty, total_harga, status_pembayaran, metode_pembayaran, nomor_invoice) VALUES
(1, 'PT Supplier Elektronik', 5, 75000000, 'Paid', 'Bank Transfer', 'INV/202401/0001'),
(2, 'PT Supplier Elektronik', 10, 25000000, 'Paid', 'Bank Transfer', 'INV/202401/0002'),
(3, 'PT Supplier Elektronik', 15, 6750000, 'Pending', 'Credit Card', 'INV/202401/0003');

-- Insert sample data for barang keluar
INSERT INTO keluar (idbarang, penerima, qty) VALUES
(1, 'PT Pembeli A', 2),
(2, 'PT Pembeli B', 3),
(3, 'PT Pembeli C', 5); 