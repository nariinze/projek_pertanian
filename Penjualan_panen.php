<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role']!='petani'){
header("Location: login.php");
exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>SCM Agro - Penjualan Panen</title>

<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;800&display=swap" rel="stylesheet">

<style>

/* (CSS kamu ga aku ubah, tetap sama) */

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Outfit,sans-serif;
}

body{
background: linear-gradient(-45deg,#f7fff7,#ffffff,#e8f5e9,#fdfbf5,#e3f2fd);
background-size:400% 400%;
animation: gradientShift 14s ease infinite;
min-height:100vh;
}

@keyframes gradientShift{
0%{background-position:0% 50%;}
50%{background-position:100% 50%;}
100%{background-position:0% 50%;}
}

.header{
text-align:center;
padding:60px 8% 30px;
}

.header h1{
font-size:46px;
font-weight:800;
color:#1b5e20;
}

.card{
max-width:800px;
margin:40px auto 80px;
background:white;
border-radius:30px;
padding:55px;
box-shadow:0 30px 80px rgba(0,0,0,0.05);
}

label{
display:block;
margin-top:22px;
}

input,select{
width:100%;
padding:17px;
margin-top:10px;
border-radius:14px;
border:1px solid #e6f2e6;
}

.summary{
margin-top:40px;
padding:25px;
border-radius:22px;
background:linear-gradient(135deg,#f7fff7,#ffffff);
}

.summary div{
display:flex;
justify-content:space-between;
margin-bottom:14px;
}

button{
margin-top:40px;
width:100%;
padding:20px;
border:none;
border-radius:50px;
background:linear-gradient(90deg,#22c55e,#16a34a);
color:white;
font-weight:700;
cursor:pointer;
}

#preview{
max-width:220px;
border-radius:18px;
margin-top:20px;
display:none;
}

</style>
</head>

<body>

<div class="header">
<h1>Penjualan Panen Digital</h1>
<p>Smart Agricultural Supply Chain Platform</p>
</div>

<div class="card">

<!-- ✅ FORM SUDAH DISAMBUNG -->
<form action="simpan_transaksi.php" method="POST">

<label>Nama Produk Panen</label>
<input type="text" name="produk" placeholder="Masukkan nama hasil panen" required>

<label>Berat Panen (kg)</label>
<input type="number" id="kg" name="jumlah" value="1" oninput="hitung()" required>

<label>Harga Jual per kg (Rp)</label>
<input type="number" id="harga" value="50000" oninput="hitung()">

<label>Upload Foto Panen</label>
<input type="file" accept="image/*" onchange="previewImage(event)">

<div style="text-align:center">
<img id="preview">
</div>

<label>Tujuan Penjualan</label>
<select name="tujuan" required>
<option>Distributor SCM Agro</option>
<option>Gudang Mitra</option>
<option>Pasar Premium</option>
</select>

<div class="summary">

<div>
<span>Total Berat</span>
<span id="totalKg">1 kg</span>
</div>

<div>
<span>Estimasi Pendapatan</span>
<span id="subtotal">Rp 50.000</span>
</div>

<div>
<span>Bonus Panen</span>
<span id="bonus">0%</span>
</div>

<div>
<span>Status Pengajuan</span>
<span id="status" style="color:#16a34a;font-weight:700">
Draft Panen
</span>
</div>

</div>

<!-- ✅ BUTTON SUDAH SUBMIT -->
<button type="submit">
Ajukan Penjualan Panen
</button>

</form>

</div>

<script>

/* Preview Image */
function previewImage(event){
let reader=new FileReader();

reader.onload=function(){
let preview=document.getElementById("preview");
preview.src=reader.result;
preview.style.display="block";
}

reader.readAsDataURL(event.target.files[0]);
}

/* Hitung Otomatis */
function hitung(){

let kg=document.getElementById("kg").value;
let harga=document.getElementById("harga").value;

let subtotal=kg*harga;

let bonus=0;

if(kg>=50){
bonus=10;
subtotal=subtotal-(subtotal*0.1);
}

document.getElementById("totalKg").innerHTML=kg+" kg";

document.getElementById("subtotal").innerHTML=
"Rp "+subtotal.toLocaleString("id-ID");

document.getElementById("bonus").innerHTML=bonus+"%";
}

hitung();

</script>

</body>
</html>