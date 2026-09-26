<?php include "admin_header.php";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)($_POST['id']??0);$name=trim($_POST['name']);$arrival=$_POST['arrival_time'];$cutoff=$_POST['cutoff_time'];$start=$_POST['pickup_start'];$end=$_POST['pickup_end'];$status=isset($_POST['status'])?1:0;
 if($id){$st=$conn->prepare("UPDATE routes SET name=?,arrival_time=?,cutoff_time=?,pickup_start=?,pickup_end=?,status=? WHERE id=?");$st->bind_param("sssssii",$name,$arrival,$cutoff,$start,$end,$status,$id);}
 else{$st=$conn->prepare("INSERT INTO routes(name,arrival_time,cutoff_time,pickup_start,pickup_end,status) VALUES(?,?,?,?,?,?)");$st->bind_param("sssssi",$name,$arrival,$cutoff,$start,$end,$status);}
 $st->execute(); redirect("admin_routes.php");
}
$edit=null;if(isset($_GET['edit'])){$id=(int)$_GET['edit'];$st=$conn->prepare("SELECT * FROM routes WHERE id=?");$st->bind_param("i",$id);$st->execute();$edit=$st->get_result()->fetch_assoc();}
$routes=$conn->query("SELECT * FROM routes ORDER BY arrival_time");
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Routes</h1></div>
<form method="post" class="form-card"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><div class="form-grid"><label>Name<input name="name" required value="<?= e($edit['name']??'Route 1') ?>"></label><label>Arrival<input type="time" name="arrival_time" required value="<?= e($edit['arrival_time']??'03:00') ?>"></label><label>Cutoff<input type="time" name="cutoff_time" required value="<?= e($edit['cutoff_time']??'02:30') ?>"></label><label>Pickup start<input type="time" name="pickup_start" required value="<?= e($edit['pickup_start']??'03:00') ?>"></label><label>Pickup end<input type="time" name="pickup_end" required value="<?= e($edit['pickup_end']??'04:00') ?>"></label></div><label><input type="checkbox" name="status" <?= (($edit['status']??1)?'checked':'') ?>> Active</label><button class="btn" type="submit">Save route</button></form>
<div class="table-wrap"><table><thead><tr><th>Name</th><th>Arrival</th><th>Cutoff</th><th>Pickup</th><th>Status</th><th></th></tr></thead><tbody><?php while($r=$routes->fetch_assoc()): ?><tr><td><?= e($r['name']) ?></td><td><?= e(date('H:i',strtotime($r['arrival_time']))) ?></td><td><?= e(date('H:i',strtotime($r['cutoff_time']))) ?></td><td><?= e(date('H:i',strtotime($r['pickup_start']))) ?>–<?= e(date('H:i',strtotime($r['pickup_end']))) ?></td><td><?= $r['status']?'Active':'Off' ?></td><td><a class="tiny-link" href="?edit=<?= (int)$r['id'] ?>">Edit</a></td></tr><?php endwhile; ?></tbody></table></div>
<?php include "admin_footer.php"; ?>