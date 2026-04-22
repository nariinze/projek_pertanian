<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$data = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($data);

if($cek > 0){
    $user = mysqli_fetch_assoc($data);

    // INI WAJIB DITAMBAH agar id_petani bisa terbaca di supplier
    $_SESSION['id'] = $user['id']; 
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if($user['role']=="petani"){
        header("Location: dashboard_petani.php");
    }
    elseif($user['role']=="supplier"){
        header("Location: dashboard_supplier.php");
    }
    elseif($user['role'] == "distributor"){
        header("Location: dashboard_distributor.php");
    }
     elseif($user['role'] == "buruh"){
        header("Location: dashboard_buruh.php");
    }
    exit;

}else{
    echo "<script>alert('Login Gagal!'); window.location='login.php';</script>";
}
?>