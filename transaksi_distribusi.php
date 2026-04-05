<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['role']) || $_SESSION['role']!='petani'){
header("Location: login.php");
exit;
}

$id_petani = $_SESSION['id'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Transaksi & Distribusi</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{
background:linear-gradient(135deg,#f4f9f4,#ffffff);
padding:40px;
}

/* HEADER */
.header{
text-align:center;
margin-bottom:40px;
}

.header h1{
font-size:36px;
color:#1b5e20;
}

/* CARD */
.card{
background:white;
padding:30px;
border-radius:20px;
box-shadow:0 20px 50px rgba(0,0,0,0.05);
}

/* TABLE */
table{
width:100%;
border-collapse:collapse;
}

th{
background:#2e7d32;
color:white;
padding:15px;
text-align:left;
}

td{
padding:15px;
border-bottom:1px solid #eee;
}

/* STATUS BADGE */
.status{
padding:6px 14px;
border-radius:20px;
font-size:12px;
font-weight:600;
}

.menunggu{ background:#fff3cd; color:#856404; }
.proses{ background:#cce5ff; color:#004085; }
.kirim{ background:#d4edda; color:#155724; }
.selesai{ background:#d1e7dd; color:#0f5132; }

</style>
</head>

<body>

<div class="header">
<h1>Transaksi & Distribusi</h1>
<p>Pantau status penjualan panen kamu 🌾</p>
</div>

<div class="card">

<table>
<tr>
<th>No</th>
<th>Produk</th>
<th>Jumlah</th>
<th>Tujuan</th>
<th>Status</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_petani='$id_petani'");
$no = 1;

while($d = mysqli_fetch_array($data)){

$status = strtolower($d['status']);
?>

<tr>
<td><?= $no++ ?></td>
<td><?= $d['produk'] ?></td>
<td><?= $d['jumlah'] ?> Kg</td>
<td><?= $d['tujuan'] ?></td>

<td>
<span class="status <?= $status ?>">
<?= $d['status'] ?>
</span>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>