<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: login.php");
    exit;
}

// Mengambil total stok yang sudah disetujui
$q_stok = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM pengajuan_panen WHERE status = 'disetujui'");
$res_stok = mysqli_fetch_assoc($q_stok);

// Mengambil jumlah antrean untuk notifikasi
$q_antrean = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_panen WHERE status = 'menunggu'");
$res_antrean = mysqli_fetch_assoc($q_antrean);
$total_notif = $res_antrean['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Terminal Distributor Elite</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1B4332;
            --mint: #D8F3DC;
            --accent: #74C69D;
            --white: #ffffff;
            --bg: #F0F4F8;
            --danger: #FF4D4D;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }

        body {
            background: var(--bg);
            background-image: 
                circle at 0% 0%, rgba(116, 198, 157, 0.1) 0%, transparent 40%,
                circle at 100% 100%, rgba(27, 67, 50, 0.05) 0%, transparent 40%;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Navigasi Melayang (Floating Pill) */
        nav {
            position: fixed;
            top: 50%;
            left: 40px;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            padding: 30px 15px;
            border-radius: 100px;
            border: 1px solid rgba(255,255,255,0.5);
            display: flex;
            flex-direction: column;
            gap: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.03);
            z-index: 100;
        }

        .nav-item {
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            text-decoration: none;
            transition: 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            position: relative;
        }

        .nav-item:hover, .nav-item.active {
            background: var(--primary);
            color: var(--white);
            transform: scale(1.2);
        }

        /* Badge Bulat Merah di Navigasi */
        .badge-nav {
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 900;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Container Utama */
        .wrapper {
            margin-left: 150px;
            padding: 60px 80px;
            width: 100%;
        }

        /* Alert Notifikasi Box */
        .alert-notif {
            background: var(--white);
            border-left: 6px solid var(--accent);
            padding: 20px 30px;
            border-radius: 20px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            animation: slideDown 0.5s ease-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-notif i { color: var(--accent); font-size: 24px; }
        .alert-notif p { font-weight: 700; color: var(--primary); }

        .header-section {
            margin-bottom: 80px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .header-section h1 {
            font-size: 72px;
            font-weight: 900;
            line-height: 0.8;
            letter-spacing: -3px;
            color: var(--primary);
            text-transform: uppercase;
        }

        .header-section span { color: var(--accent); }

        /* Stats flow */
        .stats-flow {
            display: flex;
            gap: 40px;
            margin-bottom: 80px;
        }

        .stat-blob {
            background: var(--white);
            padding: 40px;
            border-radius: 60px 20px 60px 20px;
            flex: 1;
            box-shadow: 20px 20px 60px rgba(0,0,0,0.02);
            border: 1px solid rgba(255,255,255,0.8);
            transition: 0.4s;
        }

        .stat-blob:hover { border-radius: 20px 60px 20px 60px; transform: translateY(-10px); border-color: var(--accent); }
        .stat-blob label { font-size: 12px; font-weight: 700; color: #999; letter-spacing: 2px; text-transform: uppercase; }
        .stat-blob h2 { font-size: 48px; font-weight: 900; color: var(--primary); margin-top: 10px; }

        /* Data Stream */
        .data-stream { width: 100%; }
        .stream-item {
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
            padding: 30px 40px;
            border-radius: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255,255,255,0.5);
            transition: 0.3s;
        }

        .stream-item:hover { background: var(--white); box-shadow: 0 30px 60px rgba(0,0,0,0.04); }
        .f-info h4 { font-size: 20px; font-weight: 800; color: var(--primary); }
        .f-info p { font-size: 13px; color: #777; margin-top: 5px; }

        .product-tag {
            background: var(--mint);
            padding: 10px 25px;
            border-radius: 100px;
            font-weight: 700;
            font-size: 12px;
            color: var(--primary);
        }

        .action-pill { display: flex; gap: 15px; }

        .btn {
            padding: 15px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 800;
            font-size: 12px;
            text-transform: uppercase;
            transition: 0.3s;
        }

        .btn-yes { background: var(--primary); color: white; box-shadow: 0 10px 20px rgba(27, 67, 50, 0.2); }
        .btn-no { border: 1px solid #eee; color: var(--danger); }
        .btn-yes:hover { transform: scale(1.05); background: var(--accent); }
        .btn-no:hover { background: #FFF5F5; }

        .empty { font-size: 32px; font-weight: 900; opacity: 0.1; padding: 100px 0; text-align: center; }
    </style>
</head>
<body>

    <nav>
        <a href="dashboard_distributor.php" class="nav-item active">
            <i class="fa-solid fa-house"></i>
            <?php if($total_notif > 0): ?>
                <div class="badge-nav"><?= $total_notif ?></div>
            <?php endif; ?>
        </a>
        <a href="riwayat_distribusi.php" class="nav-item">
            <i class="fa-solid fa-clock-rotate-left"></i>
        </a>
        <a href="#" class="nav-item">
            <i class="fa-solid fa-chart-pie"></i>
        </a>
        <a href="logout.php" class="nav-item" style="margin-top: auto; color: var(--danger);">
            <i class="fa-solid fa-power-off"></i>
        </a>
    </nav>

    <div class="wrapper">
        <?php if($total_notif > 0): ?>
        <div class="alert-notif">
            <i class="fa-solid fa-circle-exclamation"></i>
            <p>Perhatian! Ada <?= $total_notif ?> pengajuan baru dari petani yang menunggu verifikasi Anda.</p>
        </div>
        <?php endif; ?>

        <header class="header-section">
            <div>
                <p style="font-weight: 800; color: var(--accent); margin-bottom: 15px;">DASHBOARD // OPERATOR</p>
                <h1>Elite<br>Distri<span>.</span></h1>
            </div>
            <div style="text-align: right;">
                <p style="font-size: 14px; font-weight: 700;">USER: <?= strtoupper($_SESSION['username'] ?? 'DISTRIBUTOR') ?></p>
                <p style="color: #999; font-size: 12px;">SCM AGRO SYSTEM v.4.0</p>
            </div>
        </header>

        <div class="stats-flow">
            <div class="stat-blob">
                <label>Inventory Approved</label>
                <h2><?= number_format($res_stok['total'] ?? 0) ?> <span style="font-size: 18px; opacity: 0.3;">KG</span></h2>
            </div>
            <div class="stat-blob" style="background: var(--primary); color: white;">
                <label style="color: rgba(255,255,255,0.5);">Incoming Queue</label>
                <h2 style="color: white;"><?= $total_notif ?> <span style="font-size: 18px; opacity: 0.3;">REQ</span></h2>
            </div>
        </div>

        <div class="data-stream">
            <p style="font-weight: 800; font-size: 12px; letter-spacing: 3px; color: #ccc; margin-bottom: 30px;">PENDING VERIFICATION</p>

            <?php
            $q_data = mysqli_query($conn, "SELECT p.*, u.nama as nama_petani 
                                          FROM pengajuan_panen p 
                                          JOIN users u ON p.id_petani = u.id 
                                          WHERE p.status = 'menunggu' 
                                          ORDER BY p.id_panen DESC");
            
            if(mysqli_num_rows($q_data) == 0): ?>
                <div class="empty">SYSTEM_CLEAR</div>
            <?php 
            else:
                while($row = mysqli_fetch_assoc($q_data)):
                ?>
                <div class="stream-item">
                    <div class="f-info">
                        <p>PETANI / ID#<?= $row['id_petani'] ?></p>
                        <h4><?= $row['nama_petani'] ?></h4>
                    </div>
                    
                    <div class="product-tag">
                        <i class="fa-solid fa-seedling"></i> <?= $row['nama_hasil'] ?>
                    </div>

                    <div style="text-align: center;">
                        <p style="font-size: 10px; font-weight: 700; color: #ccc;">QUANTITY</p>
                        <h3 style="font-weight: 900; font-size: 24px;"><?= $row['jumlah'] ?> kg</h3>
                    </div>

                    <div class="action-pill">
                        <a href="verifikasi_aksi(distri).php?id=<?= $row['id_panen'] ?>&status=disetujui" class="btn btn-yes">Approve</a>
                        <a href="verifikasi_aksi(distri).php?id=<?= $row['id_panen'] ?>&status=ditolak" class="btn btn-no">Decline</a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>