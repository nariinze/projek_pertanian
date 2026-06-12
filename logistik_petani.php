<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: index.php");
    exit;
}

$id_petani = $_SESSION['id'];

// Mengambil data logistik pengiriman hasil panen milik petani yang login
$query_logistik = mysqli_query($conn, "
    SELECT tp.*, p.nama_hasil, p.jumlah, u.nama as nama_distributor 
    FROM transaksi_pembelian tp
    JOIN pengajuan_panen p ON tp.id_panen = p.id_panen
    JOIN users u ON tp.id_distributor = u.id
    WHERE tp.id_petani = '$id_petani'
    ORDER BY tp.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logistics & Tracking // SCM Agro.</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --primary: #0F5C4C; --mint: #D8F3DC; --accent: #74C69D; --bg: #F0F4F8; --white: #ffffff; --dark: #2d3748; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: var(--bg); padding: 50px 80px; min-height: 100vh; }

        .header { margin-bottom: 50px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 38px; font-weight: 900; color: var(--primary); letter-spacing: -1px; }
        .btn-back { text-decoration: none; color: var(--primary); font-weight: 800; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .btn-back:hover { color: var(--accent); }

        .table-container { background: var(--white); border-radius: 30px; padding: 35px; box-shadow: 0 20px 50px rgba(0,0,0,0.02); overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 20px 25px; font-weight: 800; font-size: 12px; color: #a0aec0; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f7fafc; }
        td { padding: 25px; border-bottom: 1px solid #f7fafc; font-size: 15px; color: var(--dark); vertical-align: middle; }
        tr:hover td { background: #fcfdfd; }

        .badge-status { display: inline-block; padding: 8px 18px; border-radius: 50px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
        .status-menunggu { background: #FFF5F5; color: #FF4D4D; }
        .status-proses { background: #FFF9E6; color: #FFB800; }
        .status-kirim { background: #EBF8FF; color: #3182CE; }
        .status-selesai { background: var(--mint); color: var(--primary); }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <a href="dashboard_petani.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> BACK TO DASHBOARD</a>
            <h1 style="margin-top: 10px;">Logistics & <span style="color: #F4C430;">Tracking.</span></h1>
        </div>
        <p style="font-size: 12px; font-weight: 800; color: #999;">SPRINT 10 // FARMER DISTRIBUTION VIEW</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Nota</th>
                    <th>Buyer (Distributor)</th>
                    <th>Commodity</th>
                    <th>Total Weight</th>
                    <th>Total Deal</th>
                    <th>Logistics Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($query_logistik) == 0) : 
                    echo "<tr><td colspan='6' style='text-align:center; padding: 40px; color: #a0aec0; font-weight: 600;'>Belum ada armada atau data distribusi aktif saat ini.</td></tr>";
                else :
                    while ($row = mysqli_fetch_assoc($query_logistik)) : 
                        $status = $row['status_logistik'] ?? 'Menunggu';
                ?>
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">#TX-<?= $row['id_transaksi'] ?></td>
                    <td>
                        <span style="font-weight: 700; display: block;"><?= $row['nama_distributor'] ?></span>
                        <small style="color: #a0aec0; font-size: 11px;">ID Buyer: <?= $row['id_distributor'] ?></small>
                    </td>
                    <td style="font-weight: 600;"><i class="fa-solid fa-truck-ramp-box" style="color: var(--primary); margin-right: 5px;"></i> <?= $row['nama_hasil'] ?></td>
                    <td style="font-weight: 700;"><?= number_format($row['jumlah']) ?> kg</td>
                    <td style="font-weight: 800; color: var(--primary);">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge-status status-<?= strtolower($status) ?>">
                            <i class="fa-solid fa-circle-dot" style="font-size: 8px; margin-right: 5px;"></i> <?= $status ?>
                        </span>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                endif; 
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>