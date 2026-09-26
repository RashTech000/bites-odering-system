<?php include "admin_header.php";
if (isset($_GET['status'])) {
  $id=(int)$_GET['id']; $status=$_GET['status'];
  $allowed=['Pending','Confirmed','Processing','Ready','Collected','Delivered','Cancelled'];
  if(in_array($status,$allowed,true)){ $st=$conn->prepare("UPDATE orders SET status=? WHERE id=?"); $st->bind_param("si",$status,$id); $st->execute(); }
}
$orders=$conn->query("SELECT o.*,r.name route_name FROM orders o LEFT JOIN routes r ON o.route_id=r.id ORDER BY o.created_at DESC LIMIT 200");
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Orders</h1></div>
<div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Route</th><th>Method</th><th>Status</th><th>Notes</th><th>Actions</th></tr></thead><tbody>
<?php while($o=$orders->fetch_assoc()): ?>
<tr><td><strong><?= e($o['order_number']) ?></strong><br><small><?= e($o['created_at']) ?></small></td><td><?= e($o['customer_name']) ?><br><?= e($o['phone']) ?></td><td>TZS <?= number_format($o['total_amount']) ?></td><td><?= e($o['route_name']) ?></td><td><?= e($o['delivery_method']) ?></td><td><span class="status"><?= e($o['status']) ?> </span></td> <td class="notes-cell" title="<?= e($o['notes']) ?>"><?= e($o['notes'] ? mb_strimwidth($o['notes'],0,40,'…') : '—') ?></td><td>


<?php foreach(['Processing','Ready','Collected','Delivered','Cancelled'] as $s): ?><a class="tiny-link" href="?id=<?= (int)$o['id'] ?>&status=<?= urlencode($s) ?>"><?= e($s) ?></a> <?php endforeach; ?>
</td></tr>
<?php endwhile; ?></tbody></table></div>
<?php include "admin_footer.php"; ?>