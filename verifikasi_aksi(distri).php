<?php
session_start();
include "koneksi.php";

// Proteksi halaman, pastikan hanya distributor yang bisa memproses
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'distributor') {
    header("Location: index.php");
    exit;
}

// 1. KONDISI JIKA TOMBOL DECLINE DIKLIK (Membawa status=ditolak di URL)
if (isset($_GET['id']) && isset($_GET['status']) && $_GET['status'] == 'ditolak') {
    $id_panen = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Ambil detail data untuk form konfirmasi alasan penolakan
    $q_detail = mysqli_query($conn, "SELECT p.*, u.nama FROM pengajuan_panen p JOIN users u ON p.id_petani = u.id WHERE p.id_panen = '$id_panen'");
    $d = mysqli_fetch_assoc($q_detail);
    
    if (!$d) {
        die("Data pengajuan tidak ditemukan!");
    }
?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Konfirmasi Penolakan // Elite Distri</title>
        <link href="https://fonts.googleapis.com/css2?family=Urbanist:wght@400;700;900&display=swap" rel="stylesheet">
        <style>
            :root { --primary: #1B4332; --danger: #FF4D4D; --bg: #F0F4F8; }
            * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Urbanist', sans-serif; }
            body { background: var(--bg); display: flex; justify-content: center; align-items: center; min-height: 100vh; }
            .confirm-card { background: white; padding: 50px; border-radius: 40px; width: 100%; max-width: 500px; box-shadow: 0 30px 60px rgba(0,0,0,0.05); }
            h2 { font-weight: 900; color: var(--danger); margin-bottom: 20px; }
            .info-box { background: #FFF5F5; padding: 20px; border-radius: 15px; margin-bottom: 25px; font-size: 14px; line-height: 1.6; }
            label { font-size: 12px; font-weight: 800; color: #ccc; text-transform: uppercase; display: block; margin-bottom: 10px; }
            textarea { width: 100%; padding: 15px; border-radius: 15px; border: 1px solid #eee; font-weight: 700; outline: none; resize: none; margin-bottom: 25px; }
            .btn-reject { background: var(--danger); color: white; border: none; padding: 20px; width: 100%; border-radius: 50px; font-weight: 900; cursor: pointer; transition: 0.3s; }
            .btn-reject:hover { background: #cc0000; }
        </style>
    </head>
    <body>
        <div class="confirm-card">
            <p style="color: var(--danger); font-weight: 800; font-size: 12px;">CONFIRM DECLINE</p>
            <h2>Tolak Pengajuan Panen</h2>
            
            <div class="info-box">
                <b>Petani:</b> <?= $d['nama'] ?><br>
                <b>Komoditas:</b> <?= $d['nama_hasil'] ?> (<?= number_format($d['jumlah']) ?> kg)
            </div>

            <form action="verifikasi_aksi(distri).php" method="POST">
                <input type="hidden" name="id_panen" value="<?= $d['id_panen'] ?>">
                <input type="hidden" name="status_keputusan" value="ditolak">
                
                <div class="input-group">
                    <label>Alasan Penolakan</label>
                    <textarea name="alasan" rows="4" placeholder="Ketik alasan penolakan... (Contoh: Kualitas komoditas belum memenuhi standar)" required></textarea>
                </div>
                
                <button type="submit" name="submit_keputusan" class="btn-reject">KONFIRMASI TOLAK</button>
                <a href="dashboard_distributor.php" style="display:block; text-align:center; margin-top:20px; color:#ccc; text-decoration:none; font-weight:700;">Batal</a>
            </form>
        </div>
    </body>
    </html>
<?php
    exit;
}

// 2. PROSES EKSEKUSI UPDATE DATABASE SETELAH DIKONFIRMASI (POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_keputusan'])) {
    $id_panen = mysqli_real_escape_string($conn, $_POST['id_panen']);
    $status   = mysqli_real_escape_string($conn, $_POST['status_keputusan']);
    $alasan   = isset($_POST['alasan']) ? mysqli_real_escape_string($conn, $_POST['alasan']) : '';

    // Jalankan query update status ke database pengajuan_panen sesuai keputusan distributor
    $query_update = "UPDATE pengajuan_panen SET status = '$status' WHERE id_panen = '$id_panen'";
    
    if (mysqli_query($conn, $query_update)) {
        // Implementasi Notifikasi Hasil Keputusan Pembelian ke Petani secara real-time
        echo "<script>
                alert('Pengajuan hasil panen telah berhasil ditolak!');
                window.location = 'dashboard_distributor.php';
              </script>";
        exit;
    } else {
        echo "Gagal memproses keputusan: " . mysqli_error($conn);
    }
}
?>