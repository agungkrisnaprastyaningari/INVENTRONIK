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
    stock INT NOT NULL,
    harga DECIMAL(10,2) NOT NULL
);

-- Create masuk table
CREATE TABLE masuk (
    idmasuk INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT NOT NULL,
    pemasok VARCHAR(100) NOT NULL,
    qty INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

-- Create keluar table
CREATE TABLE keluar (
    idkeluar INT AUTO_INCREMENT PRIMARY KEY,
    idbarang INT NOT NULL,
    penerima VARCHAR(100) NOT NULL,
    qty INT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idbarang) REFERENCES barang(idbarang)
);

-- Insert default admin account
INSERT INTO user (email, password, name, role) VALUES
('admin@admin.com', 'admin123', 'Administrator', 'admin'); 