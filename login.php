<?php
session_start();
require 'function.php';

// Clear any existing session
if (!isset($_POST['login'])) {
    session_unset();
    session_destroy();
    session_start();
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $cekdatabase = mysqli_query($conn, "SELECT * FROM user WHERE email = '$email' AND password='$password'");
    if (!$cekdatabase) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    $hitung = mysqli_num_rows($cekdatabase);
    
    if($hitung > 0){
        $userData = mysqli_fetch_array($cekdatabase);
        $_SESSION['log'] = 'true';
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $userData['role'];
        $_SESSION['name'] = $userData['name'];
        
        header('location:index.php');
        exit();
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
    }
}

// Redirect if already logged in
if(isset($_SESSION['log']) && $_SESSION['log'] === 'true'){
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
    <title>Login - Central Elektronik</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #E3F2FD, #BBDEFB, #90CAF9);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            height: 100vh;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .page-wrapper {
            width: 100%;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .background-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 1;
            overflow: hidden;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            left: -150px;
            animation-delay: -2s;
        }

        .shape:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            right: -100px;
            animation-delay: -1s;
        }

        .shape:nth-child(3) {
            width: 150px;
            height: 150px;
            bottom: 50%;
            left: 10%;
            animation-delay: -3s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 10%;
            right: 10%;
            animation-delay: -4s;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0) rotate(0deg); }
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            padding: 40px;
            max-width: 400px;
            width: 90%;
            position: relative;
            z-index: 2;
            transform: translateY(0);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.25);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header i {
            color: #1976D2;
            margin-bottom: 20px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }

        .login-header i:hover {
            transform: scale(1.1);
        }

        .login-header h3 {
            color: #1565C0;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .login-header p {
            color: #42A5F5;
            font-size: 15px;
            font-weight: 300;
        }

        .form-floating {
            margin-bottom: 25px;
            position: relative;
        }

        .form-floating input {
            height: 60px;
            border-radius: 12px;
            border: 2px solid #E3F2FD;
            padding: 15px 20px;
            font-size: 16px;
            background-color: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .form-floating input:focus {
            border-color: #1976D2;
            box-shadow: 0 0 0 4px rgba(25, 118, 210, 0.1);
            background-color: #ffffff;
        }

        .form-floating label {
            padding: 20px;
            color: #64B5F6;
            font-weight: 400;
        }

        .btn-login {
            background: linear-gradient(45deg, #1976D2, #2196F3);
            border: none;
            border-radius: 12px;
            color: white;
            padding: 15px;
            width: 100%;
            font-size: 16px;
            font-weight: 500;
            margin-top: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transition: 0.5s;
        }

        .btn-login:hover {
            background: linear-gradient(45deg, #1565C0, #1976D2);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(25, 118, 210, 0.3);
        }

        .btn-login:hover:before {
            left: 100%;
        }

        .btn-login i {
            margin-right: 8px;
            font-size: 18px;
        }

        .copyright {
            position: fixed;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: #1565C0;
            font-size: 14px;
            font-weight: 300;
            text-shadow: 0 1px 2px rgba(255,255,255,0.5);
            z-index: 2;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px;
                margin: 20px;
            }

            .login-header h3 {
                font-size: 24px;
            }

            .form-floating input {
                height: 55px;
            }
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="background-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
        </div>
        
        <div class="login-container">
            <div class="login-header">
                <i class="fas fa-microchip fa-3x"></i>
                <h3>CENTRAL ELEKTRONIK</h3>
                <p>Inventory Management System</p>
            </div>
            <form method="post">
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="inputEmail" name="email" placeholder="name@example.com" required>
                    <label for="inputEmail">Email address</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password" required>
                    <label for="inputPassword">Password</label>
                </div>
                <button type="submit" name="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>

    <div class="copyright">
        &copy; <?php echo date('Y'); ?> Central Elektronik. All rights reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
