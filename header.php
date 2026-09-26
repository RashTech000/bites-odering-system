<?php
require_once __DIR__ . '/config.php';
$business = setting('business_name', 'Campus Bites');
$college = setting('college_name', 'Sample College');
?>
<!doctype html>
<html lang="<?= e(current_lang()) ?>">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($business) ?></title>
<link rel="icon" href="favicon.ico" sizes="any">
<link rel="icon" type="image/png" href="logo-192.png">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
  
  <div class="container nav">
          <img id="image" src="logo.jpg" alt="logo">

    <a class="brand" style="margin-left:-400px; " href="index.php"><?= e($business) ?></a>
    <nav class="navbar">
      <!-- <img id="image" src="logo.jpg" alt="logo"> -->
      <a href="index.php"><?= e(t('nav_home')) ?></a>
      <a href="products.php"><?= e(t('nav_products')) ?></a>
      <a href="track-order.php"><?= e(t('nav_track')) ?></a>
      <a class="cart-link" href="cart.php">🛒<?= e(t('nav_cart')) ?> <span><?= cart_count() ?></span></a>
      <a href="admin_login.php">🔐 <?= e(t('nav_admin')) ?></a>
      <span class="lang-switch">
        <a href="<?= e(lang_switch_url('sw')) ?>" class="<?= current_lang()==='sw'?'active':'' ?>">SW</a>
        <span>|</span>
        <a href="<?= e(lang_switch_url('en')) ?>" class="<?= current_lang()==='en'?'active':'' ?>">EN</a>
      </span>
    </nav>
  </div>
</header>
<main class="container">
