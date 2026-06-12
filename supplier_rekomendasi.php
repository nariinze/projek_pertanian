<?php
session_start();
include "koneksi.php";

if (!$conn) { 
    die("Koneksi database gagal: " . mysqli_connect_error()); 
}

$pesan = "";

// Proses penyimpanan data ketika tombol ditekan
if (isset($_POST['kirim_saran'])) {
    $id_petani = mysqli_real_escape_string($conn, $_POST['id_petani']);
    $judul     = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi_saran = mysqli_real_escape_string($conn, $_POST['isi_saran']);
    $tanggal   = date('Y-m-d');
    $status    = 'baru';

    // Menggunakan query standar (terbukti sukses masuk di id 3-9 pada phpMyAdmin kamu)
    $query = "INSERT INTO saran_pemupukan (id_petani, judul, isi_saran, tanggal, status) 
              VALUES ('$id_petani', '$judul', '$isi_saran', '$tanggal', '$status')";

    if (mysqli_query($conn, $query)) {
        $pesan = "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:12px; margin-bottom:20px; font-weight:600; border: 1px solid #c3e6cb;'>
                    <i class='fa-solid fa-circle-check'></i> Sukses! Rekomendasi berhasil dikirim.
                  </div>";
    } else {
        $error_msg = mysqli_error($conn);
        $pesan = "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:12px; margin-bottom:20px; border: 1px solid #f5c6cb;'>
                    <b>Gagal Menyimpan:</b><br><small>" . $error_msg . "</small>
                  </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Rekomendasi // SCM Agro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f8faf9; color: #333; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }

        /* BOX CONTAINER FORM PUTIH POLOS SESUAI REQUEST KAMU */
        .container { background: white; padding: 40px; border-radius: 25px; max-width: 550px; width: 100%; box-shadow: 0 15px 40px rgba(0,0,0,0.03); border: 1px solid #eee; text-align: left; }
        h2 { color: #0F5C4C; margin-bottom: 25px; font-weight: 800; font-size: 24px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #444; font-size: 13px; text-align: left; }
        input, textarea, select { width: 100%; padding: 14px; margin-bottom: 20px; border: 1px solid #e2e8f0; border-radius: 12px; box-sizing: border-box; font-size: 14px; transition: 0.3s; background: #fdfdfd; color: #333; display: block; }
        input:focus, textarea:focus, select:focus { border-color: #0F5C4C; background: #fff; outline: none; box-shadow: 0 0 0 4px rgba(15, 92, 76, 0.05); }
        
        .btn-submit { width: 100%; padding: 15px; background: #0F5C4C; color: white; border: none; border-radius: 12px; cursor: pointer; font-size: 15px; font-weight: 700; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 10px; }
        .btn-submit:hover { background: #1B4332; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(15, 92, 76, 0.15); }
        
        .back-link { display: block; text-align: center; margin-top: 20px; color: #777; text-decoration: none; font-size: 13px; font-weight: 500; }
        .back-link:hover { color: #0F5C4C; text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Kirim Saran Ahli</h2>
    
    <?php echo $pesan; ?>

    <form action="" method="POST">
        
        <label>Pilih Petani Penerima:</label>
        <select name="id_petani" required>
            <option value="">-- Pilih Petani --</option>
            <?php
            // Ambil data petani dari tabel users
            $res = mysqli_query($conn, "SELECT id, username FROM users WHERE role='petani'");
            if ($res) {
                while($p = mysqli_fetch_assoc($res)) {
                    echo "<option value='".htmlspecialchars($p['id'])."'>".htmlspecialchars($p['username'])."</option>";
                }
            }
            ?>
        </select>

        <label>Judul Rekomendasi:</label>
        <input type="text" name="judul" placeholder="Contoh: Dosis Pupuk Kompos Jagung" required>

        <label>Instruksi / Isi Saran:</label>
        <textarea name="isi_saran" rows="5" placeholder="Tulis instruksi lengkap di sini..." required></textarea>

        <button type="submit" name="kirim_saran" class="btn-submit">
            <i class="fa-solid fa-paper-plane"></i> Kirim Sekarang
        </button>
        
    </form>

    <a href="dashboard_supplier.php" class="back-link"><i class="fa-solid fa-chevron-left"></i> Kembali ke Dashboard</a>
</div>

</body>
</html>