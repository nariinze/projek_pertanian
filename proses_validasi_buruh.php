<?php
session_start();
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Update status jadi disetujui (agar sah jadi stok yang bisa dijual)
    $query = "UPDATE pengajuan_panen SET status = 'disetujui' WHERE id_panen = '$id'";
    $exec = mysqli_query($conn, $query);

    if ($exec) {
        echo "<script>
                alert('Laporan Disetujui! Mengalihkan ke Penjualan Panen...');
                window.location='Penjualan_panen.php'; 
              </script>";
    } else {
        echo "Gagal Update: " . mysqli_error($conn);
    }
} else {
    echo "ID tidak ditemukan.";
}
?>