<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'buruh') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work Logs // Riwayat Buruh</title>
    
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --obsidian: #022c22;
            --emerald: #10b981;
            --slate: #f8fafc;
            --glass: rgba(255, 255, 255, 0.8);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Satoshi', sans-serif; }

        body {
            background: var(--slate);
            background-image: radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
            min-height: 100vh;
            padding: 60px 80px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .header h1 { font-size: 42px; font-weight: 900; letter-spacing: -2px; color: var(--obsidian); }

        .btn-back {
            text-decoration: none;
            color: var(--emerald);
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .btn-back:hover { transform: translateX(-5px); }

        .history-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .history-card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 35px;
            overflow: hidden;
            transition: 0.4s;
        }

        .history-card:hover {
            transform: translateY(-10px);
            background: white;
            box-shadow: 0 30px 60px -15px rgba(0,0,0,0.05);
        }

        .img-container {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background: #eee;
        }

        .img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s;
        }

        .card-body { padding: 30px; }

        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .badge-pending { background: #fef3c7; color: #b45309; }
        .badge-approved { background: #d1fae5; color: #065f46; }

        .card-body h3 { font-size: 22px; font-weight: 900; color: var(--obsidian); margin-bottom: 5px; }

        .meta-info {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .meta-val { font-weight: 800; color: var(--obsidian); }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <a href="dashboard_buruh.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> BACK TO COMMAND
            </a>
            <h1>Riwayat <span style="color: var(--emerald);">Aktivitas.</span></h1>
        </div>
    </div>

    <div class="history-grid">
        <?php
        // Query disesuaikan dengan database scm_pertanian2
        $query = mysqli_query($conn, "SELECT p.*, u.nama as nama_petani 
                                     FROM pengajuan_panen p 
                                     JOIN users u ON p.id_petani = u.id 
                                     ORDER BY p.id_panen DESC");
        
        while($row = mysqli_fetch_assoc($query)):
            $status_class = ($row['status'] == 'menunggu') ? 'badge-pending' : 'badge-approved';
        ?>
        <div class="history-card">
            <div class="img-container">
                <?php if(!empty($row['foto'])): ?>
                    <img src="uploads/<?= $row['foto'] ?>" alt="Dokumentasi">
                <?php else: ?>
                    <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: #cbd5e1;">
                        <i class="fa-solid fa-image fa-3x"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <span class="badge <?= $status_class ?>"><?= $row['status'] ?></span>
                <h3><?= $row['nama_hasil'] ?></h3>
                <p style="font-size: 13px; color: #64748b;"><i class="fa-solid fa-user-tie"></i> Lahan: <?= $row['nama_petani'] ?></p>
                
                <div class="meta-info">
                    <div>
                        <p style="font-size: 11px; color: #94a3b8;">VOLUME</p>
                        <span class="meta-val"><?= $row['jumlah'] ?> KG</span>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 11px; color: #94a3b8;">UNIT</p>
                        <span class="meta-val" style="color: var(--emerald);">VERIFIED</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

</body>
</html>