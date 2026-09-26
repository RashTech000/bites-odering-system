<?php
require_once "config.php";
$products = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY category, id");
include "header.php";
?>

<section class="hero">
  <div>
    
    <p class="eyebrow"><?= e(setting('college_name','Sample College')) ?></p>
    <h1><?= e(setting('business_name','Campus Bites')) ?></h1>
    <p><?= e(setting('business_description','Fresh fried cassava and juice, ordered simply and collected or delivered in batches.')) ?></p>
    <a class="btn" href="products.php"><?= e(t('hero_order_now')) ?></a>
  </div>
</section>

<section class="section">
  <div class="section-head">
    <div>
      <p class="eyebrow"><?= e(t('menu_eyebrow')) ?></p>
      <h2><?= e(t('menu_heading')) ?></h2>
    </div>
    <a href="products.php"><?= e(t('view_all')) ?></a>
  </div>
  <div class="product-grid">
    <?php while($p = $products->fetch_assoc()): ?>
      <article class="product-card">
        <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
        <div class="product-body">
          <span class="tag"><?= e($p['category']) ?></span>
          <h3><?= e($p['name']) ?></h3>
          <p class="price">TZS <?= number_format($p['price']) ?></p>
          <a class="btn btn-small" href="products.php"><?= e(t('choose')) ?></a>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</section>

<section class="info-grid">
  <div class="info-card">
    <h3><?= e(t('pickup_heading')) ?></h3>
    <p><?= e(setting('pickup_name','Campus Juice Point')) ?></p>
    <small><?= e(setting('pickup_location','Near Main Gate')) ?> · <?= e(setting('pickup_number','CP-001')) ?></small>
  </div>
  <div class="info-card">
    <h3><?= e(t('batch_heading')) ?></h3>
    <p><?= e(t('batch_text')) ?></p>
  </div>
  <div class="info-card">
    <h3><?= e(t('simple_heading')) ?></h3>
    <p><?= e(t('simple_text')) ?></p>
  </div>
</section>
<?php include "footer.php"; ?>
