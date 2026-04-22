<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_petani = $_SESSION['id']; 
    $nama_produk_form = mysqli_real_escape_string($conn, $_POST['produk']);
    $jumlah = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $status_awal = 'menunggu'; 

    // Cari ID Produk berdasarkan nama
    $query_produk = mysqli_query($conn, "SELECT id_produk FROM produk WHERE nama_produk = '$nama_produk_form' LIMIT 1");
    $data_produk = mysqli_fetch_assoc($query_produk);

    if ($data_produk) {
        $id_produk = $data_produk['id_produk'];

        // Sesuai SQL kamu: tabel 'pesanan_bibit' punya kolom 'tanggal' (bukan tanggal_pesan)
        $sql = "INSERT INTO pesanan_bibit (id_petani, id_produk, jumlah, status) 
                VALUES ('$id_petani', '$id_produk', '$jumlah', '$status_awal')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('Pesanan Berhasil!');
                window.location.href='dashboard_petani.php';
            </script>";
        } else {
            echo "Gagal Simpan: " . mysqli_error($conn);
        }
    } else {
        // Jika ini muncul, berarti INSERT produk di langkah 1 tadi belum dijalankan
        echo "Error: Produk '$nama_produk_form' tidak ada di tabel produk database.";
    }
}
?>