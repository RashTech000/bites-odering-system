<?php include "admin_header.php";
$msg='';
$uploadDir=__DIR__.'/';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $action=$_POST['action']??'';
  if($action==='save'){
    $id=(int)($_POST['id']??0); $name=trim($_POST['name']); $category=$_POST['category']; $price=(float)$_POST['price']; $desc=trim($_POST['description']); $status=isset($_POST['status'])?1:0;
    $image=$_POST['old_image']??'';
    if(!empty($_FILES['image']['name']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
      $ext=strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
      if(in_array($ext,['jpg','jpeg','png','webp'],true)){
        $image=uniqid('prod_',true).'.'.$ext; move_uploaded_file($_FILES['image']['tmp_name'],$uploadDir.$image);
      }
    }
    if($id){$st=$conn->prepare("UPDATE products SET name=?,category=?,price=?,image=?,description=?,status=? WHERE id=?");$st->bind_param("ssdssii",$name,$category,$price,$image,$desc,$status,$id);}
    else{$st=$conn->prepare("INSERT INTO products(name,category,price,image,description,status) VALUES(?,?,?,?,?,?)");$st->bind_param("ssdssi",$name,$category,$price,$image,$desc,$status);}
    $st->execute(); $msg='Product saved.';
  }
  if($action==='delete'){ $id=(int)$_POST['id']; $st=$conn->prepare("DELETE FROM products WHERE id=?");$st->bind_param("i",$id);$st->execute(); }
}
$edit=null;if(isset($_GET['edit'])){$id=(int)$_GET['edit'];$st=$conn->prepare("SELECT * FROM products WHERE id=?");$st->bind_param("i",$id);$st->execute();$edit=$st->get_result()->fetch_assoc();}
$products=$conn->query("SELECT * FROM products ORDER BY id");
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Products</h1></div>
<?php if($msg): ?><div class="alert success-msg"><?= e($msg) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data" class="form-card">
<input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><input type="hidden" name="old_image" value="<?= e($edit['image']??'') ?>">
<div class="form-grid"><label>Name<input name="name" required value="<?= e($edit['name']??'') ?>"></label><label>Category<select name="category"><option <?= (($edit['category']??'')==='Mihogo')?'selected':'' ?>>Mihogo</option><option <?= (($edit['category']??'')==='Juice')?'selected':'' ?>>Juice</option></select></label><label>Price<input type="number" name="price" required value="<?= e($edit['price']??'') ?>"></label><label>Image<input type="file" name="image" accept="image/*"></label></div>
<label>Description<textarea name="description" rows="2"><?= e($edit['description']??'') ?></textarea></label><label><input type="checkbox" name="status" <?= (($edit['status']??1)?'checked':'') ?>> Available</label><button class="btn" type="submit"><?= $edit?'Update':'Add' ?> product</button> <?php if($edit): ?><a class="btn btn-dark" href="admin_products.php">Cancel</a><?php endif; ?></form>
<div class="table-wrap"><table><thead><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Status</th><th></th></tr></thead><tbody>
<?php while($p=$products->fetch_assoc()): ?><tr><td><img class="thumb" src="<?= e($p['image']) ?>"></td><td><?= e($p['name']) ?></td><td><?= e($p['category']) ?></td><td>TZS <?= number_format($p['price']) ?></td><td><?= $p['status']?'Available':'Hidden' ?></td><td><a class="tiny-link" href="?edit=<?= (int)$p['id'] ?>">Edit</a><form method="post" class="inline"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>"><button class="link-btn" onclick="return confirm('Delete product?')">Delete</button></form></td></tr><?php endwhile; ?></tbody></table></div>
<?php include "admin_footer.php"; ?>