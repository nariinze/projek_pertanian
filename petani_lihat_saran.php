<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}
include "koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Saran Ahli Pertanian</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; padding: 40px; }
        .container { max-width: 800px; margin: auto; }
        .back-btn { text-decoration: none; color: #0F5C4C; font-weight: 600; display: inline-block; margin-bottom: 20px; }
        .saran-card { 
            background: white; padding: 25px; border-radius: 15px; 
            margin-bottom: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-left: 5px solid #0F5C4C;
        }
        .saran-card h3 { color: #0F5C4C; margin-bottom: 10px; }
        .saran-card p { color: #555; line-height: 1.6; }
        .meta { font-size: 12px; color: #999; margin-top: 15px; }
        .badge { background: #E8F5E9; color: #2E7D32; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard_petani.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    
    <h2 style="margin-bottom: 30px;">💡 Saran Ahli & Tips Pemupukan</h2>

   <?php
// Query diperbaiki: Hanya mengambil dari tabel saran_pemupukan karena tabel 'saran' tidak ada di SQL Anda
$query = "SELECT judul, isi, tanggal, 'Pemupukan' as tipe FROM saran_pemupukan ORDER BY tanggal DESC";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="saran-card">
            <span class="badge"><?= strtoupper($row['tipe']) ?></span>
            <h3><?= htmlspecialchars($row['judul']) ?></h3>
            <p><?= nl2br(htmlspecialchars($row['isi'])) ?></p>
            <div class="meta">
                <i class="fa-regular fa-calendar"></i> Diposting pada: <?= date('d M Y', strtotime($row['tanggal'])) ?>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='saran-card'><p>Belum ada saran dari ahli saat ini.</p></div>";
}
?>
</div>

</body>
</html>