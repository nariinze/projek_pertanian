<?php
session_start();
include "koneksi.php";

// Proteksi halaman
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Agro Elite Order - SCM ARGO</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
    /* CSS Kamu tetap saya pertahankan karena sudah bagus */
    *{ margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; transition:all .3s ease; }
    body{ background:linear-gradient(120deg,#f4f7f4,#ffffff,#eef5ee); background-size:200% 200%; animation:gradientMove 12s ease infinite; min-height:100vh; color:#1e2d1e; overflow-x:hidden; }
    @keyframes gradientMove{ 0%{background-position:0% 50%;} 50%{background-position:100% 50%;} 100%{background-position:0% 50%;} }
    .navbar{ position:sticky; top:0; z-index:1000; display:flex; justify-content:space-between; padding:20px 8%; background:rgba(255,255,255,0.8); backdrop-filter:blur(20px); box-shadow:0 10px 40px rgba(0,0,0,0.05); }
    .logo{ font-size:22px; color:#1f4d2b; text-decoration: none; font-weight:800; }
    .hero{ text-align:center; padding:60px 8% 30px 8%; }
    .hero h1{ font-size:42px; background:linear-gradient(90deg,#1f4d2b,#3a7d44); -webkit-background-clip:text; -webkit-text-fill-color:transparent; font-weight:800; }
    .badge{ display:inline-block; margin-top:10px; padding:6px 20px; background:#eaf4ec; border-radius:30px; font-size:12px; color:#2f6d43; border:1px solid #dce9dc; }
    
    .container{ padding:40px 8%; display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:25px; }
    .card{ background:white; border-radius:24px; padding:30px; cursor:pointer; border:1px solid #e4eee4; box-shadow:0 10px 30px rgba(0,0,0,0.03); position:relative; }
    .card:hover{ transform:translateY(-10px); box-shadow:0 20px 40px rgba(0,0,0,0.08); border-color:#2f6d43; }
    .card.active{ border:2px solid #2f6d43; background:#f0f7f1; }
    .price{ margin-top:10px; color:#2f6d43; font-weight:700; font-size:18px; }
    
    .order-box{ margin:40px auto 100px; background:white; border-radius:35px; padding:50px; max-width:700px; border:1px solid #e4eee4; box-shadow:0 30px 60px rgba(0,0,0,0.05); }
    label{ font-weight:700; font-size:14px; color:#666; }
    input{ width:100%; padding:16px; border-radius:14px; border:1px solid #dde6dd; margin:15px 0 25px; font-size:18px; font-weight:700; }
    .summary div{ display:flex; justify-content:space-between; margin-bottom:12px; }
    .total{ font-size:28px; font-weight:900; color:#1f4d2b; }
    
    .payment{ display:flex; gap:10px; flex-wrap:wrap; margin-bottom:30px; }
    .pay{ padding:12px 20px; border-radius:15px; background:#f8faf8; cursor:pointer; border:1px solid #e1ebe1; font-size:13px; font-weight:600; }
    .pay.active{ background:#2f6d43; color:white; border-color:#2f6d43; }
    
    button{ width:100%; padding:20px; border:none; border-radius:50px; background:#2f6d43; color:white; font-weight:800; font-size:16px; cursor:pointer; box-shadow:0 10px 25px rgba(47,109,67,0.3); }
    button:hover{ background:#1f4d2b; transform:scale(1.02); }
</style>
</head>

<body>

<div class="navbar">
    <a href="dashboard_petani.php" class="logo"><i class="fa-solid fa-arrow-left"></i> SCM ARGO</a>
    <div style="font-weight:600;">Petani: <span style="color:#2f6d43;"><?= $username ?></span></div>
</div>

<div class="hero">
    <h1>Pemesanan Bibit & Pupuk</h1>
    <div class="badge"><i class="fa-solid fa-shield-check"></i> Produk Terverifikasi Database</div>
</div>

<div class="container">
    <?php
    // AMBIL DATA DARI DATABASE (Dinamis)
    $q_produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
    if(mysqli_num_rows($q_produk) > 0){
        while($row = mysqli_fetch_assoc($q_produk)){
            ?>
            <div class="card" onclick="pilih('<?= $row['nama_produk'] ?>', <?= $row['harga'] ?>, this)">
                <i class="fa-solid fa-seedling" style="color:#2f6d43; margin-bottom:15px;"></i>
                <h3 style="font-size:16px;"><?= $row['nama_produk'] ?></h3>
                <div class="price">Rp <?= number_format($row['harga'], 0, ',', '.') ?> <small style="font-size:10px; color:#999;">/ kg</small></div>
                <div style="font-size:11px; color:#666; margin-top:5px;">Stok Tersedia: <?= $row['stok'] ?> kg</div>
            </div>
            <?php
        }
    } else {
        echo "<p style='grid-column: span 3; text-align:center;'>Belum ada produk di database.</p>";
    }
    ?>
</div>

<div class="order-box">
    <form action="SimpanTransaksi.php" method="POST" onsubmit="return validasiForm()">
        <input type="hidden" name="produk" id="input_produk">
        <input type="hidden" name="metode_bayar" id="input_bayar">

        <label><i class="fa-solid fa-weight-hanging"></i> Jumlah Pesanan (kg)</label>
        <input type="number" name="jumlah" id="kg" value="1" min="1" oninput="hitung()" required>

        <div class="summary">
            <div><span>Produk Terpilih</span><span id="labelProduk" style="font-weight:800; color:#2f6d43;">-</span></div>
            <div><span>Harga Satuan</span><span id="hargaKg">Rp 0</span></div>
            <div><span>Ongkos Kirim</span><span>Rp 10.000</span></div>
            <hr style="opacity:0.1; margin:20px 0;">
            <div><span style="font-weight:700;">TOTAL BAYAR</span><span class="total" id="total">Rp 0</span></div>
        </div>

        <label style="display:block; margin:30px 0 15px;"><i class="fa-solid fa-credit-card"></i> Metode Pembayaran</label>
        <div class="payment">
            <div class="pay" onclick="pilihPay(this)">Transfer Bank</div>
            <div class="pay" onclick="pilihPay(this)">OVO / DANA</div>
            <div class="pay" onclick="pilihPay(this)">QRIS</div>
            <div class="pay" onclick="pilihPay(this)">Bayar di Tempat (COD)</div>
        </div>

        <button type="submit">
            <i class="fa-solid fa-basket-shopping"></i> Konfirmasi Pesanan
        </button>
    </form>
</div>

<script>
let hargaSatu = 0;

function pilih(nama, h, el){
    hargaSatu = h;
    document.querySelectorAll(".card").forEach(c=>c.classList.remove("active"));
    el.classList.add("active");

    document.getElementById("labelProduk").innerText = nama;
    document.getElementById("input_produk").value = nama; // Nama ini PASTI sama dengan database
    document.getElementById("hargaKg").innerText = "Rp " + h.toLocaleString("id-ID");

    hitung();
}

function hitung(){
    let qty = document.getElementById("kg").value;
    if (qty < 1) qty = 1;
    
    let ongkir = 10000;
    let subtotal = hargaSatu * qty;
    let grandTotal = (hargaSatu > 0) ? (subtotal + ongkir) : 0;

    document.getElementById("total").innerText = "Rp " + grandTotal.toLocaleString("id-ID");
}

function pilihPay(el){
    document.querySelectorAll(".pay").forEach(p=>p.classList.remove("active"));
    el.classList.add("active");
    document.getElementById("input_bayar").value = el.innerText;
}

function validasiForm() {
    let p = document.getElementById("input_produk").value;
    let b = document.getElementById("input_bayar").value;
    
    if(!p) { alert("Klik salah satu kartu produk di atas!"); return false; }
    if(!b) { alert("Pilih salah satu metode pembayaran!"); return false; }
    return true;
}
</script>

</body>
</html>