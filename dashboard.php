<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

$uzivatel  = $_SESSION['uzivatel'];
$uzivatel['role_id']   = (int)$uzivatel['role_id'];
$uzivatel['id']        = (int)$uzivatel['id'];
$uzivatel['family_id'] = (int)$uzivatel['family_id'];
$family_id = $uzivatel['family_id'];

// Tasks query
if ((int)$uzivatel['role_id'] === 1) {
    $stTasks = $db->prepare("
        SELECT t.*, tt.name AS type_name, u.username AS user_name,
               a.name AS animal_name, f.family_name AS family_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        LEFT JOIN family f ON t.family_id = f.id
        WHERE t.is_done = 0 AND t.family_id = ?
        ORDER BY t.taskCreated DESC
    ");
    $stTasks->bind_param("i", $family_id);
} else {
    $stTasks = $db->prepare("
        SELECT t.*, tt.name AS type_name, u.username AS user_name,
               a.name AS animal_name, f.family_name AS family_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        LEFT JOIN family f ON t.family_id = f.id
        WHERE t.user_id = ? AND t.is_done = 0 AND t.family_id = ?
        ORDER BY t.taskCreated DESC
    ");
    $stTasks->bind_param("ii", $uzivatel['id'], $family_id);
}
$stTasks->execute();
$tasks = $stTasks->get_result()->fetch_all(MYSQLI_ASSOC);

// Task types — global (family_id IS NULL) + this family's custom types
$stTypes = $db->prepare("
    SELECT id, name,
           CASE WHEN family_id IS NULL THEN 1 ELSE 0 END AS is_global
    FROM task_type
    WHERE family_id IS NULL OR family_id = ?
    ORDER BY is_global DESC, name ASC
");
$stTypes->bind_param("i", $family_id);
$stTypes->execute();
$taskTypes = $stTypes->get_result()->fetch_all(MYSQLI_ASSOC);

require "./layout/header2.phtml";
require_once "./dashboard.phtml";
