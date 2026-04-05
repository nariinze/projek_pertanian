<?php
session_start();
if(isset($_SESSION['role'])){
    if($_SESSION['role']=="petani"){
        header("Location: dashboard_petani.php");
    }else{
        header("Location: dashboard_admin.php");
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SCM Agro - Login</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
}

body{
    height:100vh;
    display:flex;
    background:
    linear-gradient(rgba(255,255,255,0.2),rgba(255,255,255,0.2)),
    url("petani2.jpg");
    background-size:cover;
    background-position:center;
    position:relative;
    overflow:hidden;
}

/* Light sweep animation */
body::after{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:50%;
    height:100%;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,0.25),transparent);
    transform:skewX(-20deg);
    animation:lightMove 12s infinite;
}

@keyframes lightMove{
    0%{ left:-100%; }
    100%{ left:150%; }
}

/* LEFT SIDE */
.left{
    flex:1;
    color:white;
    display:flex;
    flex-direction:column;
    justify-content:center;
    padding:100px;
    backdrop-filter:blur(2px);
    animation:fadeLeft 1.2s ease;
}

.left h1{
    font-size:52px;
    font-weight:700;
    margin-bottom:20px;
    text-shadow:0 5px 15px rgba(0,0,0,0.3);
}

.left p{
    font-size:17px;
    opacity:0.95;
    max-width:420px;
    text-shadow:0 3px 10px rgba(0,0,0,0.3);
}

@keyframes fadeLeft{
    from{opacity:0; transform:translateX(-40px);}
    to{opacity:1; transform:translateX(0);}
}

/* RIGHT SIDE */
.right{
    width:480px;
    background:rgba(255,255,255,0.97);
    backdrop-filter:blur(25px);
    display:flex;
    align-items:center;
    justify-content:center;
    padding:60px;
    position:relative;
    animation:fadeRight 1.2s ease;
}

@keyframes fadeRight{
    from{opacity:0; transform:translateX(40px);}
    to{opacity:1; transform:translateX(0);}
}

/* Glass edge highlight */
.right::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:4px;
    height:100%;
    background:linear-gradient(to bottom,transparent,#4CAF50,transparent);
}

/* Login Box */
.login-box{
    width:100%;
}

.login-box h2{
    margin-bottom:35px;
    color:#2E7D32;
    font-weight:600;
}

/* Floating label */
.input-group{
    position:relative;
    margin-bottom:25px;
}

input{
    width:100%;
    padding:18px 14px 8px 14px;
    border-radius:10px;
    border:1px solid #ddd;
    outline:none;
    font-size:14px;
    transition:0.3s;
}

label{
    position:absolute;
    top:18px;
    left:14px;
    font-size:14px;
    color:#777;
    transition:0.3s;
    pointer-events:none;
}

input:focus{
    border-color:#4CAF50;
    box-shadow:0 0 12px rgba(76,175,80,0.3);
}

input:focus + label,
input:not(:placeholder-shown) + label{
    top:6px;
    font-size:11px;
    color:#4CAF50;
}

/* Button */
button{
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,#4CAF50,#66BB6A);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform:translateY(-3px);
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

/* Footer */
.footer{
    margin-top:20px;
    font-size:13px;
    color:#777;
}
</style>
</head>
<body>

<div class="left">
    <h1>Smart Farming<br>Management System</h1>
    <p>Platform digital modern untuk mengelola hasil panen, pesanan bibit, dan distribusi secara efisien dan terintegrasi.</p>
</div>

<div class="right">
    <div class="login-box">
        <h2>Login Akun</h2>

        <form action="proses_login.php" method="POST">

            <div class="input-group">
                <input type="text" name="username" placeholder=" " required>
                <label>Username</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder=" " required>
                <label>Password</label>
            </div>

            <button type="submit">Masuk</button>

        </form>

        <div class="footer">
            © 2026 SCM Agro
        </div>
    </div>
</div>

</body>
</html>