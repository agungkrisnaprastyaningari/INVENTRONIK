<?php
require 'function.php';
require 'check.php';

$email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT id, role FROM user WHERE email = '$email'");
$user_data = mysqli_fetch_array($user_query);
$user_id = $user_data['id'];
$user_role = $user_data['role'];

if ($user_role !== 'employee' && $user_role !== 'admin') {
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
        <title>Gaji</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.php">CENTRAL ELEKTRONIK</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <div class="ml-auto text-white">
                Welcome, <?=$_SESSION['name'];?> (<?=$_SESSION['role'];?>)
            </div>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Stock Barang
                            </a>
                            <?php if($user_role === 'admin'): ?>
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
                                Kelola Karyawan
                            </a>
                            <a class="nav-link" href="salary.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                                Kelola Gaji
                            </a>
                            <?php endif; ?>
                            <?php if($user_role === 'employee'): ?>
                            <a class="nav-link" href="attendance.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                                Absensi
                            </a>
                            <a class="nav-link" href="salary.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                                Gaji
                            </a>
                            <?php endif; ?>
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
                        <h1 class="mt-4">Informasi Gaji</h1>
                        <?php if($user_role === 'admin'): ?>
                        <div class="mb-4">
                            <a href="add_salary.php" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Gaji
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <?php if($user_role === 'admin'): ?>
                                                <th>Nama Karyawan</th>
                                                <?php endif; ?>
                                                <th>Periode</th>
                                                <th>Gaji Pokok</th>
                                                <th>Tunjangan</th>
                                                <th>Potongan</th>
                                                <th>Total Gaji</th>
                                                <th>Tanggal Pembayaran</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($user_role === 'admin') {
                                                $ambilsemuagaji = mysqli_query($conn, "SELECT s.*, u.name as employee_name 
                                                                                     FROM salary s 
                                                                                     JOIN user u ON s.user_id = u.id 
                                                                                     ORDER BY s.year DESC, s.month DESC");
                                            } else {
                                                $ambilsemuagaji = mysqli_query($conn, "SELECT * FROM salary 
                                                                                     WHERE user_id='$user_id' 
                                                                                     ORDER BY year DESC, month DESC");
                                            }
                                            while($data = mysqli_fetch_array($ambilsemuagaji)){
                                                $period = date('F Y', strtotime($data['year'].'-'.$data['month'].'-01'));
                                            ?>
                                            <tr>
                                                <?php if($user_role === 'admin'): ?>
                                                <td><?=$data['employee_name'];?></td>
                                                <?php endif; ?>
                                                <td><?=$period;?></td>
                                                <td>Rp <?=number_format($data['basic_salary'], 0, ',', '.');?></td>
                                                <td>Rp <?=number_format($data['allowance'], 0, ',', '.');?></td>
                                                <td>Rp <?=number_format($data['deduction'], 0, ',', '.');?></td>
                                                <td>Rp <?=number_format($data['total_salary'], 0, ',', '.');?></td>
                                                <td><?=$data['payment_date'] ? date('d-m-Y', strtotime($data['payment_date'])) : '-';?></td>
                                                <td><?=$data['status'];?></td>
                                            </tr>
                                            <?php
                                            }
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
                            <div class="text-muted">Copyright &copy; Central Elektronik 2024</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#dataTable').DataTable();
            });
        </script>
    </body>
</html> 