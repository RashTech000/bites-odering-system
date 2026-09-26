<?php
require_once "config.php";
if (!empty($_SESSION['admin_id'])) redirect("admin_index.php");
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $email=trim($_POST['email']??''); $password=$_POST['password']??'';
  $stmt=$conn->prepare("SELECT * FROM admins WHERE email=? LIMIT 1"); $stmt->bind_param("s",$email); $stmt->execute();
  $a=$stmt->get_result()->fetch_assoc();
  if ($a && password_verify($password,$a['password_hash'])) { $_SESSION['admin_id']=$a['id']; $_SESSION['admin_name']=$a['name']; redirect("admin_index.php"); }
  $error='Invalid login details.';
}
?><!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Admin Login</title><link rel="icon" href="favicon.ico" sizes="any"><link rel="icon" type="image/png" href="logo-192.png"><link rel="apple-touch-icon" href="apple-touch-icon.png"><link rel="stylesheet" href="style.css"></head><body class="login-page"><form class="login-card" method="post"><h1>Admin Login</h1><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><label>Email<input type="email" name="email" required></label><label>Password<input type="password" name="password" required></label><button class="btn" type="submit">Login</button><p><a href="index.php">Back to store</a></p></form></body></html>z