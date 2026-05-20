<?php
require "./utils/init.php";
$uzivatel = $_SESSION['user'];


require "./layout/header.phtml";
require_once "./dashboard.phtml";
require "./layout/footer.phtml";