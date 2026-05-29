<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php"); exit();
}

$uzivatel  = $_SESSION['uzivatel'];
$family_id = (int)$uzivatel['family_id'];
$role      = (int)$uzivatel['role_id'];

// Only admin or parent can add task types
if ($role !== 1 && $role !== 2) {
    header("Location: dashboard.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['type_name'] ?? ''))) {
    $name = trim($_POST['type_name']);

    // Prevent duplicates for this family
    $check = $db->prepare("SELECT id FROM task_type WHERE name = ? AND (family_id = ? OR family_id IS NULL)");
    $check->bind_param("si", $name, $family_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        $st = $db->prepare("INSERT INTO task_type (name, family_id) VALUES (?, ?)");
        $st->bind_param("si", $name, $family_id);
        $st->execute();
    }
}

header("Location: dashboard.php");
exit();
