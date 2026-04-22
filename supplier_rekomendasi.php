<?php
session_start();
include "koneksi.php";

// 1. Cek apakah koneksi ada
if (!$conn) { die("Koneksi gagal: " . mysqli_connect_error()); }

$pesan = "";

// 2. Proses saat tombol ditekan
if (isset($_POST['kirim_saran'])) {
    $id_petani = mysqli_real_escape_string($conn, $_POST['id_petani']);
    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi_saran = mysqli_real_escape_string($conn, $_POST['isi_saran']);
    $tanggal   = date('Y-m-d');

    // Query sesuai screenshot database kamu
    $query = "INSERT INTO saran_pemupukan (id_petani, judul, isi_saran, tanggal, status) 
              VALUES ('$id_petani', '$judul', '$isi_saran', '$tanggal', 'baru')";

    if (mysqli_query($conn, $query)) {
        // Kita tidak pakai redirect otomatis dulu untuk ngetes
        $pesan = "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:5px; margin-bottom:20px;'>
                    <b>Sukses!</b> Saran sudah masuk ke database.<br>
                    <a href='dashboard_petani.php'>Klik di sini untuk kembali</a> (Atau ganti link ini ke dashboard supplier kamu)
                  </div>";
    } else {
        $pesan = "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:5px; margin-bottom:20px;'>
                    <b>Gagal:</b> " . mysqli_error($conn) . "
                  </div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kirim Rekomendasi</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; padding: 40px; }
        .container { background: white; padding: 30px; border-radius: 15px; max-width: 500px; margin: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #34495e; }
        input, textarea, select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #dce4ec; border-radius: 8px; box-sizing: border-box; }
        .btn { width: 100%; padding: 15px; background: #27ae60; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: bold; }
        .btn:hover { background: #219150; }
    </style>
</head>
<body>

<div class="container">
    <h2>Kirim Saran Ahli</h2>
    
    <?= $pesan; ?>

    <form action="" method="POST">
        <label>Pilih Petani Penerima:</label>
        <select name="id_petani" required>
            <option value="">-- Pilih Petani --</option>
            <?php
            $res = mysqli_query($conn, "SELECT id, username FROM users WHERE role='petani'");
            while($p = mysqli_fetch_assoc($res)) {
                echo "<option value='".$p['id']."'>".$p['username']."</option>";
            }
            ?>
        </select>

        <label>Judul Rekomendasi:</label>
        <input type="text" name="judul" placeholder="Contoh: Dosis Pupuk Padi" required>

        <label>Instruksi / Isi Saran:</label>
        <textarea name="isi_saran" rows="5" placeholder="Tulis instruksi lengkap di sini..." required></textarea>

        <button type="submit" name="kirim_saran" class="btn">Kirim Sekarang</button>
    </form>
</div>

</body>
</html>