<?php
require_once "./utils/init.php";

if (
    !isset($_SESSION['uzivatel']) ||
    (int)$_SESSION['uzivatel']['role_id'] !== 1
) {
    header("Location: dashboard.php");
    exit();
}


if (!isset($_SESSION['admin_mode'])) {
    $_SESSION['admin_mode'] = true;
}

$_SESSION['admin_mode'] = !$_SESSION['admin_mode'];

$ref = $_SERVER['HTTP_REFERER'] ?? './dashboard.php';
header("Location: " . $ref);
echo $_SESSION['admin_mode'];
exit();
?>