<?php
require_once "./utils/init.php";

// Only admins can toggle
if (!isset($_SESSION['uzivatel']) || (int)$_SESSION['uzivatel']['role_id'] !== 1) {
    header("Location: dashboard.php");
    exit();
}

// admin_mode was set to true on login — just flip it
$_SESSION['admin_mode'] = !$_SESSION['admin_mode'];

$ref = $_SERVER['HTTP_REFERER'] ?? './dashboard.php';
header("Location: " . $ref);
exit();
