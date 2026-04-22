<?php
session_start();
include "koneksi.php";

if (isset($_POST['submit_panen'])) {
    $id_petani = $_POST['id_petani']; 
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $jumlah = $_POST['jumlah'];
    $tgl_panen = $_POST['tgl_panen'];

    // Validasi Dasar
    if ($jumlah <= 0) {
        echo "<script>alert('Jumlah tidak valid!'); window.history.back();</script>";
        exit;
    }

    // Folder Check
    if (!is_dir('uploads')) { mkdir('uploads', 0777, true); }

    $nama_file = $_FILES['foto']['name'];
    $tmp_file = $_FILES['foto']['tmp_name'];
    $foto_baru = "PANEN_" . time() . "_" . $nama_file;
    $path = "uploads/" . $foto_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        // PERHATIKAN BARIS INI (Line 32)
        // Pastikan kolom 'foto' ada di database agar query ini berhasil
        $query = "INSERT INTO pengajuan_panen (id_petani, nama_hasil, jumlah, status, foto) 
                  VALUES ('$id_petani', '$jenis', '$jumlah', 'menunggu', '$foto_baru')";
        
        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Data Berhasil Disimpan!'); window.location='dashboard_buruh.php';</script>";
        } else {
            // Jika masih error, ini akan memberitahu kolom apa yang kurang
            die("Kesalahan Database: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Field Input // Elite SCM</title>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root { --obsidian: #022c22; --emerald: #10b981; --slate: #f8fafc; }
        body { background: var(--slate); font-family: 'Satoshi', sans-serif; display: flex; justify-content: center; padding: 50px; }
        .card { background: white; padding: 50px; border-radius: 40px; width: 100%; max-width: 550px; box-shadow: 0 30px 60px rgba(0,0,0,0.05); }
        .input-group { margin-bottom: 20px; }
        label { display: block; font-weight: 700; font-size: 11px; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; }
        input, select { width: 100%; padding: 16px; border-radius: 15px; border: 1px solid #f1f5f9; background: #f8fafc; font-weight: 600; outline: none; }
        .btn { background: var(--obsidian); color: white; width: 100%; padding: 20px; border-radius: 20px; border: none; font-weight: 800; cursor: pointer; transition: 0.3s; margin-top: 20px; }
        .btn:hover { background: var(--emerald); transform: translateY(-3px); }
        .msg { padding: 15px; border-radius: 15px; margin-bottom: 25px; font-weight: 700; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="card">
        <a href="dashboard_buruh.php" style="text-decoration:none; color:var(--emerald); font-weight:800; font-size:12px;"><i class="fa-solid fa-arrow-left"></i> KEMBALI</a>
        <h2 style="font-weight: 900; font-size: 30px; margin: 20px 0 30px 0;">Recording <span style="color: var(--emerald);">Data.</span></h2>
        
        <?php if(isset($success)) echo "<div class='msg' style='background:#d1fae5; color:#065f46;'>$success</div>"; ?>
        <?php if(isset($error)) echo "<div class='msg' style='background:#fee2e2; color:#991b1b;'>$error</div>"; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label>Lahan Petani</label>
                <select name="id_petani" required>
                    <?php
                    $res = mysqli_query($conn, "SELECT id, nama FROM users WHERE role='petani'");
                    while($p = mysqli_fetch_assoc($res)) echo "<option value='".$p['id']."'>".$p['nama']."</option>";
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label>Jenis Tanaman</label>
                <input type="text" name="jenis" placeholder="Misal: Padi Ciherang" required>
            </div>
            <div class="input-group">
                <label>Jumlah (KG)</label>
                <input type="number" name="jumlah" step="0.1" required>
            </div>
            <div class="input-group">
                <label>Tanggal Panen</label>
                <input type="date" name="tgl_panen" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="input-group">
                <label>Foto Dokumentasi</label>
                <input type="file" name="foto" accept="image/*" required>
            </div>
            <button type="submit" name="submit_panen" class="btn">SIMPAN & KIRIM NOTIFIKASI</button>
        </form>
    </div>
</body>
</html>