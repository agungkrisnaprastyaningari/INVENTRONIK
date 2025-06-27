<?php
require 'function.php';
require 'check.php';

// Check if user is admin
if (!isAdmin()) {
    header('location:index.php');
    exit();
}

// Check if user is admin
$email = $_SESSION['email'];
$check_admin = mysqli_query($conn, "SELECT role FROM user WHERE email = '$email'");
$user_role = mysqli_fetch_array($check_admin)['role'];

if ($user_role !== 'admin') {
    header('location:index.php');
    exit();
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
        <title>Log Riwayat Pemesanan</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
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
                        <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Stock Barang
                            </a>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="logout.php">
                                Logout
                            </a>


                        </div>
                    </div>
                     
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Log Riwayat Pemesanan</h1>
                        
                        
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <!-- No button for adding new items here -->
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>tanggal</th>
                                                <th>Nama Barang</th>
                                                <th>Model</th>
                                                <th>Quantity</th>
                                                <th>Total Harga</th>
                                                <th>Pemasok</th>
                                                <th>Status Pembayaran</th>
                                                <th>Invoice</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                        <?php
                                            $ambilsemuadatastock = mysqli_query($conn,"select m.*, b.namabarang, b.model, b.harga from masuk m, barang b where b.idbarang = m.idbarang AND m.status_pembayaran = 'Paid'");
                                            while($data = mysqli_fetch_array($ambilsemuadatastock)){
                                                $tanggal = $data['tanggal'];
                                                $namabarang = $data['namabarang'];
                                                $model = $data['model'];
                                                $qty = $data['qty'];
                                                $pemasok = $data['pemasok'];
                                                $total_harga = $data['total_harga'];
                                                $status_pembayaran = $data['status_pembayaran'];
                                                $nomor_invoice = $data['nomor_invoice'];
                                                $idmasuk = $data['idmasuk'];
                                            ?>

                                            <tr>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$model;?></td>
                                                <td><?=$qty;?></td>
                                                <td>Rp <?=number_format($total_harga,0,',','.');?></td>
                                                <td><?=$pemasok;?></td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        <?=$status_pembayaran;?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="generate_invoice.php?id=<?=$idmasuk;?>" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="fas fa-file-invoice"></i> Generate Invoice
                                                    </a>
                                                </td>
                                            </tr>

                                            <?php
                                            };
                                            ?>

                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2020</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
        
    </body>

</html>