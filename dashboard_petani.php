<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SCM Agro - Smart Farming</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body{
    background:#f8faf9;
    color:#333;
    overflow-x:hidden;
}

/* ================= NAVBAR ================= */

.navbar{
    position:fixed;
    width:100%;
    top:0;
    padding:20px 8%;
    display:flex;
    justify-content:space-between;
    align-items:center;
    transition:0.3s;
    z-index:1000;
}

.navbar.scrolled{
    background:white;
    box-shadow:0 8px 25px rgba(0,0,0,0.05);
}

.logo{
    font-size:24px;
    font-weight:800;
    color:white;
}

.navbar.scrolled .logo{
    color:#0F5C4C;
}

.nav-links{
    display:flex;
    gap:30px;
}

.nav-links a{
    text-decoration:none;
    color:white;
    font-weight:500;
    transition:0.3s;
}

.navbar.scrolled .nav-links a{
    color:#333;
}

.nav-links a:hover{
    color:#F4C430;
}

.btn{
    background:#F4C430;
    padding:10px 20px;
    border-radius:30px;
    font-weight:600;
    color:black;
}

/* ================= HERO ================= */

.hero{
    height:100vh;
    background:linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url("petani.jpg");
    background-size:cover;
    background-position:center;
    display:flex;
    align-items:center;
    padding:0 8%;
    position:relative;
}

.hero-content{
    max-width:700px;
    color:white;
    animation:fadeUp 1.2s ease;
}

.hero-content h1{
    font-size:60px;
    font-weight:800;
    line-height:1.1;
    margin-bottom:20px;
}

.hero-content span{
    color:#F4C430;
}

.hero-content p{
    margin-bottom:30px;
    font-size:18px;
    opacity:0.9;
}

.hero-content .btn{
    background:#2E7D32;
    color:white;
}

/* ================= FLOATING STATS ================= */

.stats{
    position:absolute;
    bottom:-60px;
    left:8%;
    background:white;
    padding:30px 50px;
    border-radius:20px;
    display:flex;
    gap:50px;
    box-shadow:0 20px 50px rgba(0,0,0,0.08);
}

.stat h3{
    font-size:28px;
    color:#0F5C4C;
}

.stat p{
    font-size:14px;
    color:#777;
}

/* ================= SECTION ================= */

.section{
    padding:140px 8% 100px 8%;
}

.section-title{
    text-align:center;
    margin-bottom:60px;
}

.section-title h2{
    font-size:40px;
    color:#0F5C4C;
}

/* ================= CARDS ================= */

.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:40px;
}

.card-link{
    text-decoration:none;
    color:inherit;
}

.card{
    background:white;
    border-radius:25px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,0.05);
    transition:0.4s;
}

.card:hover{
    transform:translateY(-15px);
}

.card img{
    width:100%;
    height:240px;
    object-fit:cover;
}

.card-content{
    padding:30px;
}

.card-content h3{
    color:#0F5C4C;
    margin-bottom:15px;
    font-size:20px;
}

/* ================= ABOUT ================= */

.about{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:70px;
    align-items:center;
}

.about img{
    width:100%;
    border-radius:30px;
    box-shadow:0 25px 60px rgba(0,0,0,0.1);
}

.about-text h2{
    font-size:38px;
    color:#0F5C4C;
    margin-bottom:20px;
}

.about-text p{
    margin-bottom:30px;
    line-height:1.7;
}

.highlight{
    background:linear-gradient(135deg,#2E7D32,#0F5C4C);
    padding:25px;
    border-radius:20px;
    color:white;
    font-size:22px;
    font-weight:600;
}

/* ================= FOOTER ================= */

footer{
    background:#0F5C4C;
    color:white;
    text-align:center;
    padding:40px;
}

/* ================= ANIMATION ================= */

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(40px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* ================= RESPONSIVE ================= */

@media(max-width:1000px){
    .about{
        grid-template-columns:1fr;
    }

    .stats{
        position:relative;
        flex-direction:column;
        gap:20px;
        margin-top:30px;
    }

    .hero-content h1{
        font-size:40px;
    }

    .nav-links{
        display:none;
    }
}

</style>
</head>
<body>

<div class="navbar" id="navbar">
    <div class="logo">🌾 SCM Agro</div>
    <div class="nav-links">
        <a href="#">Home</a>
        <a href="#">Services</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
        <a href="#" class="btn">Login</a>
    </div>
</div>

<section class="hero">
    <div class="hero-content">
        <h1>Every Day Is A <span>Good Day</span><br>To Be A Farmer</h1>
        <p>Sistem Manajemen Rantai Pasok Pertanian modern untuk mengelola hasil panen, pemesanan bibit, dan transaksi distributor dalam satu platform digital.</p>
        <a href="#" class="btn">Mulai Sekarang</a>
    </div>

    <div class="stats">
        <div class="stat">
            <h3>12,980+</h3>
            <p>Petani Bergabung</p>
        </div>
        <div class="stat">
            <h3>8,540+</h3>
            <p>Transaksi Sukses</p>
        </div>
        <div class="stat">
            <h3>320+</h3>
            <p>Distributor Aktif</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="section-title">
        <h2>Layanan Digital Pertanian</h2>
    </div>

    <div class="cards">
        <a href="pesan_bibit.php" class="card-link">
        <div class="card">
        <img src="pesan_bibit.png">
        <div class="card-content">
            <h3>Pemesanan Bibit & Pupuk</h3>
            <p>Sistem pemesanan sarana produksi yang cepat, transparan dan terintegrasi.</p>
        </div>
    </div>
</a>

        <a href="Penjualan_panen.php" class="card-link">
<div class="card">
    <img src="Penjualan_panen.png">
    <div class="card-content">
        <h3>Penjualan Panen</h3>
        <p>Ajukan hasil panen untuk dijual ke distributor.</p>
    </div>
</div>
</a>

       <a href="transaksi_distribusi.php" class="card-link">
    <div class="card">
        <img src="transaksi&distribusi.png">
        <div class="card-content">
            <h3>Transaksi & Distribusi</h3>
            <p>Ajukan dan kelola penjualan hasil panen langsung ke distributor.</p>
        </div>
    </div>
</a>
    </div>
</section>

<section class="section">
    <div class="about">
        <img src="petani.jpg">
        <div class="about-text">
            <h2>Meningkatkan Produktivitas Pertanian Indonesia</h2>
            <p>Platform SCM Agro membantu petani mengelola rantai pasok dari pemesanan hingga transaksi dengan sistem digital yang efisien dan modern.</p>
            <div class="highlight">
                Smart Farming System 2026 🚜
            </div>
        </div>
    </div>
</section>

<footer>
    © 2026 SCM Agro - Smart Farming System
</footer>

<script>
window.addEventListener("scroll", function(){
    var navbar = document.getElementById("navbar");
    navbar.classList.toggle("scrolled", window.scrollY > 50);
});
</script>

</body>
</html>