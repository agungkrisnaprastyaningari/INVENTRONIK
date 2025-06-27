<?php
require 'function.php';
require 'check.php';

$email = $_SESSION['email'];
$user_query = mysqli_query($conn, "SELECT id, role FROM user WHERE email = '$email'");
$user_data = mysqli_fetch_array($user_query);
$user_id = $user_data['id'];
$user_role = $user_data['role'];

if ($user_role !== 'employee') {
    header('location:index.php');
    exit();
}

// Handle check-out
if(isset($_POST['check_out'])) {
    $date = date('Y-m-d');
    $time = date('H:i:s');
    
    // Update time_out for today's attendance
    $update_checkout = mysqli_query($conn, "UPDATE attendance SET time_out = '$time' WHERE user_id = '$user_id' AND date = '$date' AND time_out IS NULL");
    
    if($update_checkout) {
        echo "<script>alert('Check-out berhasil dicatat!');</script>";
    } else {
        echo "<script>alert('Anda belum melakukan check-in hari ini!');</script>";
    }
}

// Handle attendance submission (check-in)
if(isset($_POST['record_attendance'])) {
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $status = $_POST['status'];
    $notes = $_POST['notes'];
    
    // Check if attendance already exists for today
    $check_attendance = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = '$user_id' AND date = '$date'");
    
    if(mysqli_num_rows($check_attendance) > 0) {
        echo "<script>alert('Anda sudah melakukan check-in hari ini!');</script>";
    } else {
        // Record new attendance
        mysqli_query($conn, "INSERT INTO attendance (user_id, date, time_in, status, notes) VALUES ('$user_id', '$date', '$time', '$status', '$notes')");
        echo "<script>alert('Check-in berhasil dicatat!');</script>";
    }
}

// Check if already checked in today but not checked out
$date = date('Y-m-d');
$check_status = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id = '$user_id' AND date = '$date'");
$can_checkout = false;
if($check_status && mysqli_num_rows($check_status) > 0) {
    $attendance_data = mysqli_fetch_array($check_status);
    if($attendance_data['time_out'] === NULL) {
        $can_checkout = true;
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
        <title>Absensi</title>
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
                            <a class="nav-link" href="attendance.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                                Absensi
                            </a>
                            <a class="nav-link" href="salary.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-money-bill"></i></div>
                                Gaji
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
                        <h1 class="mt-4">Absensi</h1>
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attendanceModal">
                                        <i class="fas fa-sign-in-alt"></i> Check In
                                    </button>
                                    <?php if($can_checkout): ?>
                                    <form method="post" class="d-inline">
                                        <button type="submit" name="check_out" class="btn btn-danger">
                                            <i class="fas fa-sign-out-alt"></i> Check Out
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right">
                                    <h5 class="mb-0">
                                        <?php echo date('l, d F Y'); ?>
                                        <span id="clock" class="ml-2"></span>
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ambilsemuaattendance = mysqli_query($conn, "SELECT * FROM attendance WHERE user_id='$user_id' ORDER BY date DESC, time_in DESC");
                                            while($data = mysqli_fetch_array($ambilsemuaattendance)){
                                            ?>
                                            <tr>
                                                <td><?=date('d-m-Y', strtotime($data['date']));?></td>
                                                <td><?=$data['time_in'];?></td>
                                                <td><?=$data['time_out'] ? $data['time_out'] : '-';?></td>
                                                <td><?=$data['status'];?></td>
                                                <td><?=$data['notes'];?></td>
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

        <!-- Attendance Modal -->
        <div class="modal fade" id="attendanceModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Record Attendance</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="hadir">Hadir</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="record_attendance">Submit</button>
                        </div>
                    </form>
                </div>
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
                
                // Update clock
                function updateClock() {
                    var now = new Date();
                    var hours = now.getHours().toString().padStart(2, '0');
                    var minutes = now.getMinutes().toString().padStart(2, '0');
                    var seconds = now.getSeconds().toString().padStart(2, '0');
                    $('#clock').text(hours + ':' + minutes + ':' + seconds);
                }
                
                setInterval(updateClock, 1000);
                updateClock();
            });
        </script>
    </body>
</html> 