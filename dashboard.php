<?php
require_once "./utils/init.php";

// Kontrola, zda je uživatel přihlášený
if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

$uzivatel = $_SESSION['uzivatel'];
$family_id = $uzivatel['family_id']; // Získání rodiny přihlášeného uživatele

// Pokud je uživatel Admin (role 1), vidí VŠECHNY úkoly, ale POUZE ze své rodiny
if ((int)$uzivatel['role_id'] === 1) {
    $stTasks = $db->prepare("
        SELECT 
            t.*,
            tt.name AS type_name,
            u.username AS user_name,
            a.name AS animal_name,
            f.family_name AS family_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        LEFT JOIN family f ON t.family_id = f.id
        WHERE t.is_done = 0 
          AND t.family_id = ?
        ORDER BY t.taskCreated DESC
    ");
    $stTasks->bind_param("i", $family_id);

// Ostatní role vidí pouze SVÉ VLASTNÍ úkoly z dané rodiny
} else {
    $stTasks = $db->prepare("
        SELECT 
            t.*,
            tt.name AS type_name,
            u.username AS user_name,
            a.name AS animal_name,
            f.family_name AS family_name
        FROM tasks t
        LEFT JOIN task_type tt ON t.type_id = tt.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN animals a ON t.animal_id = a.id
        LEFT JOIN family f ON t.family_id = f.id
        WHERE t.user_id = ?
          AND t.is_done = 0
          AND t.family_id = ?
        ORDER BY t.taskCreated DESC
    ");
    $stTasks->bind_param("ii", $uzivatel['id'], $family_id);
}

$stTasks->execute();
$tasks = $stTasks->get_result()->fetch_all(MYSQLI_ASSOC);

if ($uzivatel === null) {
    require "./layout/header.phtml";
} else {
    require "./layout/header2.phtml";
}

require_once "./dashboard.phtml";
require "./layout/footer.phtml";