<?php include "admin_header.php";
if($_SERVER['REQUEST_METHOD']==='POST'){
 $keys=['business_name','college_name','business_phone','pickup_name','pickup_number','pickup_location','business_description',
        'payment_number','whatsapp_contact1_name','whatsapp_contact1_phone','whatsapp_contact2_name','whatsapp_contact2_phone'];
 foreach($keys as $k){$v=trim($_POST[$k]??'');$st=$conn->prepare("INSERT INTO settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)");$st->bind_param("ss",$k,$v);$st->execute();}
 $msg='Settings saved.';
}
?>
<div class="page-title"><p class="eyebrow">ADMIN</p><h1>Settings</h1></div>
<?php if(!empty($msg)): ?><div class="alert success-msg"><?= e($msg) ?></div><?php endif; ?>
<form method="post" class="form-card"><div class="form-grid">
<label>Business name<input name="business_name" value="<?= e(setting('business_name','Campus Bites')) ?>"></label>
<label>College name<input name="college_name" value="<?= e(setting('college_name','Sample College')) ?>"></label>
<label>Phone number<input name="business_phone" value="<?= e(setting('business_phone','')) ?>"></label>
<label>Pickup point name<input name="pickup_name" value="<?= e(setting('pickup_name','Campus Juice Point')) ?>"></label>
<label>Pickup point number<input name="pickup_number" value="<?= e(setting('pickup_number','CP-001')) ?>"></label>
<label>Pickup location<input name="pickup_location" value="<?= e(setting('pickup_location','Near Main Gate')) ?>"></label>
</div><label>Business description<textarea name="business_description" rows="3"><?= e(setting('business_description','Fresh fried cassava and juice, ordered simply.')) ?></textarea></label>

<h2>Payment</h2>
<div class="form-grid">
<label>Mobile payment number (shown at checkout)<input name="payment_number" value="<?= e(setting('payment_number','0744409418')) ?>"></label>
</div>

<h2>Footer WhatsApp contacts</h2>
<div class="form-grid">
<label>Contact 1 name (Male)<input name="whatsapp_contact1_name" value="<?= e(setting('whatsapp_contact1_name','Msaidizi 1')) ?>"></label>
<label>Contact 1 WhatsApp number<input name="whatsapp_contact1_phone" value="<?= e(setting('whatsapp_contact1_phone','0744409418')) ?>"></label>
<label>Contact 2 name (Female)<input name="whatsapp_contact2_name" value="<?= e(setting('whatsapp_contact2_name','Msaidizi 2')) ?>"></label>
<label>Contact 2 WhatsApp number<input name="whatsapp_contact2_phone" value="<?= e(setting('whatsapp_contact2_phone','')) ?>"></label>
</div>

<button class="btn">Save settings</button></form>
<?php include "admin_footer.php"; ?>
