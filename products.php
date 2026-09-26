<?php
require_once "config.php";
$products = $conn->query("SELECT * FROM products WHERE status=1 ORDER BY category, id");
include "header.php";
?>
<section class="page-title">
  <p class="eyebrow"><?= e(t('products_eyebrow')) ?></p>
  <h1><?= e(t('products_heading')) ?></h1>
  <p><?= e(t('products_subheading')) ?></p>
</section>

<div class="product-grid">
<?php while($p = $products->fetch_assoc()): ?>
  <article class="product-card">
    <img src="<?= e($p['image']) ?>" alt="<?= e($p['name']) ?>">
    <div class="product-body">
      <span class="tag"><?= e($p['category']) ?></span>
      <h3><?= e($p['name']) ?></h3>
      <p class="price">TZS <?= number_format($p['price']) ?></p>
      <?php if ($p['category'] === 'Mihogo'): ?>
        <form method="post" action="cart.php" class="mini-form">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
          <label><?= e(t('qty_label')) ?>
            <input type="number" name="quantity" min="1" value="1">
          </label>
          <label><?= e(t('chili_label')) ?>
            <select name="chili">
              <option value="Ina pilipili"><?= e(t('chili_yes')) ?></option>
              <option value="Haina pilipili"><?= e(t('chili_no')) ?></option>
            </select>
          </label>
          <label><?= e(t('tomato_label')) ?>
            <select name="tomato">
              <option value="Ina tomato"><?= e(t('tomato_yes')) ?></option>
              <option value="Haina tomato"><?= e(t('tomato_no')) ?></option>
            </select>
          </label>
          <label><?= e(t('prep_label')) ?>
            <select name="preparation">
              <option value="Kukaanga"><?= e(t('prep_fry')) ?></option>
              <option value="Kuchemsha"><?= e(t('prep_boil')) ?></option>
            </select>
          </label>
          <button class="btn btn-small" type="submit"><?= e(t('add_to_cart')) ?></button>
        </form>
      <?php else: ?>
        <form method="post" action="cart.php">
          <input type="hidden" name="action" value="add">
          <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
          <label><?= e(t('qty_label')) ?>
            <input type="number" name="quantity" min="1" value="1">
          </label>
          <button class="btn btn-small" type="submit"><?= e(t('add_to_cart')) ?></button>
        </form>
      <?php endif; ?>
    </div>
  </article>
<?php endwhile; ?>
</div>
<?php include "footer.php"; ?>
