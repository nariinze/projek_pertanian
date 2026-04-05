<?php
session_start();
if ($_SESSION['role'] != 'petani') {
    header("Location: login.php");
}
?>

<h2>Riwayat Transaksi</h2>

<table border="1" cellpadding="10">
<tr>
    <th>No</th>
    <th>Produk</th>
    <th>Jumlah</th>
    <th>Status</th>
</tr>
<tr>
    <td>1</td>
    <td>Padi</td>
    <td>100 Kg</td>
    <td>Selesai</td>
</tr>
</table>