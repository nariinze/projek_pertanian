<?php
session_start();
include "koneksi.php";

// 1. Proteksi Halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];

// 2. Logika Update Status
if (isset($_POST['update_status'])) {
    $id_pesanan = $_POST['id_pesanan'];
    $status_baru = $_POST['status'];
    
    // Update ke tabel pesanan_bibit
    // Pastikan nama kolom ID adalah 'id_pesanan' sesuai query tampil data
    $update = mysqli_query($conn, "UPDATE pesanan_bibit SET status='$status_baru' WHERE id_pesanan='$id_pesanan'");
    
    if ($update) {
        $notif = "Status pesanan #$id_pesanan berhasil diperbarui!";
    } else {
        $notif = "Gagal memperbarui status: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pesanan | SCM Agro</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --emerald: #10b981; --dark: #064e3b; }
        body { font-family: 'Satoshi', sans-serif; background: #f8fafc; margin: 0; display: flex; }
        .sidebar { width: 260px; background: white; height: 100vh; padding: 40px 20px; border-right: 1px solid #e2e8f0; position: fixed; }
        .main { margin-left: 260px; padding: 60px; width: calc(100% - 260px); }
        .card { background: white; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .badge { padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        
        /* Warna dinamis berdasarkan status */
        .status-menunggu, .status-Menunggu { background: #fee2e2; color: #ef4444; }
        .status-diproses, .status-Diproses { background: #fef3c7; color: #f59e0b; }
        .status-selesai, .status-Selesai { background: #ecfdf5; color: #10b981; }
        
        select, button { padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; }
        button { background: var(--dark); color: white; cursor: pointer; border: none; font-weight: bold; transition: 0.3s; }
        button:hover { background: var(--emerald); }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2 style="color: var(--emerald); margin-bottom: 30px;"><i class="fa-solid fa-leaf"></i> SCM Agro</h2>
        <p style="color: #94a3b8; font-size: 12px; font-weight: bold; text-transform: uppercase;">Menu Utama</p>
        <a href="dashboard_supplier.php" style="display:block; text-decoration:none; color:#64748b; margin-bottom:15px;"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="supplier_pesanan.php" style="display:block; text-decoration:none; color:var(--emerald); font-weight:bold; margin-bottom:15px;"><i class="fa-solid fa-cart-shopping"></i> Kelola Pesanan</a>
        <a href="logout.php" style="display:block; text-decoration:none; color:#ef4444; margin-top:50px;"><i class="fa-solid fa-power-off"></i> Keluar</a>
    </div>

    <div class="main">
        <h1>Pesanan <span style="color: var(--emerald);">Masuk.</span></h1>
        
        <?php if(isset($notif)): ?>
            <div style="background: #ecfdf5; color: #10b981; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #10b981;">
                <?= $notif ?>
            </div>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <?php
            // Query mengambil data dari pesanan_bibit join dengan users dan produk
            $sql = "SELECT pb.*, u.username, p.nama_produk 
                    FROM pesanan_bibit pb 
                    JOIN users u ON pb.id_petani = u.id 
                    JOIN produk p ON pb.id_produk = p.id_produk 
                    ORDER BY pb.id_pesanan DESC";
            $query = mysqli_query($conn, $sql);

            if (!$query) {
                die("Query Error: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($query) == 0) {
                echo "<p style='color: #94a3b8;'>Belum ada pesanan.</p>";
            }

            while($row = mysqli_fetch_assoc($query)) {
                $status = $row['status'];
            ?>
            <div class="card">
                <div>
                    <span class="badge status-<?= $status ?>">#AGRO-<?= $row['id_pesanan'] ?> | <?= $status ?></span>
                    <h3 style="margin: 10px 0 5px 0;"><?= $row['nama_produk'] ?></h3>
                    <p style="color: #64748b; font-size: 14px; margin: 0;">
                        Pemesan: <b><?= $row['username'] ?></b> | Jumlah: <b><?= $row['jumlah'] ?></b>
                    </p>
                </div>

                <form method="POST" style="display: flex; gap: 10px;">
                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                    <select name="status">
                        <option value="menunggu" <?= (strtolower($status) == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                        <option value="diproses" <?= (strtolower($status) == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                        <option value="selesai" <?= (strtolower($status) == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                    </select>
                    <button type="submit" name="update_status">Update</button>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>

</body>
</html>