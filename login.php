<?php
require_once "./utils/init.php";
$chyba = false;

if (isset($_POST['username'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = mysqli_prepare($db, "
        SELECT * FROM users
        WHERE username = ?
    ");

    if ($stmt === false) {
        echo "<h1>Přihlášení se nezdařilo.</h1>";
        echo mysqli_error($db);
        exit;
    }
    mysqli_stmt_bind_param($stmt, "s", $username);

    $result = mysqli_stmt_execute($stmt);

    if ($result === false) {
        echo "<h1>Přihlášení se nezdařilo.</h1>";
        echo mysqli_error($db);
        exit;
    }

    $result = mysqli_stmt_get_result($stmt);
    $uzivatel = mysqli_fetch_assoc($result);

    if ($uzivatel && password_verify($password, $uzivatel['passwd'])) {

        $_SESSION['uzivatel'] = $uzivatel;

        if ((int)$uzivatel['role_id'] === 1) {
            $_SESSION['admin_mode'] = true;
        }

        if (!isset($_SESSION['theme'])) {
            $_SESSION['theme'] = 'dark';
        }

        header("Location: dashboard.php");
        exit;

    } else {
        $chyba = true;
    }

    mysqli_stmt_close($stmt);
}

if ($chyba) echo "<b>Chybné přihlašovací údaje!</b><br>"; 

require "./login.phtml";

?>