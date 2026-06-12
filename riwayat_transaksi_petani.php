<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: index.php");
    exit;
}
$id_user = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Aktivitas Pertanian // Petani</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background:#f8faf9; color:#333; padding: 50px 8%; }

        .header { margin-bottom: 40px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 32px; font-weight: 800; color: #0F5C4C; }
        .btn-back { text-decoration: none; color: #0F5C4C; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; }
        .btn-back:hover { color: #F4C430; }

        .section-title { margin: 40px 0 20px 0; }
        .section-title h2 { font-size: 24px; color: #0F5C4C; font-weight: 700; border-left: 5px solid #F4C430; padding-left: 15px; }

        .history-item { background: #fff; padding: 20px; border-radius: 15px; display: flex; align-items: center; gap: 20px; margin-bottom: 15px; border: 1px solid #eee; transition: 0.3s; border-left: 5px solid #eee; }
        .icon-box { background: #0F5C4C; color: white; padding: 15px; border-radius: 12px; display: flex; align-items: center; justify-content: center; width: 55px; height: 55px; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <a href="dashboard_petani.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
            <h1 style="margin-top: 10px;">Fitur <span style="color: #F4C430;">Riwayat Transaksi</span></h1>
        </div>
        <p style="font-size: 12px; font-weight: 700; color: #888;">SCM REAL-TIME LOG</p>
    </div>

    <!-- 1. RIWAYAT SUPPLIER -->
    <div class="section-title">
        <h2>Status Pengiriman Bibit (Supplier)</h2>
    </div>
    <div style="margin-bottom: 50px;">
        <?php
        $q_riwayat_bibit = mysqli_query($conn, "SELECT pb.*, p.nama_produk FROM pesanan_bibit pb 
                                                JOIN produk p ON pb.id_produk = p.id_produk 
                                                WHERE pb.id_petani = '$id_user' ORDER BY pb.id_pesanan DESC");
        if(mysqli_num_rows($q_riwayat_bibit) == 0) {
            echo "<p style='color:#999;'>Belum ada riwayat pesanan bibit.</p>";
        } else {
            while($b = mysqli_fetch_assoc($q_riwayat_bibit)) {
                $st = strtolower($b['status']);
                $warna = ($st == 'selesai') ? '#10b981' : (($st == 'diproses') ? '#3b82f6' : '#f59e0b');
            ?>
                <div class="history-item" style="border-left: 5px solid <?= $warna ?>;">
                    <div class="icon-box" style="background: <?= $warna ?>;"><i class="fa-solid fa-seedling fa-xl"></i></div>
                    <div style="flex-grow: 1;">
                        <h4 style="margin: 0;"><?= $b['nama_produk'] ?></h4>
                        <p style="font-size: 12px; color: #777;">Jumlah: <?= $b['jumlah'] ?> kg | #AGRO-<?= $b['id_pesanan'] ?></p>
                    </div>
                    <div style="text-align: right;"><b style="color: <?= $warna ?>;"><?= strtoupper($b['status']) ?></b></div>
                </div>
        <?php } } ?>
    </div>

    <!-- 2. RIWAYAT DISTRIBUTOR -->
    <div class="section-title">
        <h2>Hasil Penjualan Panen (Distributor)</h2>
    </div>
    <div>
        <?php
        $q_riwayat_panen = mysqli_query($conn, "SELECT * FROM pengajuan_panen WHERE id_petani = '$id_user' ORDER BY id_panen DESC");
        if(mysqli_num_rows($q_riwayat_panen) == 0) {
            echo "<p style='color:#999;'>Belum ada riwayat pengajuan penjualan panen.</p>";
        } else {
            while($p = mysqli_fetch_assoc($q_riwayat_panen)) {
                $st_p = strtolower($p['status']);
                $warna_p = ($st_p == 'disetujui') ? '#10b981' : (($st_p == 'ditolak') ? '#ef4444' : '#f59e0b');
            ?>
                <div class="history-item" style="border-left: 5px solid <?= $warna_p ?>;">
                    <div class="icon-box" style="background: <?= $warna_p ?>;"><i class="fa-solid fa-check-double fa-xl"></i></div>
                    <div style="flex-grow: 1;">
                        <h4 style="margin: 0;"><?= $p['nama_hasil'] ?></h4>
                        <p style="font-size: 12px; color: #777;">Volume: <?= $p['jumlah'] ?> kg</p>
                    </div>
                    <div style="text-align: right;"><b style="color: <?= $warna_p ?>;"><?= strtoupper($p['status']) ?></b></div>
                </div>
        <?php } } ?>
    </div>

</body>
</html>