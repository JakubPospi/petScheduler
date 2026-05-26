<?php
require_once "./utils/init.php";

$taskId = $_POST['task_id'];

$st = $db->prepare("
    UPDATE tasks
    SET is_done = 0
    WHERE id = ?
");

$st->bind_param("i", $taskId);
$st->execute();

header("Location: dashboard.php");
exit;