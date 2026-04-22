<?php
session_start();
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['id'])) {
        die("Error: Session ID tidak ditemukan.");
    }

    $id_petani = $_SESSION['id']; 
    // Sesuaikan name input dari form (tadi di form kamu name-nya 'produk' dan 'jumlah')
    $produk    = mysqli_real_escape_string($conn, $_POST['produk']);
    $jumlah    = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $tanggal   = date('Y-m-d');
    
    /* PENTING: 
       Ganti 'pesanan' menjadi 'pengajuan_panen' jika ini untuk Penjualan Panen.
       Atau ganti menjadi 'pesanan_bibit' jika ini untuk Pemesanan Bibit.
       Karena kamu sedang mengerjakan FR-009 (Penjualan Panen), maka kita pakai 'pengajuan_panen'.
    */

    $sql = "INSERT INTO pengajuan_panen (id_petani, nama_hasil, jumlah, tanggal, status) 
            VALUES ('$id_petani', '$produk', '$jumlah', '$tanggal', 'menunggu')";

    if (mysqli_query($conn, $sql)) {
        // Mengarahkan kembali ke dashboard dengan notifikasi sukses (FR-009)
        header("Location: dashboard_petani.php?pesan=panen_sukses");
        exit;
    } else {
        echo "Gagal menyimpan: " . mysqli_error($conn);
    }
}
?>