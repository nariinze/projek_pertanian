<?php
session_start();
?>

<h2>Data Akun</h2>

Username: <?php echo $_SESSION['username']; ?><br>
Role: <?php echo $_SESSION['role']; ?><br>