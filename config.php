<?php
session_start();

$host = "localhost";       // InfinityFree: use the MySQL hostname they provide
$user = "root";            // InfinityFree: use your MySQL username
$pass = "";                // InfinityFree: use your MySQL password
$db   = "campus_bites";    // InfinityFree: use your MySQL database name

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

require_once __DIR__ . '/lang.php';

// Kiswahili ni lugha ya default; mtumiaji anaweza kubadili kwa English.
if (isset($_GET['lang']) && in_array($_GET['lang'], ['sw','en'], true)) {
    $_SESSION['lang'] = $_GET['lang'];
}
if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = 'sw';
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function setting($key, $default = '') {
    global $conn;
    static $cache = [];
    if (array_key_exists($key, $cache)) return $cache[$key];
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key=? LIMIT 1");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $cache[$key] = $res ? $res['setting_value'] : $default;
    return $cache[$key];
}

function cart_count() {
    return array_sum($_SESSION['cart'] ?? []);
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function admin_required() {
    if (empty($_SESSION['admin_id'])) redirect("admin_login.php");
}

function delivery_required() {
    if (empty($_SESSION['delivery_id'])) redirect("delivery_login.php");
}

// Builds a wa.me link from a Tanzanian-style phone number (e.g. 0744409418 -> 255744409418).
function whatsapp_link($phone) {
    $digits = preg_replace('/\D/', '', (string)$phone);
    if ($digits === '') return '';
    if (substr($digits, 0, 1) === '0') {
        $digits = '255' . substr($digits, 1);
    } elseif (substr($digits, 0, 3) !== '255') {
        $digits = '255' . $digits;
    }
    return 'https://wa.me/' . $digits;
}

// Keeps the current page + query string but switches language, for the SW/EN links.
function lang_switch_url($lang) {
    $qs = $_GET;
    $qs['lang'] = $lang;
    return '?' . http_build_query($qs);
}
?>
