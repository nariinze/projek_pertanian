<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Log Distribusi // Elite Distri.</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@300;400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --primary: #1B4332; --mint: #D8F3DC; --accent: #74C69D; --white: #ffffff; --bg: #F0F4F8; --danger: #FF4D4D; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }
        body { background: var(--bg); display: flex; }
        
        nav { position: fixed; top: 50%; left: 40px; transform: translateY(-50%); background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); padding: 30px 15px; border-radius: 100px; border: 1px solid rgba(255,255,255,0.5); display: flex; flex-direction: column; gap: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.03); z-index: 100; }
        .nav-item { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); text-decoration: none; transition: 0.5s; }
        .nav-item:hover, .nav-item.active { background: var(--primary); color: var(--white); transform: scale(1.2); }

        .wrapper { margin-left: 150px; padding: 60px 80px; width: 100%; }
        .header-section h1 { font-size: 54px; font-weight: 900; letter-spacing: -2px; color: var(--primary); text-transform: uppercase; margin-bottom: 50px;}

        .history-item { background: rgba(255,255,255,0.6); backdrop-filter: blur(10px); margin-bottom: 15px; padding: 25px 40px; border-radius: 25px 50px 25px 50px; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; align-items: center; border: 1px solid rgba(255,255,255,0.5); transition: 0.4s; }
        .history-item:hover { transform: translateX(10px); background: var(--white); border-color: var(--accent); }

        .data-label { font-size: 10px; font-weight: 800; color: #bbb; text-transform: uppercase; margin-bottom: 5px; }
        .data-value { font-size: 18px; font-weight: 700; color: var(--primary); }
        .status-badge { padding: 8px 20px; border-radius: 50px; font-size: 11px; font-weight: 900; text-transform: uppercase; }
        .status-disetujui { background: var(--mint); color: var(--primary); }
        .status-ditolak { background: #FFE5E5; color: var(--danger); }
        .status-badge {
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
}

/* Warna Hijau Elite untuk Disetujui */
.status-disetujui { 
    background: #D8F3DC; 
    color: #1B4332; 
}

/* Warna Merah Elite untuk Ditolak */
.status-ditolak { 
    background: #FFE5E5; 
    color: #FF4D4D; 
}
    </style>
</head>
<body>

    <nav>
        <a href="dashboard_distributor.php" class="nav-item"><i class="fa-solid fa-house"></i></a>
        <a href="riwayat_distribusi.php" class="nav-item active"><i class="fa-solid fa-clock-rotate-left"></i></a>
        <a href="logout.php" class="nav-item" style="margin-top: auto; color: var(--danger);"><i class="fa-solid fa-power-off"></i></a>
    </nav>

    <div class="wrapper">
        <header class="header-section">
            <p style="font-weight: 800; color: var(--accent); margin-bottom: 10px;">ARCHIVE // PB-04</p>
            <h1>History Log<span>.</span></h1>
        </header>

       <div class="history-list">
    <?php
    // Ambil data yang statusnya BUKAN menunggu
    $query = mysqli_query($conn, "SELECT p.*, u.nama as nama_petani 
                                  FROM pengajuan_panen p 
                                  JOIN users u ON p.id_petani = u.id 
                                  WHERE p.status != 'menunggu' 
                                  ORDER BY p.id_panen DESC");

    while ($row = mysqli_fetch_assoc($query)):
        // Penentuan Class Warna
        $status_raw = strtolower($row['status']);
        if ($status_raw == 'disetujui') {
            $class_warna = "status-disetujui";
        } else {
            $class_warna = "status-ditolak";
        }
    ?>
    <div class="history-item">
        <div>
            <p class="data-label">Origin Farmer</p>
            <p class="data-value"><?= $row['nama_petani'] ?></p>
        </div>

        <div>
            <p class="data-label">Commodity</p>
            <p class="data-value" style="color: var(--accent);"><?= $row['nama_hasil'] ?></p>
        </div>

        <div>
            <p class="data-label">Quantity</p>
            <p class="data-value"><?= number_format($row['jumlah']) ?> KG</p>
        </div>

        <div style="text-align: right;">
            <p class="data-label">Final Decision</p>
            <span class="status-badge <?= $class_warna ?>">
                <?= strtoupper($row['status']) ?>
            </span>
        </div>
    </div>
    <?php endwhile; ?>
        </div>
    </div>
</body>
</html>