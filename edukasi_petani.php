<?php
include "koneksi.php"; // Pastikan koneksi ke scm_pertanian
?>

<div class="container" style="padding: 20px; background: #fff; border-radius: 15px;">
    <h2 style="color: #064e3b;"><i class="fa-solid fa-leaf"></i> Rekomendasi Pemupukan dari Supplier</h2>
    <hr>

    <?php
    // Mengambil data dari tabel saran_pemupukan
    $query = mysqli_query($conn, "SELECT * FROM saran_pemupukan ORDER BY id DESC");
    
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
    ?>
            <div class="card-saran" style="border: 1px solid #e2e8f0; padding: 15px; border-radius: 10px; margin-bottom: 15px;">
                <h3 style="margin: 0; color: #10b981;"><?= $row['judul']; ?></h3>
                <small style="color: #94a3b8;">Diposting pada: <?= $row['tanggal']; ?></small>
                <p style="margin-top: 10px; line-height: 1.6; color: #475569;">
                    <?= nl2br($row['isi']); ?>
                </p>
            </div>
    <?php
        }
    } else {
        echo "<p style='color: #94a3b8;'>Belum ada saran pemupukan tersedia.</p>";
    }
    ?>
</div>