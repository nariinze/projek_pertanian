<?php
session_start();
include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$password'");

if(mysqli_num_rows($query) > 0){

    $data = mysqli_fetch_assoc($query);

    $_SESSION['id'] = $data['id'];
    $_SESSION['username'] = $data['username'];
    $_SESSION['role'] = trim($data['role']);

    echo "Login berhasil <br>";
    echo "Role: ".$_SESSION['role']."<br>";

    if($_SESSION['role'] == "petani"){
        header("Location: dashboard_petani.php");
        exit;
    }
    elseif($_SESSION['role'] == "admin"){
        header("Location: dashboard_admin.php");
        exit;
    }

}else{
    echo "Login gagal";
}
?>