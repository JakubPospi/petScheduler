<?php

require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

require "./layout/header2.phtml";
require "./tasks.phtml";
require "./layout/footer.phtml";