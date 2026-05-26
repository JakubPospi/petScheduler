<?php

require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];


if($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["ulozTask"])){

    $typUkolu = $_POST['task_type'];
    $clenKteryMaSplnitTask = $_POST['user_id'];
    $mazlicek = $_POST['animal_id'];
    $popisekUkolu = $_POST['description'];
    $datumVytvoreni = date('Y-m-d H:i:s') ;
    $datumSplneni = $_POST['datum_splneni'];

    $stmt = mysqli_prepare($db,
            "INSERT INTO tasks(type_id,user_id,animal_id,family_id,taskTime,taskCreated,note) VALUES(?,?,?,?,?,?,?)");

    If($stmt === false) {
        die("Úkol se nepodařilo připravit k zápisu: " . mysqli_error($db));
    }


    mysqli_stmt_bind_param($stmt,"iiiisss",$typUkolu,$clenKteryMaSplnitTask,$mazlicek,$uzivatel['family_id'],$datumSplneni,$datumVytvoreni,$popisekUkolu);
    $result = mysqli_execute($stmt);

    if ($result === false) {
        die("Úkol se nepodařilo přidat do databáze: " . mysqli_error($db));
    }

    header("Location: dashboard.php");
    exit;
}

require "./layout/header2.phtml";
require "./add_tasks.phtml";
require "./layout/footer.phtml";
