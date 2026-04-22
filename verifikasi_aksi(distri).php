<?php
session_start();
include "koneksi.php";

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_panen = $_GET['id'];
    $status_baru = $_GET['status']; // Nilainya 'disetujui' atau 'ditolak'

    // Update status di database
    $sql = "UPDATE pengajuan_panen SET status = '$status_baru' WHERE id_panen = '$id_panen'";
    
    if (mysqli_query($conn, $sql)) {
        // Berhasil: Langsung pindah ke halaman riwayat
        header("Location: riwayat_distribusi.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: dashboard_distributor.php");
}
?>