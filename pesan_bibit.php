<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'petani') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Agro Elite Order</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
transition:all .3s ease;
}

body{
background:linear-gradient(120deg,#f4f7f4,#ffffff,#eef5ee);
background-size:200% 200%;
animation:gradientMove 12s ease infinite;
min-height:100vh;
color:#1e2d1e;
overflow-x:hidden;
}

@keyframes gradientMove{
0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}
}

/* Floating soft light effect */
body::before{
content:"";
position:fixed;
width:500px;
height:500px;
background:radial-gradient(circle,rgba(47,109,67,0.15),transparent 70%);
top:-100px;
right:-100px;
z-index:0;
animation:floatLight 8s ease-in-out infinite alternate;
}

@keyframes floatLight{
from{transform:translateY(0px);}
to{transform:translateY(40px);}
}

/* NAVBAR */
.navbar{
position:relative;
z-index:2;
display:flex;
justify-content:space-between;
padding:30px 8%;
background:rgba(255,255,255,0.8);
backdrop-filter:blur(20px);
box-shadow:0 10px 40px rgba(0,0,0,0.05);
font-weight:700;
letter-spacing:1px;
}

.logo{
font-size:22px;
color:#1f4d2b;
}

/* HERO */
.hero{
position:relative;
z-index:2;
text-align:center;
padding:80px 8% 50px 8%;
}

.hero h1{
font-size:48px;
background:linear-gradient(90deg,#1f4d2b,#3a7d44);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

.badge{
display:inline-block;
margin-top:15px;
padding:8px 22px;
background:linear-gradient(90deg,#eaf4ec,#f6faf6);
border-radius:30px;
font-size:13px;
color:#2f6d43;
border:1px solid #dce9dc;
}

/* GRID */
.container{
position:relative;
z-index:2;
padding:40px 8%;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
gap:30px;
}

/* CARD */
.card{
background:rgba(255,255,255,0.85);
backdrop-filter:blur(20px);
border-radius:24px;
padding:35px;
cursor:pointer;
border:1px solid #e4eee4;
box-shadow:0 15px 35px rgba(0,0,0,0.05);
position:relative;
overflow:hidden;
}

.card::before{
content:"";
position:absolute;
top:-50%;
left:-50%;
width:200%;
height:200%;
background:linear-gradient(120deg,transparent,rgba(255,255,255,0.4),transparent);
transform:rotate(25deg);
opacity:0;
}

.card:hover::before{
opacity:1;
animation:shine 1s ease;
}

@keyframes shine{
0%{transform:translateX(-100%) rotate(25deg);}
100%{transform:translateX(100%) rotate(25deg);}
}

.card:hover{
transform:translateY(-12px);
box-shadow:0 25px 50px rgba(0,0,0,0.08);
}

.card.active{
border:1px solid #2f6d43;
box-shadow:0 0 30px rgba(47,109,67,0.2);
}

.price{
margin-top:10px;
color:#2f6d43;
font-weight:600;
}

/* ORDER BOX */
.order-box{
position:relative;
z-index:2;
margin:80px auto;
background:rgba(255,255,255,0.9);
backdrop-filter:blur(25px);
border-radius:40px;
padding:60px;
max-width:750px;
border:1px solid #e4eee4;
box-shadow:0 30px 70px rgba(0,0,0,0.07);
}

label{
font-size:14px;
color:#4a5a4a;
}

input{
width:100%;
padding:16px;
border-radius:14px;
border:1px solid #dde6dd;
margin:20px 0;
font-size:16px;
background:#f9fcf9;
}

input:focus{
border-color:#2f6d43;
outline:none;
box-shadow:0 0 0 4px rgba(47,109,67,0.15);
}

/* SUMMARY */
.summary{
margin-top:20px;
padding-top:20px;
border-top:1px solid #e6eee6;
}

.summary div{
display:flex;
justify-content:space-between;
margin-bottom:12px;
font-size:15px;
}

.total{
font-size:26px;
font-weight:800;
background:linear-gradient(90deg,#1f4d2b,#3a7d44);
-webkit-background-clip:text;
-webkit-text-fill-color:transparent;
}

/* PAYMENT */
.payment{
margin-top:30px;
display:flex;
gap:15px;
flex-wrap:wrap;
}

.pay{
padding:10px 18px;
border-radius:25px;
background:#f1f6f1;
cursor:pointer;
border:1px solid #e1ebe1;
}

.pay:hover{
background:#e4efe4;
transform:translateY(-2px);
}

.pay.active{
background:#2f6d43;
color:white;
border:none;
box-shadow:0 8px 20px rgba(47,109,67,0.3);
}

/* BUTTON */
button{
margin-top:35px;
width:100%;
padding:18px;
border:none;
border-radius:50px;
background:linear-gradient(90deg,#2f6d43,#1f4d2b);
color:white;
font-weight:600;
font-size:15px;
cursor:pointer;
letter-spacing:1px;
}

button:hover{
transform:translateY(-3px);
box-shadow:0 15px 40px rgba(47,109,67,0.3);
}
</style>
</head>

<body>

<div class="navbar">
<div class="logo">SCM ARGO</div>
</div>

<div class="hero">
<h1>Pemesanan Bibit & Pupuk</h1>
<div class="badge">Premium Quality Guaranteed</div>
</div>

<div class="container">

<div class="card" onclick="pilih(50000,this)">
<h3>Bibit Padi Premium</h3>
<div class="price">Rp 50.000 / kg</div>
</div>

<div class="card" onclick="pilih(75000,this)">
<h3>Bibit Jagung Hybrid</h3>
<div class="price">Rp 75.000 / kg</div>
</div>

<div class="card" onclick="pilih(40000,this)">
<h3>Pupuk Urea Organik</h3>
<div class="price">Rp 40.000 / kg</div>
</div>

<div class="card" onclick="pilih(60000,this)">
<h3>Pupuk NPK Profesional</h3>
<div class="price">Rp 60.000 / kg</div>
</div>

</div>

<div class="order-box">

<label>Masukkan Jumlah (kg)</label>
<input type="number" id="kg" value="1" min="1" onchange="hitung()">

<div class="summary">
<div><span>Harga per kg</span><span id="hargaKg">Rp 0</span></div>
<div><span>Total Berat</span><span id="totalKg">0 kg</span></div>
<div><span>Subtotal</span><span id="subtotal">Rp 0</span></div>
<div><span>Diskon</span><span class="discount" id="diskon">-</span></div>
<div><span>Ongkir</span><span>Rp 10.000</span></div>
<hr style="opacity:0.3;margin:10px 0;">
<div><span>Total Bayar</span><span class="total" id="total">Rp 0</span></div>
</div>

<div class="payment">
<div class="pay" onclick="pilihPay(this)">Transfer</div>
<div class="pay" onclick="pilihPay(this)">OVO</div>
<div class="pay" onclick="pilihPay(this)">DANA</div>
<div class="pay" onclick="pilihPay(this)">QRIS</div>
</div>

<button onclick="alert('Pesanan berhasil dibuat!')">
Checkout Sekarang
</button>

</div>

<script>
let harga = 0;

function pilih(h,el){
harga = h;
document.querySelectorAll(".card").forEach(c=>c.classList.remove("active"));
el.classList.add("active");

document.getElementById("hargaKg").innerHTML =
"Rp " + harga.toLocaleString("id-ID");

hitung();
}

function hitung(){
let kg = document.getElementById("kg").value;
let subtotal = harga * kg;
let ongkir = 10000;
let diskon = 0;

if(kg >= 10){
diskon = subtotal * 0.1; // diskon 10%
}

let total = subtotal - diskon + ongkir;

document.getElementById("totalKg").innerHTML = kg + " kg";

document.getElementById("subtotal").innerHTML =
"Rp " + subtotal.toLocaleString("id-ID");

document.getElementById("diskon").innerHTML =
diskon > 0 ? "- Rp " + diskon.toLocaleString("id-ID") : "-";

document.getElementById("total").innerHTML =
"Rp " + total.toLocaleString("id-ID");
}

function pilihPay(el){
document.querySelectorAll(".pay").forEach(p=>p.classList.remove("active"));
el.classList.add("active");
}
</script>

</body>
</html>