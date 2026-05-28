<?php
require_once "./utils/init.php";
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

$user_to_edit_id = intval($_POST['user_to_edit']);
$vypis = $db->prepare("SELECT u.username, u.email, u.role_id, r.name FROM users u LEFT JOIN role_type r ON u.role_id = r.id_role WHERE u.id = ?");
$vypis->execute([$user_to_edit_id]);
$profil = $vypis->get_result()->fetch_assoc();


require "./layout/header2.phtml";
require "./familyList.phtml";
require "./layout/footer.phtml";



