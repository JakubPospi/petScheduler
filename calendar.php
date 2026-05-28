<?php
require_once "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

$uzivatel  = $_SESSION['uzivatel'];
$family_id = $uzivatel['family_id'];
$role      = (int)$uzivatel['role_id'];

// Month navigation
$month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
$year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
if ($month < 1)  { $month = 12; $year--; }
if ($month > 12) { $month = 1;  $year++; }

$prevMonth = $month - 1; $prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
$nextMonth = $month + 1; $nextYear = $year;
if ($nextMonth > 13) { $nextMonth = 1; $nextYear++; }

// Fetch tasks for this month
if ($role === 1 || $role === 2) {
    $st = $db->prepare("
        SELECT t.*, tt.name AS type_name, u.username AS user_name, a.name AS animal_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        WHERE t.family_id = ?
          AND MONTH(t.taskTime) = ? AND YEAR(t.taskTime) = ?
        ORDER BY t.taskTime ASC
    ");
    $st->bind_param("iii", $family_id, $month, $year);
} else {
    $st = $db->prepare("
        SELECT t.*, tt.name AS type_name, u.username AS user_name, a.name AS animal_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        WHERE t.user_id = ? AND t.family_id = ?
          AND MONTH(t.taskTime) = ? AND YEAR(t.taskTime) = ?
        ORDER BY t.taskTime ASC
    ");
    $st->bind_param("iiii", $uzivatel['id'], $family_id, $month, $year);
}
$st->execute();
$allTasks = $st->get_result()->fetch_all(MYSQLI_ASSOC);

// Group by day
$byDay = [];
foreach ($allTasks as $t) {
    $d = date('j', strtotime($t['taskTime']));
    $byDay[$d][] = $t;
}

require "./layout/header2.phtml";
require "./calendar.phtml";
require "./layout/footer.phtml";
