<?php
session_start();
include "koneksi.php";

// Proteksi halaman, pastikan hanya distributor yang bisa masuk
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: login.php");
    exit;
}

$id_distributor = $_SESSION['id'];

// Ambil semua data transaksi pembelian dari database
$query_transaksi = mysqli_query($conn, "
    SELECT tp.*, p.nama_hasil, p.jumlah, u.nama as nama_petani 
    FROM transaksi_pembelian tp
    JOIN pengajuan_panen p ON tp.id_panen = p.id_panen
    JOIN users u ON tp.id_petani = u.id
    WHERE tp.id_distributor = '$id_distributor'
    ORDER BY tp.id_transaksi DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Logistics & Tracking // Elite Distri.</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #1B4332;
            --mint: #D8F3DC;
            --accent: #74C69D;
            --bg: #F0F4F8;
            --white: #ffffff;
            --dark: #2d3748;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }
        body { background: var(--bg); padding: 50px 80px; min-height: 100vh; }

        /* Header Style */
        .header { margin-bottom: 50px; display: flex; justify-content: space-between; align-items: center; }
        .header h1 { font-size: 38px; font-weight: 900; color: var(--primary); letter-spacing: -1px; }
        .btn-back { text-decoration: none; color: var(--primary); font-weight: 800; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .btn-back:hover { color: var(--accent); }

        /* Table Card Container */
        .table-container {
            background: var(--white);
            border-radius: 30px;
            padding: 35px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.02);
            overflow-x: auto;
        }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { padding: 20px 25px; font-weight: 800; font-size: 12px; color: #a0aec0; text-transform: uppercase; letter-spacing: 1px; border-bottom: 2px solid #f7fafc; }
        td { padding: 25px; border-bottom: 1px solid #f7fafc; font-size: 15px; color: var(--dark); vertical-align: middle; }
        tr:hover td { background: #fcfdfd; }

        /* Badge Status Logistics */
        .badge-status {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-menunggu { background: #FFF5F5; color: #FF4D4D; }
        .status-proses { background: #FFF9E6; color: #FFB800; }
        .status-kirim { background: #EBF8FF; color: #3182CE; }
        .status-selesai { background: var(--mint); color: var(--primary); }

        /* Action Button */
        .btn-update {
            background: var(--primary);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(27, 67, 50, 0.1);
        }
        .btn-update:hover { background: var(--accent); color: var(--primary); transform: translateY(-2px); }
        .disabled-btn { background: #e2e8f0; color: #a0aec0; cursor: not-allowed; box-shadow: none; }
        .disabled-btn:hover { background: #e2e8f0; color: #a0aec0; transform: none; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <a href="dashboard_distributor.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> BACK TO DASHBOARD</a>
            <h1 style="margin-top: 10px;">Transaction <span style="color: var(--accent);">Logistics.</span></h1>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 12px; font-weight: 800; color: #999; letter-spacing: 1px;">SPRINT 10 // FINALIZE</p>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID Nota</th>
                    <th>Farmer (Petani)</th>
                    <th>Commodity</th>
                    <th>Total Weight</th>
                    <th>Total Deal</th>
                    <th>Logistics Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (mysqli_num_rows($query_transaksi) == 0) : 
                    echo "<tr><td colspan='7' style='text-align:center; padding: 50px; color: #a0aec0; font-weight: 700;'>Belum ada transaksi pembelian.</td></tr>";
                else :
                    while ($row = mysqli_fetch_assoc($query_transaksi)) : 
                        $status = $row['status_logistik'];
                ?>
                <tr>
                    <td style="font-weight: 800; color: var(--primary);">#TX-<?= $row['id_transaksi'] ?></td>
                    <td>
                        <span style="font-weight: 700; display: block;"><?= $row['nama_petani'] ?></span>
                        <small style="color: #a0aec0; font-size: 11px;">ID Petani: <?= $row['id_petani'] ?></small>
                    </td>
                    <td style="font-weight: 600;"><i class="fa-solid fa-seedling" style="color: var(--accent); margin-right: 5px;"></i> <?= $row['nama_hasil'] ?></td>
                    <td style="font-weight: 700;"><?= number_format($row['jumlah']) ?> kg</td>
                    <td style="font-weight: 800; color: var(--primary);">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                    <td>
                        <span class="badge-status status-<?= strtolower($status) ?>">
                            <i class="fa-solid fa-circle-dot" style="font-size: 8px; margin-right: 5px;"></i> <?= $status ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($status != 'Selesai') : ?>
                            <form action="update_status_logistik.php" method="POST">
                                <input type="hidden" name="id" value="<?= $row['id_transaksi'] ?>">
                                <button type="submit" class="btn-update">
                                    <i class="fa-solid fa-truck-ramp-box"></i> Next Step
                                </button>
                            </form>
                        <?php else : ?>
                            <button class="btn-update disabled-btn" disabled>
                                <i class="fa-solid fa-circle-check"></i> Delivered
                            </button>
                        <?php endif; ?>
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