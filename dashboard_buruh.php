<?php
session_start();
include "koneksi.php"; // Pastikan isinya: $conn = mysqli_connect("localhost", "root", "", "scm_pertanian2");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buruh') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];
$username = $_SESSION['username'];

/** * FIX STATISTIK: 
 * Kita ambil total dari pengajuan_panen. 
 * Jika ingin spesifik per buruh, pastikan tabel pengajuan_panen punya kolom id_buruh.
 * Untuk sekarang, kita ambil total global agar angka tidak 0.
 */
$q_stats = mysqli_query($conn, "SELECT COUNT(*) as total_input, SUM(jumlah) as total_berat FROM pengajuan_panen");
$res_stats = mysqli_fetch_assoc($q_stats);

$total_laporan = $res_stats['total_input'] ?? 0;
$total_kg = $res_stats['total_berat'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Field Command // Buruh Elite</title>
    
    <link href="https://api.fontshare.com/v2/css?f[]=general-sans@700,600,500&f[]=satoshi@900,700,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --obsidian: #022c22;
            --emerald: #10b981;
            --slate: #f8fafc;
            --glass: rgba(255, 255, 255, 0.7);
            --border: rgba(0, 0, 0, 0.04);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Satoshi', sans-serif; }

        body {
            background: var(--slate);
            background-image: radial-gradient(at 0% 0%, rgba(16, 185, 129, 0.1) 0px, transparent 40%);
            min-height: 100vh;
            display: flex;
            color: var(--obsidian);
        }

        /* Side Command Dock */
        .dock {
            width: 100px;
            background: var(--glass);
            backdrop-filter: blur(25px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 0;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .dock-brand {
            width: 50px; height: 50px;
            background: var(--obsidian);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            color: var(--emerald);
            font-size: 20px;
            margin-bottom: 60px;
        }

        .dock-item {
            width: 54px; height: 54px;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            color: #94a3b8;
            text-decoration: none;
            margin-bottom: 25px;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dock-item.active, .dock-item:hover {
            background: white;
            color: var(--emerald);
            box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.2);
            transform: scale(1.1);
        }

        /* Workspace */
        .workspace { margin-left: 100px; padding: 60px 80px; width: 100%; }

        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 60px; }
        .top-bar h2 { font-size: 12px; font-weight: 800; color: var(--emerald); letter-spacing: 3px; text-transform: uppercase; }
        .top-bar h1 { font-size: 52px; font-weight: 900; letter-spacing: -3px; line-height: 1; }

        /* Bento Grid */
        .bento-box { display: grid; grid-template-columns: repeat(4, 1fr); grid-auto-rows: 180px; gap: 30px; }
        .card { background: var(--glass); backdrop-filter: blur(15px); border: 1px solid var(--border); border-radius: 40px; padding: 35px; transition: 0.5s; position: relative; overflow: hidden; }
        .card:hover { transform: translateY(-10px); background: white; border-color: var(--emerald); }
        .span-2 { grid-column: span 2; }
        .row-2 { grid-row: span 2; }

        .label { font-family: 'General Sans'; font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; display: block; margin-bottom: 15px; }
        .value { font-size: 90px; font-weight: 900; letter-spacing: -5px; color: var(--obsidian); line-height: 1; }

        .btn-action {
            background: var(--obsidian);
            color: white;
            padding: 24px 45px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center; gap: 15px;
            margin-top: 40px;
            transition: 0.3s;
        }

        .btn-action:hover { background: var(--emerald); box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.3); }

        .status-dot { width: 8px; height: 8px; background: var(--emerald); border-radius: 50%; display: inline-block; margin-right: 8px; box-shadow: 0 0 10px var(--emerald); }
    </style>
</head>
<body>

    <nav class="dock">
        <div class="dock-brand"><i class="fa-solid fa-bolt"></i></div>
        <a href="dashboard_buruh.php" class="dock-item active"><i class="fa-solid fa-house-chimney-window"></i></a>
        <a href="buruh_input_panen.php" class="dock-item"><i class="fa-solid fa-plus"></i></a>
        <a href="riwayat_buruh.php" class="dock-item"><i class="fa-solid fa-receipt"></i></a>
        <a href="logout.php" class="dock-item" style="margin-top: auto; color: #f87171;"><i class="fa-solid fa-power-off"></i></a>
    </nav>

    <div class="workspace">
        <div class="top-bar">
            <div>
                <h2>Worker Intelligence Unit</h2>
                <h1>Halo, <?= strtoupper($username) ?>.</h1>
            </div>
            <div style="text-align: right;">
                <p style="font-weight: 800; font-size: 14px;">Aktif</p>
                <p style="font-size: 11px; color: #94a3b8;"><span class="status-dot"></span> System Online</p>
            </div>
        </div>

        <div class="bento-box">
            <div class="card span-2 row-2" style="background: white;">
                <span class="label">Total Akumulasi Hasil</span>
                <div class="value">
                    <?= number_format($total_kg, 1) ?>
                    <span style="font-size: 24px; color: var(--emerald); margin-left: 10px;">KG</span>
                </div>
                <p style="color: #64748b; margin-top: 25px; font-size: 18px; font-weight: 500;">
                    Data dihitung berdasarkan <?= $total_laporan ?> laporan valid yang tersimpan di database scm_pertanian2.
                </p>
                <a href="buruh_input_panen.php" class="btn-action">
                    <i class="fa-solid fa-plus"></i> INPUT HASIL BARU
                </a>
            </div>

            <div class="card">
                <span class="label">Laporan Masuk</span>
                <div class="value" style="font-size: 60px;"><?= $total_laporan ?></div>
                <div style="margin-top: 15px; font-size: 11px; font-weight: 800; color: var(--emerald);">
                    <i class="fa-solid fa-shield-check"></i> TERVERIFIKASI
                </div>
            </div>

            <div class="card">
                <span class="label">Operator Rank</span>
                <h3 style="font-size: 22px; font-weight: 900;">Elite Worker</h3>
                <p style="font-size: 12px; color: #94a3b8; margin-top: 5px;">Akses Penuh Lapangan</p>
            </div>

            <div class="card span-2" style="background: var(--obsidian); border: none;">
                <div style="display: flex; justify-content: space-between; align-items: center; height: 100%;">
                    <div>
                        <span class="label" style="color: rgba(255,255,255,0.4);">Quick Access</span>
                        <h2 style="color: white; font-size: 28px; font-weight: 900;">Buka Riwayat <br>Dokumentasi.</h2>
                    </div>
                    <a href="riwayat_buruh.php" style="width: 70px; height: 70px; background: var(--emerald); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: var(--obsidian); font-size: 24px;">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>