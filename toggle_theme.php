<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

// Cycle: dark → light → dark
$current = $_SESSION['theme'] ?? 'dark';
$_SESSION['theme'] = ($current === 'dark') ? 'light' : 'dark';

$ref = $_SERVER['HTTP_REFERER'] ?? './dashboard.php';
header("Location: " . $ref);
exit();
