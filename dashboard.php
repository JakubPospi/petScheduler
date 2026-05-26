<?php
require "./utils/init.php";

$uzivatel = $_SESSION['uzivatel'];

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
    WHERE t.taskTime >= NOW() 
    ORDER BY t.taskCreated DESC
    ");
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
    WHERE t.taskTime >= NOW() 
        AND t.user_id = ?
    ORDER BY t.taskCreated DESC
    ");
    $stTasks->bind_param("i", $uzivatel['id']);
}

$stTasks->execute();
$tasks = $stTasks->get_result();

if($uzivatel === null){
    require "./layout/header.phtml";
}
else{
    require "./layout/header2.phtml";
}

require_once "./dashboard.phtml";
require "./layout/footer.phtml";