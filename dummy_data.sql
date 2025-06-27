-- Menambah data dummy barang
INSERT INTO barang (namabarang, merek, model, stock, harga) VALUES
('Smartphone', 'Samsung', 'S21', 50, 10000000),
('Laptop', 'Lenovo', 'ThinkPad', 30, 15000000),
('Tablet', 'Apple', 'iPad Pro', 25, 12000000),
('Smart TV', 'LG', 'OLED 55', 15, 8000000);

-- Data dummy barang keluar (penjualan) untuk 12 bulan terakhir
INSERT INTO keluar (idbarang, penerima, qty, tanggal) VALUES
-- Samsung S21
(1, 'Customer A', 5, '2024-01-15'),
(1, 'Customer B', 3, '2023-12-20'),
(1, 'Customer C', 4, '2023-11-10'),
(1, 'Customer D', 6, '2023-10-05'),
(1, 'Customer E', 2, '2023-09-15'),
(1, 'Customer F', 3, '2023-08-20'),

-- Lenovo ThinkPad
(2, 'Company X', 3, '2024-01-10'),
(2, 'Company Y', 2, '2023-12-15'),
(2, 'Company Z', 4, '2023-11-20'),
(2, 'Customer G', 1, '2023-10-25'),
(2, 'Customer H', 2, '2023-09-30'),
(2, 'Customer I', 3, '2023-08-05'),

-- iPad Pro
(3, 'Store A', 4, '2024-01-20'),
(3, 'Store B', 3, '2023-12-25'),
(3, 'Store C', 2, '2023-11-30'),
(3, 'Customer J', 5, '2023-10-15'),
(3, 'Customer K', 3, '2023-09-20'),
(3, 'Customer L', 2, '2023-08-25'),

-- LG OLED TV
(4, 'Electronics Store', 2, '2024-01-05'),
(4, 'Mall Store', 3, '2023-12-10'),
(4, 'Online Store', 1, '2023-11-15'),
(4, 'Customer M', 2, '2023-10-20'),
(4, 'Customer N', 1, '2023-09-25'),
(4, 'Customer O', 2, '2023-08-30');

-- Data dummy barang masuk (pembelian stok) dengan total harga
INSERT INTO masuk (idbarang, pemasok, qty, total_harga, status_pembayaran, metode_pembayaran, nomor_invoice, tanggal) VALUES
-- Samsung S21
(1, 'Samsung Distribution', 10, 100000000, 'Paid', 'Bank Transfer', 'INV/202401/0001', '2024-01-01'),
(1, 'Electronics Supplier', 15, 150000000, 'Paid', 'Bank Transfer', 'INV/202312/0001', '2023-12-01'),
(1, 'Global Tech', 10, 100000000, 'Paid', 'Bank Transfer', 'INV/202311/0001', '2023-11-01'),

-- Lenovo ThinkPad
(2, 'Lenovo Official', 8, 120000000, 'Paid', 'Bank Transfer', 'INV/202401/0002', '2024-01-05'),
(2, 'Computer Wholesale', 12, 180000000, 'Paid', 'Bank Transfer', 'INV/202312/0002', '2023-12-05'),
(2, 'Tech Distributor', 10, 150000000, 'Paid', 'Bank Transfer', 'INV/202311/0002', '2023-11-05'),

-- iPad Pro
(3, 'Apple Distributor', 10, 120000000, 'Paid', 'Bank Transfer', 'INV/202401/0003', '2024-01-10'),
(3, 'iStore Supply', 8, 96000000, 'Paid', 'Bank Transfer', 'INV/202312/0003', '2023-12-10'),
(3, 'Gadget Wholesale', 7, 84000000, 'Paid', 'Bank Transfer', 'INV/202311/0003', '2023-11-10'),

-- LG OLED TV
(4, 'LG Electronics', 5, 40000000, 'Paid', 'Bank Transfer', 'INV/202401/0004', '2024-01-15'),
(4, 'Electronics Wholesale', 6, 48000000, 'Paid', 'Bank Transfer', 'INV/202312/0004', '2023-12-15'),
(4, 'TV Distributor', 4, 32000000, 'Paid', 'Bank Transfer', 'INV/202311/0004', '2023-11-15'); 