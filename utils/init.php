<?php

session_start();

$db = mysqli_connect("localhost","root","","appkaKrmeni");
if($db=== false){
    echo "<h1>Připojení k databázi selhalo</h1>";
    exit;
}

mysqli_set_charset($db, "utf8mb4");