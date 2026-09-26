<?php
require_once "config.php";
$orderNo = trim($_GET['order'] ?? '');
$stmt = $conn->prepare("SELECT o.*, r.name route_name, r.arrival_time, r.pickup_start, r.pickup_end, p.name pickup_name, p.point_number, p.location pickup_location FROM orders o LEFT JOIN routes r ON o.route_id=r.id LEFT JOIN pickup_points p ON o.pickup_point_id=p.id WHERE o.order_number=? LIMIT 1");
$stmt->bind_param("s",$orderNo); $stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$paymentNumber = setting('payment_number', '0744409418');
include "header.php";
?>
<?php if (!$order): ?>
  <div class="empty"><h2><?= e(t('order_not_found_title')) ?></h2><a class="btn" href="products.php"><?= e(t('back_to_menu')) ?></a></div>
<?php else: ?>
<section class="success">
  <div class="success-icon">✓</div>
  <p class="eyebrow"><?= e(t('order_received')) ?></p>
  <h1><?= e(t('order_success_thanks')) ?>, <?= e($order['customer_name']) ?>!</h1>
  <p><?= e(t('order_confirmed_msg')) ?></p>
  <div class="order-summary">
    <div><span><?= e(t('order_number_label')) ?></span><strong><?= e($order['order_number']) ?></strong></div>
    <div><span><?= e(t('route_label')) ?></span><strong><?= e($order['route_name']) ?></strong></div>
    <div><span><?= e(t('arrival_label')) ?></span><strong><?= e(date('H:i', strtotime($order['arrival_time']))) ?></strong></div>
    <div><span><?= e(t('pickup_window_label')) ?></span><strong><?= e(date('H:i', strtotime($order['pickup_start']))) ?>–<?= e(date('H:i', strtotime($order['pickup_end']))) ?></strong></div>
    <div><span><?= e(t('method_label')) ?></span><strong><?= e(ucfirst($order['delivery_method'])) ?></strong></div>
    <?php if ($order['delivery_method']==='pickup'): ?>
      <div><span><?= e(t('pickup_point_label')) ?></span><strong><?= e($order['pickup_name']) ?> · <?= e($order['point_number']) ?></strong></div>
      <div><span><?= e(t('location_label')) ?></span><strong><?= e($order['pickup_location']) ?></strong></div>
    <?php endif; ?>
    <div><span><?= e(t('payment_label')) ?></span><strong>
      <?php if (($order['payment_method'] ?? 'cash') === 'mobile'): ?>
        <?= e(t('payment_mobile')) ?> (<?= e($paymentNumber) ?>)
      <?php else: ?>
        <?= e(t('payment_cash')) ?>
      <?php endif; ?>
    </strong></div>
    <div><span><?= e(t('status_label')) ?></span><strong><?= e($order['status']) ?></strong></div>
    <div><span><?= e(t('total_label')) ?></span><strong>TZS <?= number_format($order['total_amount']) ?></strong></div>
  </div>
  <a class="btn" href="track-order.php?order=<?= urlencode($order['order_number']) ?>"><?= e(t('track_order_btn')) ?></a>
</section>
<?php endif; ?>
<?php include "footer.php"; ?>
