<?php
// Database connection
$conn = mysqli_connect("localhost","root","","inven");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is admin before any modification operations
function isAdmin() {
    if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
        return false;
    }
    return $_SESSION['role'] === 'admin';
}

//namanahin barang baru
if(isset($_POST['Addnewbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk menambah barang!');</script>";
        exit();
    }
    
    $namabarang = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $merek = mysqli_real_escape_string($conn, $_POST['merek']);
    $model = mysqli_real_escape_string($conn, $_POST['model']);
    $stock = (int)$_POST['stock'];
    $harga = (float)$_POST['harga'];
    
    $addtotable = mysqli_query($conn, "INSERT INTO barang (namabarang, merek, model, stock, harga) VALUES ('$namabarang', '$merek', '$model', $stock, $harga)");
    if($addtotable){
        header('location:index.php');
        exit();
    } else {
        echo "<script>alert('Gagal menambah barang: " . mysqli_error($conn) . "');</script>";
        header('location:index.php');
        exit();
    }
}
// nambah barang masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['namabarang'];
    $pemasok = $_POST['pemasok'];
    $qty = $_POST['qty'];
    $total_harga = $_POST['total_harga'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    
    // Generate invoice number
    $tahun = date('Y');
    $bulan = date('m');
    $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM masuk WHERE YEAR(tanggal) = '$tahun' AND MONTH(tanggal) = '$bulan'");
    $count = mysqli_fetch_array($query_count)['total'] + 1;
    $nomor_invoice = "INV/" . $tahun . $bulan . "/" . str_pad($count, 4, "0", STR_PAD_LEFT);
    
    $addtomasuk = mysqli_query($conn,"insert into masuk (idbarang, pemasok, qty, total_harga, metode_pembayaran, nomor_invoice) values('$barangnya', '$pemasok', '$qty', '$total_harga', '$metode_pembayaran', '$nomor_invoice')");
    if($addtomasuk){
        $updatestockmasuk = mysqli_query($conn,"update barang set stock = stock+'$qty' where idbarang='$barangnya'");
        if($updatestockmasuk){
            header('location:masuk.php');
        } else {
            echo 'Gagal';
            header('location:masuk.php');
        }
    } else {
        echo 'Gagal';
        header('location:masuk.php');
    }
}

// Process payment
if(isset($_POST['process_payment'])){
    $idmasuk = $_POST['idmasuk'];
    $metode_pembayaran = $_POST['metode_pembayaran'];
    
    $update_payment = mysqli_query($conn, "UPDATE masuk SET 
        status_pembayaran = 'Paid',
        metode_pembayaran = '$metode_pembayaran'
        WHERE idmasuk = '$idmasuk'");
        
    if($update_payment){
        header('location:masuk.php');
    } else {
        echo 'Gagal memproses pembayaran';
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


//hapus barang (soft delete)
if(isset($_POST['hapusbarang'])){
    if (!isAdmin()) {
        echo "<script>alert('Anda tidak memiliki akses untuk menghapus barang!');</script>";
        exit();
    }
    
    $idbarang = mysqli_real_escape_string($conn, $_POST['idbarang']);
    
    // Update status barang menjadi 'dihapus'
    $update_status = mysqli_query($conn, "UPDATE barang SET status = 'dihapus' WHERE idbarang = '$idbarang'");
    
    if($update_status){
        echo "<script>
            alert('Barang berhasil dihapus dari stock aktif!');
            window.location.href='index.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Gagal menghapus barang: " . mysqli_error($conn) . "');
            window.location.href='index.php';
        </script>";
        exit();
    }
}
?>