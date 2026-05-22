<?php require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

if ($uzivatel === null || empty($uzivatel["family_id"])) {
    die("Pro zobrazení této stránky musíte být přihlášeni a být členem rodiny.");
}

require "./layout/header2.phtml";
require "./upravaClenu.phtml";
require "./layout/footer.phtml";

?>