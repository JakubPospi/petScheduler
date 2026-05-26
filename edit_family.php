<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    $novy_nazev = trim($_POST['family_name']);
    $family_id = $uzivatel['family_id'];

    if (!empty($novy_nazev)) {
        $stmt = $db->prepare("UPDATE family SET family_name = ? WHERE ID = ?");
        
        if ($stmt) {
            $stmt->execute([$novy_nazev, $family_id]);
        }
    }

    header("Location: family.php"); 
    exit();

} else {
    header("Location: dashboard.php");
    exit();
}