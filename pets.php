<?php
require "./utils/init.php";

$nazev;
$druh;
$datumNarozeni;
$popis;

$familyId = $uzivatel['family_id'];

$stmt = mysqli_prepare($db, "
INSERT INTO animals (name,species,birth_date,description,family_id) 
VALUES(?,?,?,?,?)");

if($stmt === false){
    echo "Mazlíčka se nepodařilo přidat";
    echo mysqli_error($db);
}

mysqli_stmt_bind_param($stmt,"ssdsi",$nazev,$druh,$datumNarozeni,$popis,$familyId);
$result = mysqli_execute($stmt);

if($result === false){
    echo "Mazlíčka se nepodařilo přidat";
    echo mysqli_error($db);
    exit;
}
?>