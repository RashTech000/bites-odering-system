<?php include "admin_header.php";
$stats=$conn->query("SELECT COUNT(*) total, COALESCE(SUM(total_amount),0) sales FROM orders WHERE DATE(created_at)=CURDATE()")->fetch_assoc();
$pending=$conn->query("SELECT COUNT(*) c FROM orders WHERE DATE(created_at)=CURDATE() AND status='Pending'")->fetch_assoc()['c'];
$ready=$conn->query("SELECT COUNT(*) c FROM orders WHERE DATE(created_at)=CURDATE() AND status='Ready'")->fetch_assoc()['c'];
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Dashboard</h1></div>
<div class="stats"><div><span>Today's orders</span><strong><?= (int)$stats['total'] ?></strong></div><div><span>Today's sales</span><strong>TZS <?= number_format($stats['sales']) ?></strong></div><div><span>Pending</span><strong><?= (int)$pending ?></strong></div><div><span>Ready</span><strong><?= (int)$ready ?></strong></div></div>
<div class="admin-grid">
<div class="admin-card"><h2>Quick actions</h2><a class="btn btn-small" href="admin_orders.php">Manage orders</a> <a class="btn btn-small" href="admin_products.php">Manage products</a> <a class="btn btn-small" href="admin_settings.php">Edit settings</a></div>
<div class="admin-card"><h2>Store</h2><p><strong><?= e(setting('business_name')) ?></strong><br><?= e(setting('college_name')) ?><br><?= e(setting('pickup_name')) ?> · <?= e(setting('pickup_number')) ?></p></div>
</div>
<?php include "admin_footer.php"; ?>