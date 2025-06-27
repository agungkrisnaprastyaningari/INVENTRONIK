<?php
require 'function.php';
require 'check.php';

// Check if user is admin
if (!isAdmin()) {
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
        <title>Barang Keluar</title>
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
                        <h1 class="mt-4">Barang Keluar</h1>
                        
                        
                        
                         <div class="card mb-4">
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>tanggal</th>
                                                <th>Nama Barang</th>
                                                <th>model</th>
                                                <th>Quantity</th>
                                                <th>penerima</th>
                                                <th>harga jual </th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody>
                                            

                                        <?php
                                            $ambilsemuadatastock = mysqli_query($conn,"select * from keluar,barang b where b.idbarang = keluar.idbarang");
                                            while($data = mysqli_fetch_array($ambilsemuadatastock)){
                                                $tanggal = $data['tanggal'];
                                                $namabarang = $data['namabarang'];
                                                $model = $data['model'];
                                                $qty = $data['qty'];
                                                $penerima = $data['penerima'];
                                                $harga_jual = $data['harga'];

                                            ?>

                                            <tr>
                                                <td><?=$tanggal;?></td>
                                                <td><?=$namabarang;?></td>
                                                <td><?=$model;?></td>
                                                <td><?=$qty;?></td>
                                                <td><?=$penerima;?></td>
                                                <td><?=$harga_jual;?></td>
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
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
        <div class="modal-content">
        
            
            <div class="modal-header">
            <h4 class="modal-title">Tambah Barang Keluar</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <form method="post">
            <div class="modal-body">
            <select name="namabarang" class="form-control"required>
            <option value="">-- Pilih Barang --</option>
            <?php
            $ambilsemuadata = mysqli_query($conn,"SELECT * FROM barang");
            while($fetcharray = mysqli_fetch_array($ambilsemuadata)){
                $namabarang = $fetcharray['namabarang'];
                $idbarang = $fetcharray['idbarang'];
            ?>
                <option value="<?=$idbarang;?>"><?=$namabarang;?></option>
            <?php
            }
            ?>
            </select>
            <select name = "model" class="form-control"required>    
            <option value="">-- Pilih Model --</option>
            <?php
            $ambilsemuadata = mysqli_query($conn,"SELECT * FROM barang");
            while($fetcharray = mysqli_fetch_array($ambilsemuadata)){
                $model = $fetcharray['model'];
                $idbarang = $fetcharray['idbarang'];
            ?>
                <option value="<?=$idbarang;?>"><?=$model;?></option>
            <?php
            }
            ?>
            </select>
            <br>  
            <input type="text" name="penerima" placeholder="penerima Barang" class="form-control"required>
            <br>
            <input type="number" name="qty" placeholder="Quantity Barang" class="form-control"required>
            <br>
            <button type="submit" class="btn btn-primary" name="barangkeluar">Submit</button>
            </form> 
            </div>
            
            
           
        </div>
        </div>
    </div>
</html>
