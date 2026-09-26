<?php include "admin_header.php";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $action=$_POST['action']??'';
 if($action==='save'){ $id=(int)($_POST['id']??0);$name=trim($_POST['name']);$phone=trim($_POST['phone']);$gender=trim($_POST['gender']);$availability=trim($_POST['availability']);$status=trim($_POST['status']);$email=trim($_POST['email']);$hash=password_hash($_POST['password'] ?: '123456',PASSWORD_DEFAULT);
  if($id){$st=$conn->prepare("UPDATE delivery_people SET name=?,phone=?,gender=?,email=?,availability=?,status=? WHERE id=?");$st->bind_param("ssssssi",$name,$phone,$gender,$email,$availability,$status,$id);}
  else{$st=$conn->prepare("INSERT INTO delivery_people(name,phone,gender,email,password_hash,availability,status) VALUES(?,?,?,?,?,?,?)");$st->bind_param("sssssss",$name,$phone,$gender,$email,$hash,$availability,$status);}
  $st->execute();
 }
 if($action==='assign'){ $orderId=(int)$_POST['order_id'];$deliveryId=(int)$_POST['delivery_id'];$st=$conn->prepare("UPDATE orders SET delivery_person_id=?, status='Confirmed' WHERE id=? AND delivery_method='delivery'");$st->bind_param("ii",$deliveryId,$orderId);$st->execute();}
 redirect("admin_delivery.php");
}
$people=$conn->query("SELECT * FROM delivery_people ORDER BY id");
$orders=$conn->query("SELECT o.*,d.name delivery_name FROM orders o LEFT JOIN delivery_people d ON o.delivery_person_id=d.id WHERE o.delivery_method='delivery' ORDER BY o.created_at DESC LIMIT 100");
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Delivery</h1></div>
<form method="post" class="form-card"><input type="hidden" name="action" value="save"><div class="form-grid"><label>Name<input name="name" required></label><label>Phone<input name="phone" required></label><label>Gender<select name="gender"><option>Male</option><option>Female</option></select></label><label>Email<input type="email" name="email"></label><label>Availability<input name="availability" placeholder="e.g. 10:00-12:00"></label><label>Status<select name="status"><option>Available</option><option>Busy</option><option>In Class</option><option>Off Duty</option></select></label><label>Password<input type="password" name="password" placeholder="Default 123456"></label></div><button class="btn">Add delivery person</button></form>
<h2>Delivery people</h2><div class="table-wrap"><table><thead><tr><th>Name</th><th>Phone</th><th>Status</th><th>Availability</th></tr></thead><tbody><?php while($d=$people->fetch_assoc()): ?><tr><td><?= e($d['name']) ?></td><td><?= e($d['phone']) ?></td><td><?= e($d['status']) ?></td><td><?= e($d['availability']) ?></td></tr><?php endwhile; ?></tbody></table></div>
<h2>Delivery orders</h2><div class="table-wrap"><table><thead><tr><th>Order</th><th>Customer</th><th>Location</th><th>Assigned</th><th>Assign</th></tr></thead><tbody>
<?php $people2=$conn->query("SELECT id,name,status FROM delivery_people"); while($o=$orders->fetch_assoc()): ?><tr><td><?= e($o['order_number']) ?></td><td><?= e($o['customer_name']) ?><br><?= e($o['phone']) ?></td><td><?= e($o['delivery_location']) ?></td><td><?= e($o['delivery_name']??'Not assigned') ?></td><td><form method="post" class="inline"><input type="hidden" name="action" value="assign"><input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>"><select name="delivery_id"><?php $people2->data_seek(0); while($d=$people2->fetch_assoc()): ?><option value="<?= (int)$d['id'] ?>"><?= e($d['name']) ?></option><?php endwhile; ?></select><button class="tiny-link">Assign</button></form></td></tr><?php endwhile; ?></tbody></table></div>
<?php include "admin_footer.php"; ?>