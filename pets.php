<?php
require "./utils/init.php";

if (!isset($uzivatel) || $uzivatel === null) {
    die("Chyba: Uživatel není přihlášen nebo se nepodařilo načíst jeho data.");
}

$nazev = $_POST["name"];
$druh = $_POST["species"];
$datumNarozeni = $_POST["birthDate"];
$popis = $_POST["desc"];

$familyId = $uzivatel['family_id'];

$stmt = mysqli_prepare($db, "
INSERT INTO animals (name,species,birth_date,description,family_id) 
VALUES(?,?,?,?,?)");

if($stmt === false){
    echo "Mazlíčka se nepodařilo přidat";
    echo mysqli_error($db);
}

mysqli_stmt_bind_param($stmt,"ssssi",$nazev,$druh,$datumNarozeni,$popis,$familyId);
$result = mysqli_execute($stmt);

if($result === false){
    echo "Mazlíčka se nepodařilo přidat";
    echo mysqli_error($db);
    exit;
}

header("Location: dashboard.php");
?>