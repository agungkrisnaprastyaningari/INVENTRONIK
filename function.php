<?php
session_start();
$conn = mysqli_connect("localhost","root","","inven");

// Check if user is admin before any modification operations
function isAdmin() {
    global $conn;
    if (!isset($_SESSION['email'])) return false;
    $email = $_SESSION['email'];
    $check_admin = mysqli_query($conn, "SELECT role FROM user WHERE email = '$email'");
    $user_role = mysqli_fetch_array($check_admin)['role'];
    return $user_role === 'admin';
}

//namanahin barang baru
if(isset($_POST['Addnewbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk menambah barang!');</script>";
        exit();
    }
    $namabarang = $_POST['namabarang'];
    $merek = $_POST['merek'];
    $model = $_POST['model'];
    $stock = $_POST['stock'];
    $harga = $_POST['harga'];
    

    $addtotable = mysqli_query( $conn,"insert into barang(namabarang,merek,model,stock,harga) values('$namabarang','$merek','$model','$stock','$harga')");
    if($addtotable){
        header('location:masuk.php');
    }else{
        echo'gagal';
        header('location:masuk.php');
    }
}
// nambah barang masuk
    if(isset($_POST['barangmasuk'])){
    $namabarang = $_POST['namabarang'];
    $pemasok = $_POST['pemasok'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from barang where idbarang = '$namabarang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $stockbaru = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn,"insert into masuk(idbarang, pemasok, qty) values('$namabarang','$pemasok','$qty')");
    $updatestockmasuk = mysqli_query($conn,"update barang set stock = '$stockbaru' where idbarang = '$namabarang'");
    if($addtomasuk && $updatestockmasuk){ 
        header('location:masuk.php');
    }else{
        echo'gagal';
        header('location:masuk.php');
    }
}


// nambah barang keluar
if(isset($_POST['barangkeluar'])){
    $namabarang = $_POST['namabarang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn,"select * from barang where idbarang = '$namabarang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $stockbaru = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn,"insert into keluar(idbarang, penerima, qty) values('$namabarang','$penerima','$qty')");
    $updatestockkeluar = mysqli_query($conn,"update barang set stock = '$stockbaru' where idbarang = '$namabarang'");
    if($addtokeluar && $updatestockkeluar){ 
        header('location:keluar.php');
    }else{
        echo'gagal';
        header('location:keluar.php');
    }
}



//edit barang
if(isset($_POST['editbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk mengedit barang!');</script>";
        exit();
    }
    $idbarang = $_POST['idbarang'];
    $namabarang = $_POST['namabarang'];
    $merek = $_POST['merek'];
    $model = $_POST['model'];
    $harga = $_POST['harga'];

    $editbarang = mysqli_query($conn,"update barang set namabarang = '$namabarang', merek = '$merek', model = '$model', harga = '$harga' where idbarang = '$idbarang'");
    if($editbarang){
        header('location:index.php');
    }else{
        echo'gagal';
        header('location:index.php');
    }
}


//hapus barang
 if(isset($_POST['hapusbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk menghapus barang!');</script>";
        exit();
    }
    $idbarang = $_POST['idbarangygmaudihapus'];
    $hapusbarang = mysqli_query($conn,"delete from barang where idbarang = '$idbarang'");
    if($hapusbarang){
        header('location:index.php');
    }else{
        echo'gagal';
        header('location:index.php');
    }
 }

if(isset($_POST['hapusbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk menghapus barang!');</script>";
        exit();
    }
    $idbarang = $_POST['idbarang'];
    $delete = mysqli_query($conn, "DELETE FROM barang WHERE idbarang='$idbarang'");
    if($delete){
        header('location:index.php');
    } else {
        echo 'gagal';
        header('location:index.php');
    }
}
?>