<?php
require_once "config.php";

if (empty($_SESSION['cart'])) redirect("products.php");

$paymentNumber = setting('payment_number', '0744409418');

$errors = [];
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $college = trim($_POST['college_name'] ?? '');
    $student = trim($_POST['student_id'] ?? '');
    $method = $_POST['delivery_method'] ?? 'pickup';
    $location = trim($_POST['delivery_location'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $paymentMethod = $_POST['payment_method'] ?? 'cash';

    if ($name === '') $errors[] = "Full name is required.";
    if ($phone === '') $errors[] = "Phone number is required.";
    if ($college === '') $errors[] = "College name is required.";
    if (!in_array($method, ['pickup','delivery'], true)) $errors[] = "Invalid order method.";
    if ($method==='delivery' && $location==='') $errors[] = "Delivery location is required.";
    if (!in_array($paymentMethod, ['cash','mobile'], true)) $errors[] = "Invalid payment method.";

    if (!$errors) {
        // Calculate total and collect cart data.
        $cartRows = [];
        $total = 0;
        foreach ($_SESSION['cart'] as $key=>$qty) {
            $meta = $_SESSION['cart_meta'][$key] ?? [];
            $pid = (int)($meta['product_id'] ?? 0);
            $stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND status=1 LIMIT 1");
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $p = $stmt->get_result()->fetch_assoc();
            if (!$p) continue;
            $sub = $p['price'] * $qty;
            $total += $sub;
            $cartRows[] = [$p,$qty,$sub,$meta];
        }

        // Find next open route by cutoff. Route times are stored as TIME.
        $now = date('H:i:s');
        $stmt = $conn->prepare("SELECT * FROM routes WHERE status=1 AND cutoff_time >= ? ORDER BY arrival_time LIMIT 1");
        $stmt->bind_param("s", $now);
        $stmt->execute();
        $route = $stmt->get_result()->fetch_assoc();
        if (!$route) {
            $route = $conn->query("SELECT * FROM routes WHERE status=1 ORDER BY arrival_time LIMIT 1")->fetch_assoc();
            $routeTomorrow = true;
        } else {
            $routeTomorrow = false;
        }
        if (!$route) $errors[] = "No active route is available right now.";

        $pickup = $conn->query("SELECT * FROM pickup_points WHERE status=1 ORDER BY id LIMIT 1")->fetch_assoc();

        if (!$errors) {
            $orderNumber = 'CB-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)),0,6));
            $deliveryLocation = $method==='delivery' ? $location : ($pickup['location'] ?? '');
            $stmt = $conn->prepare("INSERT INTO orders (order_number, customer_name, phone, college_name, student_id, delivery_method, delivery_location, payment_method, route_id, pickup_point_id, status, total_amount, notes) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
            $status='Confirmed';
            $routeId=(int)$route['id']; $pickupId=(int)($pickup['id'] ?? 0);
            $stmt->bind_param("ssssssssiisds", $orderNumber,$name,$phone,$college,$student,$method,$deliveryLocation,$paymentMethod,$routeId,$pickupId,$status,$total,$notes);
            $stmt->execute();
            $orderId = $stmt->insert_id;

            foreach ($cartRows as [$p,$qty,$sub,$meta]) {
                $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, unit_price, subtotal, chili_option, tomato_option, preparation) VALUES (?,?,?,?,?,?,?,?)");
                $chili=$meta['chili'] ?? ''; $tomato=$meta['tomato'] ?? ''; $prep=$meta['preparation'] ?? '';
                $pid=(int)$p['id']; $price=(float)$p['price'];
                $stmt2->bind_param("iiiddsss",$orderId,$pid,$qty,$price,$sub,$chili,$tomato,$prep);
                $stmt2->execute();
            }

            $_SESSION['cart'] = [];
            $_SESSION['cart_meta'] = [];
            $_SESSION['last_order'] = $orderNumber;
            redirect("order-success.php?order=".urlencode($orderNumber));
        }
    }
}

include "header.php";
?>
<section class="page-title"><p class="eyebrow"><?= e(t('checkout_eyebrow')) ?></p><h1><?= e(t('checkout_heading')) ?></h1></section>
<?php if ($errors): ?><div class="alert error"><?php foreach($errors as $er): ?><div><?= e($er) ?></div><?php endforeach; ?></div><?php endif; ?>

<form method="post" class="form-card">
  <div class="form-grid">
    <label><?= e(t('full_name')) ?><input name="customer_name" required value="<?= e($_POST['customer_name'] ?? '') ?>"></label>
    <label><?= e(t('phone_number')) ?><input name="phone" required value="<?= e($_POST['phone'] ?? '') ?>"></label>
    <label><?= e(t('college_name')) ?><input name="college_name" required value="<?= e($_POST['college_name'] ?? setting('college_name','')) ?>"></label>
    <label><?= e(t('student_id_optional')) ?><input name="student_id" value="<?= e($_POST['student_id'] ?? '') ?>"></label>
  </div>

  <label><?= e(t('order_method')) ?>
    <select name="delivery_method" id="delivery_method" onchange="toggleDelivery()">
      <option value="pickup"><?= e(t('pickup_option')) ?></option>
      <option value="delivery"><?= e(t('delivery_option')) ?></option>
    </select>
  </label>

  <div id="deliveryBox" class="hidden">
    <label><?= e(t('delivery_location_label')) ?><input name="delivery_location" value="<?= e($_POST['delivery_location'] ?? '') ?>" placeholder="<?= e(t('delivery_location_placeholder')) ?>"></label>
  </div>

  <label><?= e(t('payment_method_label')) ?>
    <select name="payment_method" id="payment_method" onchange="togglePayment()">
      <option value="cash"><?= e(t('payment_cash')) ?></option>
      <option value="mobile"><?= e(t('payment_mobile')) ?></option>
    </select>
  </label>
  <div id="paymentBox" class="hidden payment-note">
    <?= e(t('payment_mobile_instructions')) ?> <strong><?= e($paymentNumber) ?></strong>
  </div>

  <label><?= e(t('order_notes_optional')) ?><textarea name="notes" rows="3" placeholder="<?= e(t('order_notes_placeholder')) ?>"><?= e($_POST['notes'] ?? '') ?></textarea></label>
  <button class="btn" type="submit"><?= e(t('place_order')) ?></button>
</form>
<?php include "footer.php"; ?>
