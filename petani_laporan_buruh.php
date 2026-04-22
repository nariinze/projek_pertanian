<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Field Intelligence // Laporan Lahan</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --obsidian: #022c22; --emerald: #10b981; --slate: #f8fafc; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Satoshi', sans-serif; }
        
        body { 
            background: var(--slate); 
            padding: 60px 80px;
            background-image: radial-gradient(at 100% 0%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
        }

        .header { margin-bottom: 50px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 42px; font-weight: 900; color: var(--obsidian); letter-spacing: -2px; }
        
        .btn-back { text-decoration: none; color: var(--emerald); font-weight: 800; font-size: 13px; display: flex; align-items: center; gap: 10px; transition: 0.3s; }
        .btn-back:hover { transform: translateX(-5px); }

        .report-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
        
        .report-card { 
            background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); 
            border: 1px solid rgba(0,0,0,0.05); border-radius: 35px; 
            overflow: hidden; transition: 0.4s; 
        }
        
        .report-card:hover { transform: translateY(-10px); background: white; box-shadow: 0 30px 60px -20px rgba(0,0,0,0.1); }
        
        .img-box { width: 100%; height: 220px; background: #eee; position: relative; overflow: hidden; }
        .img-box img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .report-card:hover img { transform: scale(1.1); }

        .badge-status { 
            position: absolute; top: 20px; left: 20px; padding: 6px 15px; border-radius: 12px; 
            font-size: 10px; font-weight: 900; color: white; text-transform: uppercase; 
        }

        .card-body { padding: 30px; }
        .card-body h3 { font-size: 22px; font-weight: 900; color: var(--obsidian); margin-bottom: 5px; }
        .card-body p { color: #64748b; font-size: 14px; margin-bottom: 20px; }

        .footer-info { 
            border-top: 1px solid rgba(0,0,0,0.05); padding-top: 20px;
            display: flex; justify-content: space-between; align-items: center;
        }

        .weight-box span { display: block; font-size: 10px; color: #94a3b8; font-weight: 800; }
        .weight-box b { font-size: 20px; color: var(--obsidian); }

        .btn-val { 
            background: var(--obsidian); color: white; text-decoration: none; 
            padding: 12px 20px; border-radius: 15px; font-size: 11px; font-weight: 800; 
            transition: 0.3s;
        }
        .btn-val:hover { background: var(--emerald); box-shadow: 0 10px 20px rgba(16, 185, 129, 0.2); }

        .empty { text-align: center; grid-column: span 3; padding: 100px; color: #94a3b8; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <a href="dashboard_petani.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> BACK TO DASHBOARD</a>
            <h1>Laporan <span style="color: var(--emerald);">Lahan.</span></h1>
        </div>
    </div>

    <div class="report-grid">
        <?php
        $query = mysqli_query($conn, "SELECT * FROM pengajuan_panen WHERE id_petani = '$id_user' ORDER BY id_panen DESC");
        
        if(mysqli_num_rows($query) > 0):
            while($row = mysqli_fetch_assoc($query)):
                $st_color = ($row['status'] == 'menunggu') ? '#f59e0b' : '#10b981';
        ?>
        <div class="report-card">
            <div class="img-box">
                <span class="badge-status" style="background: <?= $st_color ?>;"><?= $row['status'] ?></span>
                <?php if(!empty($row['foto'])): ?>
                    <img src="uploads/<?= $row['foto'] ?>" alt="Dokumentasi">
                <?php else: ?>
                    <div style="display:flex; align-items:center; justify-content:center; height:100%; color:#cbd5e1;"><i class="fa-solid fa-image fa-3x"></i></div>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <h3><?= $row['nama_hasil'] ?></h3>
                <p>Verifikasi input buruh berdasarkan bukti visual di lapangan.</p>
                
                <div class="footer-info">
                    <div class="weight-box">
                        <span>VOLUME BERAT</span>
                        <b><?= $row['jumlah'] ?> KG</b>
                    </div>
                    <?php if($row['status'] == 'menunggu'): ?>
                        <a href="proses_validasi_buruh.php?id=<?= $row['id_panen'] ?>" 
                           class="btn-val" 
                           onclick="return confirm('Apakah data panen ini sudah sesuai?')">
                           VALIDASI
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php 
            endwhile;
        else: 
        ?>
            <div class="empty">
                <i class="fa-solid fa-folder-open fa-3x" style="margin-bottom: 20px; display:block;"></i>
                <p>Belum ada laporan buruh yang masuk.</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>