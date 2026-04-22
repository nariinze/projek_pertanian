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

<style>
body{
    font-family:'Poppins',sans-serif;
    background:#f4f9f4;
    padding:40px;
}

/* HEADER */
h1{
    text-align:center;
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
    margin-top:20px;
}

th{
    background:#2e7d32;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    border-bottom:1px solid #eee;
}

/* STATUS */
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

/* PROGRESS */
.progress{
    width:100%;
    height:10px;
    background:#eee;
    border-radius:10px;
    overflow:hidden;
}

.bar{
    height:100%;
    background:#2e7d32;
}

/* BUTTON */
button{
    padding:8px 14px;
    border:none;
    border-radius:8px;
    background:#2e7d32;
    color:white;
    cursor:pointer;
}

button:hover{
    background:#1b5e20;
}

/* EMPTY */
.empty{
    text-align:center;
    padding:40px;
    color:#888;
}
</style>
</head>

<body>

<h1>Transaksi & Distribusi</h1>

<div class="card">

<table>
<tr>
<th>No</th>
<th>Produk</th>
<th>Jumlah</th>
<th>Tujuan</th>
<th>Status</th>
<th>Progress</th>
<th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_petani='$id_petani' ORDER BY id_transaksi DESC");

if(mysqli_num_rows($data) == 0){
    echo "<tr><td colspan='7' class='empty'>Belum ada transaksi</td></tr>";
}

$no = 1;
while($d = mysqli_fetch_array($data)){

$status = strtolower($d['status']);

/* HITUNG PROGRESS */
$progress = 0;
if($status=='menunggu') $progress=25;
if($status=='proses') $progress=50;
if($status=='kirim') $progress=75;
if($status=='selesai') $progress=100;
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

<td>
<div class="progress">
<div class="bar" style="width:<?= $progress ?>%"></div>
</div>
<?= $progress ?>%
</td>

<td>
<?php if($status != 'selesai'){ ?>
<form action="update_status.php" method="POST">
<input type="hidden" name="id" value="<?= $d['id_transaksi'] ?>">
<button type="submit">Update</button>
</form>
<?php } else { ?>
✔ Selesai
<?php } ?>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>