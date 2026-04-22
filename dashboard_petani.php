<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
include "koneksi.php";

$id_user = $_SESSION['id']; 

// --- 1. LOGIKA NOTIFIKASI SARAN AHLI (DENGAN PROTEKSI TABEL) ---
$check_saran = mysqli_query($conn, "SHOW TABLES LIKE 'saran_pemupukan'");
$notif_saran = 0;
if(mysqli_num_rows($check_saran) > 0) {
    $tgl_sekarang = date('Y-m-d');
    $q_notif_saran = mysqli_query($conn, "SELECT COUNT(*) as total FROM saran_pemupukan WHERE tanggal = '$tgl_sekarang'");
    $res_notif_saran = mysqli_fetch_assoc($q_notif_saran);
    $notif_saran = $res_notif_saran['total'] ?? 0;
}

// --- 2. DATA STATISTIK SUPPLIER & DISTRIBUTOR ---
$q_pesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan_bibit WHERE id_petani = '$id_user' AND status != 'selesai'");
$res_pesanan = mysqli_fetch_assoc($q_pesanan);

$q_panen = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_panen WHERE id_petani = '$id_user' AND status = 'menunggu'");
$res_panen = mysqli_fetch_assoc($q_panen);

// --- 3. NOTIFIKASI LAPORAN BURUH ---
$notif_buruh = $res_panen['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCM Agro - Smart Farming Hub</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        *{ margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body{ background:#f8faf9; color:#333; overflow-x:hidden; scroll-behavior: smooth; }

        .navbar{ position:fixed; width:100%; top:0; padding:20px 8%; display:flex; justify-content:space-between; align-items:center; transition:0.3s; z-index:1000; }
        .navbar.scrolled{ background:white; box-shadow:0 8px 25px rgba(0,0,0,0.05); }
        .logo{ font-size:24px; font-weight:800; color:white; }
        .navbar.scrolled .logo{ color:#0F5C4C; }
        .nav-links{ display:flex; gap:30px; align-items: center; }
        .nav-links a{ text-decoration:none; color:white; font-weight:500; transition:0.3s; position: relative; }
        .navbar.scrolled .nav-links a{ color:#333; }
        
        /* Notif Kecil Saran Ahli di Navbar */
        .badge-nav { background: #FF4D4D; color: white; padding: 2px 7px; border-radius: 50%; font-size: 10px; position: absolute; top: -10px; right: -12px; border: 2px solid #0F5C4C; }
        .navbar.scrolled .badge-nav { border-color: white; }

        .btn{ background:#F4C430; padding:10px 20px; border-radius:30px; font-weight:600; color:black; text-decoration:none; }

        .hero{ height:100vh; background:linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url("petani.jpg"); background-size:cover; background-position:center; display:flex; align-items:center; padding:0 8%; position:relative; }
        .hero-content{ max-width:700px; color:white; animation:fadeUp 1.2s ease; }
        .hero-content h1{ font-size:60px; font-weight:800; line-height:1.1; margin-bottom:20px; }
        .hero-content span{ color:#F4C430; }

        .stats{ position:absolute; bottom:-60px; left:8%; background:white; padding:30px 50px; border-radius:20px; display:flex; gap:50px; box-shadow:0 20px 50px rgba(0,0,0,0.08); }
        .stat h3{ font-size:28px; color:#0F5C4C; }
        .stat p{ font-size:14px; color:#777; }

        .section{ padding:140px 8% 60px 8%; }
        .section-title{ text-align:center; margin-bottom:60px; }
        .section-title h2{ font-size:40px; color:#0F5C4C; }

        /* Notifikasi Banner Buruh */
        .notif-banner { background: #022c22; color: white; padding: 25px; border-radius: 20px; margin-bottom: 40px; display: flex; align-items: center; gap: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); animation: pulse 2s infinite; }

        .cards{ display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:40px; }
        .card-link{ text-decoration:none; color:inherit; }
        .card{ background:white; border-radius:25px; overflow:hidden; box-shadow:0 15px 40px rgba(0,0,0,0.05); transition:0.4s; position: relative; }
        .card:hover{ transform:translateY(-15px); }
        .card img{ width:100%; height:240px; object-fit:cover; }
        .card-content{ padding:30px; }

        /* Badge Laporan Buruh di Kartu */
        .badge-card { position: absolute; top: 15px; right: 15px; background: #ef4444; color: white; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 3px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }

        .history-item { background: #fff; padding: 20px; border-radius: 15px; display: flex; align-items: center; gap: 20px; margin-bottom: 15px; border: 1px solid #eee; transition: 0.3s; border-left: 5px solid #eee; }
        .icon-box { background: #0F5C4C; color: white; padding: 15px; border-radius: 12px; display: flex; align-items: center; justify-content: center; width: 55px; height: 55px; }

        footer{ background:#0F5C4C; color:white; text-align:center; padding:40px; }

        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.02); } 100% { transform: scale(1); } }
        @keyframes fadeUp{ from{ opacity:0; transform:translateY(40px); } to{ opacity:1; transform:translateY(0); } }

        .badge-card { 
    position: absolute; 
    top: 15px; 
    right: 15px; 
    background: #ef4444; 
    color: white; 
    width: 35px; 
    height: 35px; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    font-weight: 800; 
    border: 3px solid white; 
    box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
    z-index: 10;
}
    </style>
</head>
<body>

<div class="navbar" id="navbar">
    <div class="logo">🌾 SCM Agro</div>
    <div class="nav-links">
        <a href="#">Dashboard</a>
        <a href="#riwayat">Riwayat Transaksi</a>
        <a href="petani_lihat_saran.php">Saran Ahli
            <?php if($notif_saran > 0): ?>
                <span class="badge-nav"><?= $notif_saran ?></span>
            <?php endif; ?>
        </a>
        <a href="logout.php" class="btn">Logout</a>
    </div>
</div>

<section class="hero">
    <div class="hero-content">
        <p style="color: #F4C430; font-weight: 600; letter-spacing: 2px;">SELAMAT DATANG, <?= strtoupper($username) ?></p>
        <h1>Aktivitas Tani <span>Terintegrasi</span><br>Dalam Satu Genggaman</h1>
        <p>Kelola kebutuhan lahan, pantau pesanan bibit, dan validasi laporan buruh secara real-time.</p>
    </div>

    <div class="stats">
        <div class="stat">
            <h3><?= $res_pesanan['total'] ?? 0 ?></h3>
            <p>Pesanan Bibit</p>
        </div>
        <div class="stat">
            <h3><?= $notif_buruh ?></h3>
            <p>Laporan Buruh</p>
        </div>
        <div class="stat" style="border-left: 2px solid #eee; padding-left: 20px;">
            <p style="margin-bottom: 0;">Sistem SCM:</p>
            <span style="color: #2E7D32; font-weight: 700; font-size: 14px;"><i class="fa-solid fa-circle-check"></i> Cloud Sync Active</span>
        </div>
    </div>
</section>

<section class="section" id="layanan">
   
    <div class="section-title">
        <h2>Layanan Digital Pertanian</h2>
    </div>

    <div class="cards">
        <a href="pesan_bibit.php" class="card-link">
            <div class="card">
                <img src="Pemesanan_bibit.png" alt="Pesan Bibit">
                <div class="card-content">
                    <h3>Pemesanan Bibit & Pupuk</h3>
                    <p>Input data lahan dan pesan sarana produksi langsung ke Supplier.</p>
                </div>
            </div>
        </a>

        <a href="Penjualan_panen.php" class="card-link">
            <div class="card">
                <img src="Penjualan_panen.png" alt="Jual Panen">
                <div class="card-content">
                    <h3>Penjualan Panen</h3>
                    <p>Ajukan stok panen untuk diverifikasi oleh Distributor.</p>
                </div>
            </div>
        </a>

        <a href="petani_laporan_buruh.php" class="card-link">
        <div class="card">
            <?php if($notif_buruh > 0): ?>
                <div class="badge-card"><?= $notif_buruh ?></div>
            <?php endif; ?>
            <img src="transaksi&distribusi.png" alt="Buruh">
            <div class="card-content">
                <h3>Laporan Lahan (Buruh)</h3>
                <p>Pantau dokumentasi foto dan berat hasil panen dari buruh lapangan.</p>
            </div>
        </div>
    </a>
    </div>
</section>

<section class="section" id="riwayat" style="background: white; padding-top: 50px;">
    <div class="section-title">
        <h2>Status Pengiriman Bibit (Supplier)</h2>
    </div>
    <div style="max-width: 900px; margin: 0 auto; margin-bottom: 80px;">
        <?php
        $q_riwayat_bibit = mysqli_query($conn, "SELECT pb.*, p.nama_produk FROM pesanan_bibit pb 
                                                JOIN produk p ON pb.id_produk = p.id_produk 
                                                WHERE pb.id_petani = '$id_user' ORDER BY pb.id_pesanan DESC");
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
        <?php } ?>
    </div>

    <div class="section-title">
        <h2>Hasil Penjualan Panen (Distributor)</h2>
    </div>
    <div style="max-width: 900px; margin: 0 auto;">
        <?php
        $q_riwayat_panen = mysqli_query($conn, "SELECT * FROM pengajuan_panen WHERE id_petani = '$id_user' ORDER BY id_panen DESC");
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
        <?php } ?>
    </div>
</section>

<footer>
    <div class="logo">🌾 SCM Agro</div>
    <p style="margin-top: 10px; opacity: 0.7; font-size: 13px;">Smart Farming System - Digital Supply Chain 2026</p>
</footer>

<script>
window.addEventListener("scroll", function(){
    var navbar = document.getElementById("navbar");
    if(navbar) navbar.classList.toggle("scrolled", window.scrollY > 50);
});
</script>

</body>
</html>