<?php
session_start();
include "koneksi.php";

// Proteksi halaman, pastikan hanya distributor yang bisa mengubah status logistik
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id_transaksi = mysqli_real_escape_string($conn, $_POST['id']);

    // 1. Ambil status logistik saat ini dari database
    $query_status = mysqli_query($conn, "SELECT status_logistik FROM transaksi_pembelian WHERE id_transaksi = '$id_transaksi'");
    $data = mysqli_fetch_assoc($query_status);

    if ($data) {
        $status_sekarang = $data['status_logistik'];
        $status_baru = $status_sekarang;

        // 2. Logika berjenjang tahapan pengiriman (Switch-Case Status)
        switch ($status_sekarang) {
            case 'Menunggu':
                $status_baru = 'Proses'; // Berubah menjadi truk sedang disiapkan
                break;
            case 'Proses':
                $status_baru = 'Kirim';  // Berubah menjadi truk dalam perjalanan
                break;
            case 'Kirim':
                $status_baru = 'Selesai'; // Komoditas telah sampai di gudang distributor
                break;
            default:
                $status_baru = 'Selesai';
                break;
        }

        // 3. Update status baru ke dalam database transaksi_pembelian
        $update = mysqli_query($conn, "UPDATE transaksi_pembelian SET status_logistik = '$status_baru' WHERE id_transaksi = '$id_transaksi'");

        if ($update) {
            // Jalankan alert sukses, lalu lempar balik ke halaman transaksi logistik tadi
            echo "<script>
                    alert('Status logistik #TX-" . $id_transaksi . " berhasil diperbarui menjadi: " . $status_baru . "');
                    window.location = 'transaksi_distribusi.php';
                  </script>";
            exit;
        } else {
            echo "Gagal memperbarui status logistik: " . mysqli_error($conn);
        }
    } else {
        echo "Data transaksi tidak ditemukan!";
    }
} else {
    // Jika diakses ilegal tanpa POST ID, kembalikan ke halaman utama logistik
    header("Location: transaksi_distribusi.php");
    exit;
}
?>