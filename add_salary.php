<?php
require 'function.php';
require 'check.php';

$email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT id, role FROM user WHERE email = '$email'");
$user_data = mysqli_fetch_array($user_query);
$user_role = $user_data['role'];

// Only admin can access this page
if ($user_role !== 'admin') {
    header('location:index.php');
    exit();
}

// Process form submission
if (isset($_POST['addgaji'])) {
    $user_id = $_POST['user_id'];
    $year = $_POST['year'];
    $month = $_POST['month'];
    $basic_salary = $_POST['basic_salary'];
    $allowance = $_POST['allowance'];
    $deduction = $_POST['deduction'];
    $total_salary = $basic_salary + $allowance - $deduction;
    $payment_date = $_POST['payment_date'];
    $status = $_POST['status'];

    $add_query = mysqli_query($conn, "INSERT INTO salary (user_id, year, month, basic_salary, allowance, deduction, total_salary, payment_date, status) 
                                     VALUES ('$user_id', '$year', '$month', '$basic_salary', '$allowance', '$deduction', '$total_salary', '$payment_date', '$status')");

    if ($add_query) {
        header('location:salary.php?msg=success');
    } else {
        header('location:salary.php?msg=error');
    }
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
        <title>Tambah Gaji</title>
        <link href="css/styles.css" rel="stylesheet" />
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
                            <a class="nav-link" href="employee.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Kelola Karyawan
                            </a>
                            <a class="nav-link" href="salary.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                                Kelola Gaji
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
                        <h1 class="mt-4">Tambah Data Gaji</h1>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label for="user_id">Karyawan</label>
                                        <select class="form-control" name="user_id" required>
                                            <?php
                                            $employee_query = mysqli_query($conn, "SELECT id, name FROM user WHERE role='employee'");
                                            while($employee = mysqli_fetch_array($employee_query)) {
                                                echo "<option value='".$employee['id']."'>".$employee['name']."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="year">Tahun</label>
                                            <input type="number" class="form-control" name="year" min="2000" max="2099" value="<?=date('Y')?>" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="month">Bulan</label>
                                            <select class="form-control" name="month" required>
                                                <?php
                                                for($m=1; $m<=12; $m++) {
                                                    $month = date('F', mktime(0,0,0,$m,1));
                                                    echo "<option value='$m'".($m==date('n')?" selected":"").">$month</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="basic_salary">Gaji Pokok</label>
                                        <input type="number" class="form-control" name="basic_salary" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="allowance">Tunjangan</label>
                                        <input type="number" class="form-control" name="allowance" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="deduction">Potongan</label>
                                        <input type="number" class="form-control" name="deduction" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="payment_date">Tanggal Pembayaran</label>
                                        <input type="date" class="form-control" name="payment_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" required>
                                            <option value="Pending">Pending</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="addgaji" class="btn btn-primary">Simpan</button>
                                </form>
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
    </body>
</html> 