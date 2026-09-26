</main>
<?php
$w1_name = setting('whatsapp_contact1_name', 'Msaidizi 1');
$w1_phone = setting('whatsapp_contact1_phone', '0744409418');
$w2_name = setting('whatsapp_contact2_name', 'Msaidizi 2');
$w2_phone = setting('whatsapp_contact2_phone', '');
?>
<footer class="footer">

    <div class="footer-content">

        <div class="footer-brand">
            <h2><?= e(setting('business_name','Campus Bites')) ?> 🍹</h2>
            <p><?= e(t('footer_tagline')) ?></p>
        </div>

        <div class="footer-links">
            <h3><?= e(t('footer_quick_links')) ?></h3>
            <a href="index.php"><?= e(t('nav_home')) ?></a>
            <a href="products.php"><?= e(t('nav_products')) ?></a>
            <a href="cart.php"><?= e(t('nav_cart')) ?></a>
            <a href="track-order.php"><?= e(t('nav_track')) ?></a>
        </div>

        <div class="footer-contact">
            <h3><?= e(t('footer_contact_us')) ?></h3>
            <?php if ($w1_phone !== ''): ?>
                <a href="<?= e(whatsapp_link($w1_phone)) ?>" target="_blank" rel="noopener" class="whatsapp-link">
                    🟢 <?= e($w1_name) ?> (👨) — <?= e($w1_phone) ?>
                </a>
            <?php endif; ?>
            <?php if ($w2_phone !== ''): ?>
                <a href="<?= e(whatsapp_link($w2_phone)) ?>" target="_blank" rel="noopener" class="whatsapp-link">
                    🟢 <?= e($w2_name) ?> (👩) — <?= e($w2_phone) ?>
                </a>
            <?php endif; ?>
            <p>📍 <?= e(setting('pickup_location','Campus Pickup Point')) ?></p>
        </div>

        <div class="footer-admin">
            <h3><?= e(t('footer_admin')) ?></h3>
            <a href="admin_login.php"><?= e(t('footer_admin_login')) ?></a>
        </div>

    </div>

    <div class="footer-bottom">
        <p>
            © <?= date('Y') ?> <?= e(setting('business_name','Campus Bites')) ?>. <?= e(t('footer_rights')) ?>
        </p>
        <p>
            <?= e(t('footer_made_for_all')) ?>
        </p>
    </div>

</footer>
<script src="script.js"></script>
</body>
</html>
