<?php
session_start();
include "koneksi.php";

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT p.*, u.nama FROM pengajuan_panen p JOIN users u ON p.id_petani = u.id WHERE p.id_panen = '$id'");
$d = mysqli_fetch_assoc($query);

// Update Status Bayar jika tombol diklik
if(isset($_POST['bayar'])) {
    mysqli_query($conn, "UPDATE pengajuan_panen SET status_bayar = 'Lunas' WHERE id_panen = '$id'");
    header("Location: riwayat_distribusi.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Payment Detail // Elite Distri.</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1B4332; --accent: #74C69D; --bg: #F0F4F8; --white: #ffffff; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }
        body { background: var(--bg); display: flex; justify-content: center; padding: 50px; }
        
        .invoice { background: var(--white); width: 100%; max-width: 600px; padding: 50px; border-radius: 40px; box-shadow: 0 30px 60px rgba(0,0,0,0.05); }
        .head { border-bottom: 2px dashed #eee; padding-bottom: 30px; margin-bottom: 30px; }
        .head h2 { font-weight: 900; color: var(--primary); font-size: 32px; }
        
        .item-info { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .item-info label { color: #bbb; font-weight: 800; font-size: 12px; text-transform: uppercase; }
        .item-info p { font-weight: 700; color: var(--primary); }

        .total-box { background: var(--primary); color: white; padding: 30px; border-radius: 25px; margin-top: 30px; }
        .btn-pay { background: var(--accent); color: var(--primary); width: 100%; padding: 20px; border: none; border-radius: 50px; font-weight: 900; margin-top: 30px; cursor: pointer; transition: 0.3s; text-transform: uppercase; }
        .btn-pay:hover { transform: scale(1.02); filter: brightness(1.1); }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="head">
            <p style="color: var(--accent); font-weight: 800; margin-bottom: 5px;">PAYMENT SETTLEMENT</p>
            <h2>#TRX-<?= $d['id_panen'] ?></h2>
        </div>

        <div class="item-info"><label>Farmer Name</label><p Timothy><?= $d['nama'] ?></p></div>
        <div class="item-info"><label>Commodity</label><p><?= $d['nama_hasil'] ?></p></div>
        <div class="item-info"><label>Volume</label><p><?= number_format($d['jumlah']) ?> KG</p></div>
        <div class="item-info"><label>Price / KG</label><p>Rp <?= number_format($d['harga_perkg'] ?? 15000) ?></p></div>

        <div class="total-box">
            <label style="color: rgba(255,255,255,0.5);">TOTAL PAYMENT</label>
            <h1 style="font-size: 36px; font-weight: 900;">Rp <?= number_format($d['jumlah'] * ($d['harga_perkg'] ?? 15000)) ?></h1>
        </div>

        <?php if($d['status_bayar'] == 'Belum Dibayar'): ?>
            <form method="POST"><button type="submit" name="bayar" class="btn-pay">Konfirmasi Pembayaran</button></form>
        <?php else: ?>
            <div style="background: #D8F3DC; color: #1B4332; text-align: center; padding: 20px; border-radius: 50px; font-weight: 900; margin-top: 30px;">LUNAS</div>
        <?php endif; ?>
        
        <a href="riwayat_distribusi.php" style="display: block; text-align: center; margin-top: 20px; color: #bbb; text-decoration: none; font-weight: 700;">Kembali</a>
    </div>
</body>
</html>