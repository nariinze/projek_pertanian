<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
include "koneksi.php";

// 1. Ambil jumlah pesanan baru untuk NOTIFIKASI (status Menunggu/Diproses)
$queryPesanan = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan_bibit WHERE status != 'Selesai'");
$dataPesanan = mysqli_fetch_assoc($queryPesanan);
$pesananBaru = $dataPesanan['total']; 

// 2. Ambil total seluruh transaksi selesai
$queryTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM pesanan_bibit WHERE status = 'Selesai'");
$dataTotal = mysqli_fetch_assoc($queryTotal);
$pesananSelesai = $dataTotal['total'];

// 3. Cek apakah ada saran yang baru dikirim hari ini (Statistik Tambahan)
$tgl_hari_ini = date('Y-m-d');
$querySaran = mysqli_query($conn, "SELECT COUNT(*) as total FROM saran_pemupukan WHERE tanggal = '$tgl_hari_ini'");
$dataSaran = mysqli_fetch_assoc($querySaran);
$saranHariIni = $dataSaran['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Supplier | SCM Agro</title>
    
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&f[]=general-sans@600,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --emerald-deep: #064e3b;
            --emerald-mid: #10b981;
            --emerald-soft: #ecfdf5;
            --glass: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.4);
            --text-main: #064e3b;
            --text-mute: #6b7280;
            --shadow-lux: 0 20px 40px -10px rgba(0,0,0,0.05);
            --danger: #ef4444;
        }

        /* Navigasi Badge Style */
        .nav-item { position: relative; }
        .notif-badge {
            position: absolute; top: 12px; left: 35px;
            background: var(--danger); color: white;
            font-size: 10px; font-weight: 800;
            padding: 2px 7px; border-radius: 10px;
            border: 2px solid white;
        }
        .sidebar:hover .notif-badge { left: auto; right: 20px; }

        /* Alert Animation */
        .alert-toast {
            background: white; border-left: 5px solid var(--emerald-mid);
            padding: 15px 25px; border-radius: 15px;
            display: flex; align-items: center; gap: 15px;
            box-shadow: var(--shadow-lux); margin-bottom: 30px;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn { from { opacity:0; transform: translateY(-20px); } to { opacity:1; transform: translateY(0); } }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Satoshi', sans-serif; }
        body {
            background: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.05) 0px, transparent 50%);
            min-height: 100vh; display: flex; color: var(--text-main);
        }

        .sidebar {
            width: 90px; background: var(--glass); backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border); margin: 20px;
            border-radius: 24px; display: flex; flex-direction: column;
            align-items: center; padding: 35px 0; position: fixed;
            height: calc(100vh - 40px); transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000; box-shadow: var(--shadow-lux);
        }
        .sidebar:hover { width: 260px; align-items: flex-start; padding-left: 20px; }
        .brand-icon { width: 45px; height: 45px; background: var(--emerald-mid); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 20px; margin-bottom: 50px; box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3); }
        .nav-item { width: calc(100% - 20px); padding: 16px 20px; margin-bottom: 10px; display: flex; align-items: center; color: var(--text-mute); text-decoration: none; border-radius: 16px; transition: 0.3s; white-space: nowrap; }
        .nav-item i { font-size: 20px; min-width: 25px; }
        .nav-item span { margin-left: 20px; opacity: 0; font-weight: 600; font-family: 'General Sans'; }
        .sidebar:hover .nav-item span { opacity: 1; }
        .nav-item:hover, .nav-item.active { background: white; color: var(--emerald-mid); box-shadow: 0 4px 12px rgba(0,0,0,0.03); }

        .main { margin-left: 130px; width: 100%; padding: 50px 60px 50px 20px; transition: 0.5s; }
        .page-header h1 { font-size: 48px; font-weight: 700; letter-spacing: -2px; line-height: 1; margin-bottom: 10px; }
        .bento-grid { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: 180px; gap: 25px; margin-top: 40px; }
        .bento-card { background: var(--glass); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 32px; padding: 30px; transition: all 0.4s ease; position: relative; overflow: hidden; box-shadow: var(--shadow-lux); }
        .bento-card:hover { transform: translateY(-10px); background: white; border-color: var(--emerald-mid); }
        .span-2 { grid-column: span 2; }
        .row-2 { grid-row: span 2; }
        .stat-label { font-family: 'General Sans'; font-size: 13px; font-weight: 600; color: var(--text-mute); text-transform: uppercase; letter-spacing: 1.5px; display: block; margin-bottom: 20px; }
        .stat-value { font-size: 64px; font-weight: 700; letter-spacing: -4px; color: var(--emerald-deep); line-height: 1; }
        .btn-lux { background: var(--emerald-deep); color: white; padding: 16px 30px; border-radius: 18px; text-decoration: none; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 12px; transition: 0.3s; }
        .btn-lux:hover { background: var(--emerald-mid); box-shadow: 0 15px 30px rgba(16, 185, 129, 0.25); }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="brand-icon">
            <i class="fa-solid fa-leaf"></i>
        </div>

        <a href="dashboard_supplier.php" class="nav-item active">
            <i class="fa-solid fa-compass"></i>
            <span>Ringkasan</span>
        </a>
        <a href="supplier_pesanan.php" class="nav-item">
            <i class="fa-solid fa-box-open"></i>
            <span>Kelola Pesanan</span>
            <?php if($pesananBaru > 0): ?>
                <div class="notif-badge"><?= $pesananBaru ?></div>
            <?php endif; ?>
        </a>
        <a href="supplier_rekomendasi.php" class="nav-item">
            <i class="fa-solid fa-vial-circle-check"></i>
            <span>Saran Pemupukan</span>
        </a>

        <a href="logout.php" class="nav-item" style="margin-top: auto; color: #f87171;">
            <i class="fa-solid fa-power-off"></i>
            <span>Keluar Aman</span>
        </a>
    </nav>

    <div class="main">
        <?php if($pesananBaru > 0): ?>
        <div class="alert-toast">
            <i class="fa-solid fa-bell fa-shake" style="color: var(--emerald-mid); font-size: 20px;"></i>
            <p style="font-weight: 600;">Notifikasi: Terdapat <?= $pesananBaru ?> pesanan bibit baru dari petani yang memerlukan tindakan.</p>
        </div>
        <?php endif; ?>

        <header class="page-header">
            <p style="color: var(--text-mute); font-weight: 500; margin-bottom: 5px;">Platform Intelijen Supplier</p>
            <h1>Pusat <span style="color: var(--emerald-mid);">Strategis.</span></h1>
        </header>

        <div class="bento-grid">
            <div class="bento-card span-2 row-2" style="background: white;">
                <span class="stat-label">Antrean Pesanan Petani</span>
                <div class="stat-value" style="font-size: 110px;"><?= $pesananBaru ?></div>
                <p style="color: var(--text-mute); margin-top: 20px; font-size: 18px; max-width: 80%;">
                    Pantau dan validasi pesanan masuk untuk menjaga stabilitas rantai pasok.
                </p>
                <div style="margin-top: 40px;">
                    <a href="supplier_pesanan.php" class="btn-lux">
                        <i class="fa-solid fa-arrow-right"></i> Kelola Pesanan Sekarang
                    </a>
                </div>
            </div>

            <div class="bento-card">
                <span class="stat-label">Transaksi Berhasil</span>
                <div class="stat-value"><?= $pesananSelesai ?></div>
                <p style="color: var(--emerald-mid); font-weight: 700; font-size: 11px; margin-top: 10px; letter-spacing: 1px;">
                    <i class="fa-solid fa-check-circle"></i> TOTAL DISTRIBUSI
                </p>
            </div>

            <div class="bento-card">
                <span class="stat-label">Saran Hari Ini</span>
                <div class="stat-value"><?= $saranHariIni ?></div>
                <p style="color: var(--text-mute); font-weight: 600; font-size: 11px; margin-top: 10px;">
                    DIKIRIM KE PETANI
                </p>
            </div>

            <div class="bento-card">
                <span class="stat-label">Profil Aktif</span>
                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <div style="width: 45px; height: 45px; background: var(--emerald-deep); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                        <?= strtoupper(substr($username, 0, 1)) ?>
                    </div>
                    <div>
                        <p style="font-weight: 700; font-size: 14px;"><?= $username ?></p>
                        <p style="font-size: 11px; color: var(--text-mute);">Premium Supplier</p>
                    </div>
                </div>
            </div>

            <div class="bento-card" style="background: var(--emerald-deep); border: none;">
                <span class="stat-label" style="color: rgba(255,255,255,0.6);">Aksi Rekomendasi</span>
                <p style="color: white; font-weight: 700; font-size: 14px;">Kirim Tips Pemupukan Ahli</p>
                <a href="supplier_rekomendasi.php" style="color: var(--emerald-mid); font-size: 28px; margin-top: 15px; display: block;">
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

</body>
</html>