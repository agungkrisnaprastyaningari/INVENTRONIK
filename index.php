<?php
require 'function.php';
require 'check.php';

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Stock barang</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">INVENTRONIK</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <div class="ml-auto text-white">
                Welcome, <?=$user_name;?> (<?=$user_role;?>)
            </div>
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
                            <?php if($user_role === 'admin'): ?>
                            <a class="nav-link" href="masuk.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Masuk
                            </a>
                            <a class="nav-link" href="keluar.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Barang Keluar
                            </a>
                            <a class="nav-link" href="admin.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Kelola User
                            </a>
                            <?php endif; ?>
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
                        <h1 class="mt-4">Stock Barang</h1>
                        <div class="card mb-4">
                            <?php if($user_role === 'admin'): ?>
                            <div class="card-header">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                    Tambah Barang
                                </button>
                                <a href="export.php" class="btn btn-success">Export</a>
                            </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <?php
                                $cekstock = mysqli_query($conn,"select * from barang where stock < 1");
                                while($fetch = mysqli_fetch_array($cekstock)){
                                $barang = $fetch['namabarang'];
                                
                                ?>
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                     <strong>perhatian!</strong> Stok Barang <?=$barang;?> ini telah habis!!
                                    </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Barang</th>
                                                    <th>merek</th>
                                                    <th>model</th>
                                                    <th>stock</th>
                                                    <th>harga</th>
                                                    <?php if($user_role === 'admin'): ?>
                                                    <th>aksi</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            
                                            <tbody>
                                                <?php
                                                $ambilsemuadatastock = mysqli_query($conn,"select * from barang");
                                                $i = 1;
                                                while($data = mysqli_fetch_array($ambilsemuadatastock)){ 
                                                    $namabarang = $data['namabarang'];
                                                    $merek = $data['merek'];
                                                    $model = $data['model'];
                                                    $stock = $data['stock'];
                                                    $harga = $data['harga'];
                                                    $idbarang = $data['idbarang'];


                                                ?>

                                                <tr>
                                                    <td><?=$i++;?></td>
                                                    <td><?=$namabarang;?></td>
                                                    <td><?=$merek;?></td>
                                                    <td><?=$model;?></td>
                                                    <td><?=$stock;?></td>
                                                    <td><?=$harga;?></td>
                                                    <td> 
                                                    <?php if($user_role === 'admin'): ?>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit<?=$idbarang;?>">
                                                        Edit
                                                    </button>
                                                    <input type="hidden" name="idbarangygmaudihapus" value="<?=$idbarang;?>">
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete<?=$idbarang;?>">
                                                        delete
                                                    </button>
                                                    <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <!-- edit -->
                                                <div class="modal fade" id="edit<?=$idbarang;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <form method="post">
                                                        <div class="modal-body">
                                                        <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                                                        <input type="text" name="namabarang" value="<?=$namabarang;?>" class="form-control" required>
                                                        <br>
                                                        <input type="text" name="merek" value="<?=$merek;?>" class="form-control" required>
                                                        <br>
                                                        <input type="text" name="model" value="<?=$model;?>" class="form-control" required>
                                                        <br>
                                                        <input type="number" name="harga" value="<?=$harga;?>" class="form-control" required>
                                                        <br>  
                                                        <button type="submit" class="btn btn-primary" name="editbarang">Edit</button>
                                                        </form> 
                                                        </div>
                                                        
                                                        
                                                    
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- delete -->
                                                 <div class="modal fade" id="delete<?=$idbarang;?>">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    
                                                        
                                                        <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        
                                                        <form method="post">
                                                        <div class="modal-body">
                                                        Apakah anda yakin ingin menghapus barang <?=$model;?>?
                                                        <br><br>
                                                        <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                                                        <button type="submit" class="btn btn-primary" name="hapusbarang">Hapus</button>
                                                        </form> 
                                                        </div>
                                                        
                                                        
                                                    
                                                    </div>
                                                    </div>
                                                </div>

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
                            <div class="text-muted">Copyright &copy; Inventronik 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <?php if($user_role === 'admin'): ?>
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
            <div class="modal-content">
            
                
                <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Masuk</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <form method="post">
                <div class="modal-body">
                <input type = "text" name="namabarang" placeholder="Nama Barang" class="form-control"required>
                <br>
                <input type = "text" name="merek" placeholder="Merek Barang" class="form-control"required>
                <br>
                <input type = "text" name="model" placeholder="Model Barang" class="form-control"required>
                <br>
                <input type="number" name="stock" placeholder="Stock Barang" class="form-control"required>
                <br>
                <input type="number" name="harga" placeholder="Harga Barang" class="form-control"required>
                <br>  
                <button type="submit" class="btn btn-primary" name="Addnewbarang">Submit</button>
                </form> 
                </div>
                
                
               
            </div>
            </div>
        </div>
        <?php endif; ?>

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
