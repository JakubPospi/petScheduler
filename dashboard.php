<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

if($uzivatel === null){
require "./layout/header.phtml";
}
else{
    require "./layout/header2.phtml";
}
require_once "./dashboard.phtml";
require "./layout/footer.phtml";