<?php
require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $id = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        $stmt = $conn->prepare("SELECT id FROM products WHERE id=? AND status=1 LIMIT 1");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            $key = $id . '|' . ($_POST['chili'] ?? '') . '|' . ($_POST['tomato'] ?? '') . '|' . ($_POST['preparation'] ?? '');
            $_SESSION['cart'][$key] = ($_SESSION['cart'][$key] ?? 0) + $qty;
            $_SESSION['cart_meta'][$key] = [
                'product_id'=>$id,
                'chili'=>$_POST['chili'] ?? '',
                'tomato'=>$_POST['tomato'] ?? '',
                'preparation'=>$_POST['preparation'] ?? ''
            ];
        }
    } elseif ($action === 'remove') {
        $key = $_POST['key'] ?? '';
        unset($_SESSION['cart'][$key], $_SESSION['cart_meta'][$key]);
    } elseif ($action === 'update') {
        foreach (($_POST['qty'] ?? []) as $key=>$qty) {
            $qty = (int)$qty;
            if ($qty <= 0) {
                unset($_SESSION['cart'][$key], $_SESSION['cart_meta'][$key]);
            } else {
                $_SESSION['cart'][$key] = $qty;
            }
        }
    }
    redirect("cart.php");
}

$items = [];
$total = 0;
foreach (($_SESSION['cart'] ?? []) as $key=>$qty) {
    $meta = $_SESSION['cart_meta'][$key] ?? [];
    $pid = (int)($meta['product_id'] ?? 0);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $pid);
    $stmt->execute();
    $p = $stmt->get_result()->fetch_assoc();
    if (!$p) continue;
    $sub = $p['price'] * $qty;
    $total += $sub;
    $items[] = ['key'=>$key,'p'=>$p,'qty'=>$qty,'sub'=>$sub,'meta'=>$meta];
}
include "header.php";
?>
<section class="page-title"><p class="eyebrow"><?= e(t('cart_eyebrow')) ?></p><h1><?= e(t('cart_heading')) ?></h1></section>

<?php if (!$items): ?>
  <div class="empty">
    <h2><?= e(t('cart_empty_heading')) ?></h2>
    <a class="btn" href="products.php"><?= e(t('browse_menu')) ?></a>
  </div>
<?php else: ?>
<form method="post" class="cart-list">
  <input type="hidden" name="action" value="update">
  <?php foreach($items as $item): ?>
    <div class="cart-item">
      <img src="<?= e($item['p']['image']) ?>" alt="">
      <div class="grow">
        <h3><?= e($item['p']['name']) ?></h3>
        <p>TZS <?= number_format($item['p']['price']) ?> <?= e(t('each_label')) ?></p>
        <?php if ($item['p']['category']==='Mihogo'): ?>
          <small><?= e($item['meta']['preparation']) ?> · <?= e($item['meta']['chili']) ?> · <?= e($item['meta']['tomato']) ?></small>
        <?php endif; ?>
      </div>
      <input class="qty" type="number" name="qty[<?= e($item['key']) ?>]" min="0" value="<?= (int)$item['qty'] ?>">
      <strong>TZS <?= number_format($item['sub']) ?></strong>
      <button type="submit" formaction="cart.php" name="action" value="remove" class="link-btn"><?= e(t('remove')) ?></button>
    </div>
  <?php endforeach; ?>
  <div class="cart-total"><span><?= e(t('total_label')) ?></span><strong>TZS <?= number_format($total) ?></strong></div>
  <button class="btn" type="submit"><?= e(t('update_cart')) ?></button>
  <a class="btn btn-dark" href="checkout.php"><?= e(t('proceed_checkout')) ?></a>
</form>
<?php endif; ?>
<?php include "footer.php"; ?>
