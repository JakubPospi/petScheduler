<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php"); exit();
}

$uzivatel  = $_SESSION['uzivatel'];
$family_id = (int)$uzivatel['family_id'];
$role      = (int)$uzivatel['role_id'];

if ($role !== 1 && $role !== 2) {
    header("Location: dashboard.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['type_id'])) {
    $type_id = (int)$_POST['type_id'];

    // Only allow deleting THIS family's custom types — never global ones
    $st = $db->prepare("DELETE FROM task_type WHERE id = ? AND family_id = ?");
    $st->bind_param("ii", $type_id, $family_id);
    $st->execute();
}

header("Location: dashboard.php");
exit();
