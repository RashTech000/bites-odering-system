<?php include "admin_header.php";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['id']??0);$name=trim($_POST['name']);$number=trim($_POST['point_number']);$location=trim($_POST['location']);$description=trim($_POST['description']);$phone=trim($_POST['phone']);$status=isset($_POST['status'])?1:0;
 if($id){$st=$conn->prepare("UPDATE pickup_points SET name=?,point_number=?,location=?,description=?,phone=?,status=? WHERE id=?");$st->bind_param("sssssii",$name,$number,$location,$description,$phone,$status,$id);}
 else{$st=$conn->prepare("INSERT INTO pickup_points(name,point_number,location,description,phone,status) VALUES(?,?,?,?,?,?)");$st->bind_param("sssssi",$name,$number,$location,$description,$phone,$status);}
 $st->execute(); redirect("admin_pickup_points.php");
}
$edit=null;if(isset($_GET['edit'])){$id=(int)$_GET['edit'];$st=$conn->prepare("SELECT * FROM pickup_points WHERE id=?");$st->bind_param("i",$id);$st->execute();$edit=$st->get_result()->fetch_assoc();}
$points=$conn->query("SELECT * FROM pickup_points ORDER BY id");
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Pickup points</h1></div>
<form method="post" class="form-card"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><div class="form-grid"><label>Name<input name="name" required value="<?= e($edit['name']??'Campus Juice Point') ?>"></label><label>Point number<input name="point_number" required value="<?= e($edit['point_number']??'CP-001') ?>"></label><label>Location<input name="location" required value="<?= e($edit['location']??'Near Main Gate') ?>"></label><label>Phone<input name="phone" value="<?= e($edit['phone']??'') ?>"></label></div><label>Description<textarea name="description"><?= e($edit['description']??'') ?></textarea></label><label><input type="checkbox" name="status" <?= (($edit['status']??1)?'checked':'') ?>> Active</label><button class="btn">Save pickup point</button></form>
<div class="table-wrap"><table><thead><tr><th>Name</th><th>Number</th><th>Location</th><th>Status</th><th></th></tr></thead><tbody><?php while($p=$points->fetch_assoc()): ?><tr><td><?= e($p['name']) ?></td><td><?= e($p['point_number']) ?></td><td><?= e($p['location']) ?></td><td><?= $p['status']?'Active':'Off' ?></td><td><a class="tiny-link" href="?edit=<?= (int)$p['id'] ?>">Edit</a></td></tr><?php endwhile; ?></tbody></table></div>
<?php include "admin_footer.php"; ?>