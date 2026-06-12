<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: index.php");
    exit;
}

$id_distributor = $_SESSION['id'];

// PROSES SIMPAN NOTA TRANSAKSI
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['proses_beli'])) {
    $id_panen    = mysqli_real_escape_string($conn, $_POST['id_panen']);
    $harga_perkg = mysqli_real_escape_string($conn, $_POST['harga_perkg']);
    
    $q_panen = mysqli_query($conn, "SELECT id_petani, jumlah FROM pengajuan_panen WHERE id_panen = '$id_panen'");
    $d_panen = mysqli_fetch_assoc($q_panen);
    
    if ($d_panen) {
        $id_petani   = $d_panen['id_petani'];
        $jumlah_kg   = $d_panen['jumlah'];
        $total_bayar = $jumlah_kg * $harga_perkg;
        
        // Simpan ke tabel transaksi_pembelian Sprint 10
        $query_transaksi = "INSERT INTO transaksi_pembelian (id_panen, id_distributor, id_petani, harga_perkg, total_bayar, status_logistik) 
                            VALUES ('$id_panen', '$id_distributor', '$id_petani', '$harga_perkg', '$total_bayar', 'Menunggu')";
        
        if (mysqli_query($conn, $query_transaksi)) {
            mysqli_query($conn, "UPDATE pengajuan_panen SET status = 'disetujui' WHERE id_panen = '$id_panen'");
            
            // ALERT & OTOMATIS DIALIKHAN MASUK KE HALAMAN TRANSAKSI
            echo "<script>
                    alert('Transaksi Berhasil! Nota pembelian telah dibuat.');
                    window.location = 'transaksi_distribusi.php'; 
                  </script>";
            exit;
        } else {
            echo "Gagal menyimpan transaksi: " . mysqli_error($conn);
        }
    } else {
        echo "Data pengajuan panen tidak valid!";
    }
}

if (!isset($_GET['id'])) {
    header("Location: dashboard_distributor.php");
    exit;
}

$id = mysqli_real_escape_string($conn, $_GET['id']);
$query = mysqli_query($conn, "SELECT p.*, u.nama FROM pengajuan_panen p JOIN users u ON p.id_petani = u.id WHERE p.id_panen = '$id'");
$d = mysqli_fetch_assoc($query);

if (!$d) { die("Data pengajuan panen tidak ditemukan!"); }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Finalize Order // Elite Distri.</title>
    <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #1B4332; --accent: #74C69D; --bg: #F0F4F8; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }
        body { background: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .modal-card { background: white; padding: 50px; border-radius: 40px; width: 100%; max-width: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.05); }
        h2 { font-weight: 900; color: var(--primary); margin-bottom: 30px; }
        .input-group { margin-bottom: 25px; }
        label { font-size: 12px; font-weight: 800; color: #ccc; text-transform: uppercase; display: block; margin-bottom: 10px; }
        input { width: 100%; padding: 15px 25px; border-radius: 15px; border: 1px solid #eee; font-weight: 700; outline: none; }
        .btn-confirm { background: var(--primary); color: white; border: none; padding: 20px; width: 100%; border-radius: 50px; font-weight: 900; cursor: pointer; transition: 0.3s; }
        .btn-confirm:hover { background: var(--accent); color: var(--primary); }
    </style>
</head>
<body>
    <div class="modal-card">
        <p style="color: var(--accent); font-weight: 800; font-size: 12px;">ORDER FINALIZATION</p>
        <h2>Set Purchase Price</h2>
        
        <form action="" method="POST">
            <input type="hidden" name="id_panen" value="<?= $d['id_panen'] ?>">
            
            <div class="input-group">
                <label>Commodity</label>
                <input type="text" value="<?= $d['nama_hasil'] ?> (<?= $d['jumlah'] ?> KG)" disabled>
            </div>
            
            <div class="input-group">
                <label>Price per KG (Rp)</label>
                <input type="number" name="harga_perkg" placeholder="Contoh: 15000" required autofocus>
            </div>
            
            <button type="submit" name="proses_beli" class="btn-confirm">APPROVE & PURCHASE</button>
            <a href="dashboard_distributor.php" style="display:block; text-align:center; margin-top:20px; color:#ccc; text-decoration:none; font-weight:700;">Cancel</a>
        </form>
    </div>
</body>
</html>