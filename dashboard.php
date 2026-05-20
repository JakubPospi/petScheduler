<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

$nazev_rodiny = "Žádná rodina"; 

if ($uzivatel !== null && !empty($uzivatel["family_id"])) {

    $stmt = $db->prepare("SELECT family_name FROM family WHERE ID = ?");
    $stmt->execute([$uzivatel["family_id"]]);
    
    $vysledek = $stmt->get_result();
    $rodina = $vysledek->fetch_assoc();
    
    if ($rodina) {
        $nazev_rodiny = $rodina["family_name"]; 
    }
}

if($uzivatel === null){
require "./layout/header.phtml";
}
else{
    require "./layout/header2.phtml";
}
require_once "./dashboard.phtml";
require "./layout/footer.phtml";