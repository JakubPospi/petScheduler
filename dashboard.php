<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

$nazev_rodiny = "Žádná rodina"; 

if ($uzivatel !== null && !empty($uzivatel["family_id"])) {
    // V SELECTu taháme 'name', protože se tak jmenuje sloupec v tabulce family
    $stmt = $db->prepare("SELECT family_name FROM family WHERE ID = ?");
    $stmt->execute([$uzivatel["family_id"]]);
    $rodina = $stmt->fetch();
    
    if ($rodina) {
        // Tady musíme použít ['name'], protože to jsme vybrali v SQL dotazu
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