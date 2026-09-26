<?php
require_once "config.php";
$orderNo = trim($_GET['order'] ?? $_POST['order_number'] ?? '');
$order = null;
if ($orderNo !== '') {
    $stmt=$conn->prepare("SELECT o.*, r.name route_name, r.arrival_time, r.pickup_start, r.pickup_end, p.name pickup_name, p.point_number, p.location pickup_location FROM orders o LEFT JOIN routes r ON o.route_id=r.id LEFT JOIN pickup_points p ON o.pickup_point_id=p.id WHERE o.order_number=? LIMIT 1");
    $stmt->bind_param("s",$orderNo); $stmt->execute(); $order=$stmt->get_result()->fetch_assoc();
}
include "header.php";
$statusKeys = [
    'Pending'=>'status_pending','Confirmed'=>'status_confirmed','Processing'=>'status_processing',
    'Ready'=>'status_ready','Collected'=>'status_collected','Delivered'=>'status_delivered','Cancelled'=>'status_cancelled'
];
?>
<section class="page-title"><p class="eyebrow"><?= e(t('track_eyebrow')) ?></p><h1><?= e(t('track_heading')) ?></h1></section>
<form method="post" class="track-form">
  <input name="order_number" placeholder="<?= e(t('track_placeholder')) ?>" value="<?= e($orderNo) ?>" required>
  <button class="btn" type="submit"><?= e(t('track_button')) ?></button>
</form>
<?php if ($order): ?>
<div class="tracking-card">
  <div class="track-top"><strong><?= e($order['order_number']) ?></strong><span class="status"><?= e($statusKeys[$order['status']] ?? null ? t($statusKeys[$order['status']]) : $order['status']) ?></span></div>
  <div class="timeline">
    <?php
      $steps=['Pending','Confirmed','Processing','Ready','Collected'];
      $current=array_search($order['status'],$steps,true);
      if ($current===false) $current=1;
      foreach($steps as $i=>$step):
    ?>
      <div class="step <?= $i <= $current ? 'done':'' ?>"><span><?= $i+1 ?></span><div><strong><?= e(t($statusKeys[$step])) ?></strong></div></div>
    <?php endforeach; ?>
  </div>
  <div class="order-summary">
    <div><span><?= e(t('route_label')) ?></span><strong><?= e($order['route_name']) ?></strong></div>
    <div><span><?= e(t('pickup_window_label')) ?></span><strong><?= e(date('H:i', strtotime($order['pickup_start']))) ?>–<?= e(date('H:i', strtotime($order['pickup_end']))) ?></strong></div>
    <div><span><?= e(t('total_label')) ?></span><strong>TZS <?= number_format($order['total_amount']) ?></strong></div>
    <?php if ($order['delivery_method']==='pickup'): ?>
      <div><span><?= e(t('pickup_heading')) ?></span><strong><?= e($order['pickup_name']) ?> · <?= e($order['point_number']) ?></strong></div>
    <?php endif; ?>
  </div>
</div>
<?php elseif ($orderNo): ?>
<div class="alert error"><?= e(t('order_not_found')) ?></div>
<?php endif; ?>
<?php include "footer.php"; ?>
