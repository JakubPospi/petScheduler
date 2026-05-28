<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel']) || (int)$_SESSION['uzivatel']['role_id'] !== 1) {
    header("Location: dashboard.php");
    exit();
}

// Toggle the admin_mode session variable
$_SESSION['admin_mode'] = !($_SESSION['admin_mode'] ?? true);

// Redirect back to wherever they came from
$ref = $_SERVER['HTTP_REFERER'] ?? './dashboard.php';
header("Location: " . $ref);
exit();
