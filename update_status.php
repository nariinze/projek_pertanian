<?php
session_start();
include "koneksi.php";

/* CEK LOGIN */
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

/* AMBIL ID TRANSAKSI */
$id = $_POST['id'];

/* AMBIL DATA TRANSAKSI */
$query = mysqli_query($conn, "SELECT status FROM transaksi WHERE id_transaksi='$id'");
$data = mysqli_fetch_assoc($query);

/* CEK DATA ADA */
if (!$data) {
    echo "Data tidak ditemukan!";
    exit;
}

$status = $data['status'];

/* LOGIKA PERUBAHAN STATUS */
switch($status){
    case 'Menunggu':
        $next = 'Proses';
        break;
    case 'Proses':
        $next = 'Kirim';
        break;
    case 'Kirim':
        $next = 'Selesai';
        break;
    default:
        $next = 'Selesai';
}

/* UPDATE STATUS */
$update = mysqli_query($conn, 
    "UPDATE transaksi 
     SET status='$next' 
     WHERE id_transaksi='$id'"
);

/* CEK BERHASIL */
if($update){
    header("Location: transaksi_distribusi.php");
} else {
    echo "Gagal update status!";
}
?>