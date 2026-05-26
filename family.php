<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

if ($uzivatel === null || empty($uzivatel["family_id"])) {
    die("Pro zobrazení této stránky musíte být přihlášeni a být členem rodiny.");
}


$stmt = $db->prepare("SELECT username, role_id, id FROM users WHERE family_id = ?");
$stmt->execute([$uzivatel["family_id"]]);

$clenove = $stmt->get_result(); 

$rod = $db->prepare("SELECT family_name FROM family WHERE ID = ?");
$rod->execute([$uzivatel["family_id"]]);

$vysledek_rodiny = $rod->get_result()->fetch_assoc();
$nazev_rodiny = $vysledek_rodiny['family_name'];


require "./layout/header2.phtml";
require "./familyList.phtml";
require "./layout/footer.phtml";



