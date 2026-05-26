<?php
require "./utils/init.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = (int)$_POST['task_id'];
 
    $stmt = $db->prepare("UPDATE tasks SET is_done = 1 WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();

}
header("Location: dashboard.php"); 
exit;
?>