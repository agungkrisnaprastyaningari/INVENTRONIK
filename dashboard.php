<?php
require 'function.php';
require 'check.php';

// Check if user is admin
if (!isAdmin()) {
    header('location:index.php');
    exit();
}

// Hitung total omset dari penjualan (barang keluar)
$query_penjualan = mysqli_query($conn, "SELECT SUM(k.qty * b.harga) as total_penjualan 
                                      FROM keluar k 
                                      JOIN barang b ON k.idbarang = b.idbarang");
$data_penjualan = mysqli_fetch_array($query_penjualan);
$total_penjualan = $data_penjualan['total_penjualan'] ?? 0;

// Hitung total pembelian (barang masuk)
$query_pembelian = mysqli_query($conn, "SELECT SUM(m.qty * b.harga) as total_pembelian 
                                      FROM masuk m 
                                      JOIN barang b ON m.idbarang = b.idbarang");
$data_pembelian = mysqli_fetch_array($query_pembelian);
$total_pembelian = $data_pembelian['total_pembelian'] ?? 0;

// Data untuk grafik penjualan per bulan
$query_grafik = mysqli_query($conn, "SELECT 
    DATE_FORMAT(k.tanggal, '%Y-%m') as bulan,
    SUM(k.qty) as total_qty,
    SUM(k.qty * b.harga) as total_harga
    FROM keluar k
    JOIN barang b ON k.idbarang = b.idbarang
    GROUP BY DATE_FORMAT(k.tanggal, '%Y-%m')
    ORDER BY bulan DESC
    LIMIT 12");

$labels = [];
$data_qty = [];
$data_harga = [];

while($row = mysqli_fetch_array($query_grafik)) {
    $labels[] = $row['bulan'];
    $data_qty[] = $row['total_qty'];
    $data_harga[] = $row['total_harga'];
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - CENTRAL ELEKTRONIK</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">CENTRAL ELEKTRONIK</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-download"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-upload"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Kelola User
                            </a>
                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Dashboard</h1>
                        <div class="row">
                            <div class="col-xl-6 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">
                                        <h4>Total Penjualan</h4>
                                        <h2>Rp <?= number_format($total_penjualan, 0, ',', '.') ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">
                                        <h4>Total Pembelian</h4>
                                        <h2>Rp <?= number_format($total_pembelian, 0, ',', '.') ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Grafik Penjualan 12 Bulan Terakhir
                            </div>
                            <div class="card-body">
                                <canvas id="myChart" width="100%" height="40"></canvas>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; CENTRAL ELEKTRONIK 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script>
        // Setup grafik
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_reverse($labels)) ?>,
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: <?= json_encode(array_reverse($data_harga)) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value, index, values) {
                                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        }
                    }
                }
            }
        });
        </script>
    </body>
</html> 