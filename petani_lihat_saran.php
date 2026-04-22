<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];

// Mengambil data saran berdasarkan ID Petani yang login
$query = mysqli_query($conn, "SELECT * FROM saran_pemupukan WHERE id_petani = '$id_user' ORDER BY id_saran DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Saran Ahli // Farmer Intelligence</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --obsidian: #022c22; --emerald: #10b981; --slate: #f8fafc; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Satoshi', sans-serif; }
        body { background: var(--slate); padding: 60px 80px; }
        .saran-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; }
        .saran-card { background: white; border-radius: 30px; padding: 30px; border: 1px solid #eee; transition: 0.3s; }
        .saran-card:hover { transform: translateY(-10px); border-color: var(--emerald); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .date { font-size: 12px; color: #94a3b8; font-weight: 700; margin-top: 20px; display: block; border-top: 1px solid #f1f1f1; padding-top: 15px; }
    </style>
</head>
<body>

    <div style="margin-bottom: 40px;">
        <a href="dashboard_petani.php" style="text-decoration:none; color:var(--emerald); font-weight:800;">← KEMBALI</a>
        <h1 style="font-size: 40px; font-weight: 900; margin-top:10px;">Saran <span style="color: var(--emerald);">Ahli.</span></h1>
    </div>

    <div class="saran-grid">
        <?php
        if(mysqli_num_rows($query) > 0):
            while($row = mysqli_fetch_assoc($query)):
        ?>
        <div class="saran-card">
            <div style="color: var(--emerald); margin-bottom: 15px;"><i class="fa-solid fa-vial-circle-check fa-2x"></i></div>
            <h3 style="font-size: 18px; font-weight: 900; color: var(--obsidian);"><?= $row['judul'] ?></h3>
            <p style="color: #64748b; font-size: 14px; margin-top: 10px; line-height: 1.6;">
                <?= nl2br($row['isi_saran']) ?>
            </p>
            <span class="date">
                <i class="fa-solid fa-calendar-day"></i> <?= date('d M Y', strtotime($row['tanggal'])) ?> 
                | <span style="color: var(--emerald);">STATUS: <?= strtoupper($row['status']) ?></span>
            </span>
        </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div style="grid-column: span 3; text-align: center; padding: 100px; color: #94a3b8;">
                <i class="fa-solid fa-comment-slash fa-3x"></i>
                <p style="margin-top: 15px;">Belum ada saran untuk lahan Anda.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>