<?php
session_start();
include "koneksi.php"; // Menggunakan koneksi utama ke scm_pertanian

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'supplier') {
    header("Location: login.php");
    exit;
}

// --- LOGIKA SIMPAN SARAN ---
if (isset($_POST['kirim_saran'])) {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi   = mysqli_real_escape_string($conn, $_POST['isi_saran']);
    $tgl   = date('Y-m-d');

    // Query masuk ke tabel saran_pemupukan
    $query = "INSERT INTO saran_pemupukan (judul, isi, tanggal) VALUES ('$judul', '$isi', '$tgl')";
    
    if (mysqli_query($conn, $query)) {
        $msg = "Saran pemupukan berhasil dipublikasikan!";
    }
}

// --- LOGIKA HAPUS SARAN ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM saran_pemupukan WHERE id = '$id'");
    header("Location: supplier_rekomendasi.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Saran Pemupukan | Supplier</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@700,500,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --emerald: #10b981; --dark: #064e3b; }
        body { font-family: 'Satoshi', sans-serif; background: #f8fafc; margin: 0; display: flex; }
        .sidebar { width: 90px; background: white; height: 100vh; padding: 30px 0; border-right: 1px solid #e2e8f0; position: fixed; display: flex; flex-direction: column; align-items: center; }
        .sidebar a { color: #64748b; font-size: 20px; margin-bottom: 30px; text-decoration: none; }
        .sidebar a.active { color: var(--emerald); }
        .main { margin-left: 90px; padding: 50px; width: 100%; }
        .card { background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        input, textarea { width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .btn { background: var(--dark); color: white; border: none; padding: 15px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; width: 100%; }
        .saran-item { background: white; padding: 20px; border-radius: 15px; border-left: 5px solid var(--emerald); margin-top: 15px; display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>

    <nav class="sidebar">
        <a href="dashboard_supplier.php"><i class="fa-solid fa-house"></i></a>
        <a href="supplier_pesanan.php"><i class="fa-solid fa-box-open"></i></a>
        <a href="supplier_rekomendasi.php" class="active"><i class="fa-solid fa-vial-circle-check"></i></a>
        <a href="logout.php" style="margin-top: auto; color: #ef4444;"><i class="fa-solid fa-power-off"></i></a>
    </nav>

    <div class="main">
        <h1>Saran <span style="color: var(--emerald);">Pemupukan</span></h1>
        <p style="color: #64748b;">Gunakan tabel <b>saran_pemupukan</b> di database scm_pertanian.</p>

        <?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

        <div class="card">
            <form method="POST">
                <input type="text" name="judul" placeholder="Judul Rekomendasi" required>
                <textarea name="isi_saran" rows="5" placeholder="Isi Saran..." required></textarea>
                <button type="submit" name="kirim_saran" class="btn">Kirim Saran</button>
            </form>
        </div>

        <h3 style="margin-top: 40px;">Riwayat Saran</h3>
        <?php
        $res = mysqli_query($conn, "SELECT * FROM saran_pemupukan ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($res)):
        ?>
            <div class="saran-item">
                <div>
                    <strong><?= $row['judul'] ?></strong><br>
                    <small><?= $row['tanggal'] ?></small>
                </div>
                <a href="supplier_rekomendasi.php?hapus=<?= $row['id'] ?>" style="color:red;"><i class="fa-solid fa-trash"></i></a>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>