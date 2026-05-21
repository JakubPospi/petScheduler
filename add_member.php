<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// Kontrola, zda je uživatel přihlášen, je Admin (role_id === 1) a data přišla přes POST
if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. Načtení dat z formuláře
    $jmeno = $_POST['username'];
    $email = $_POST['email'];
    $heslo_surove = $_POST['password'];
    $role_id = intval($_POST['role_id']); // Převedeme na číslo pro jistotu
    $family_id = $uzivatel['family_id']; // Nový člen dostane stejné ID rodiny jako ty

    // 2. Bezpečné zašifrování hesla
    $heslo_zasifrovane = password_hash($heslo_surove, PASSWORD_BCRYPT);

    // 3. Příprava SQL dotazu (Změň si názvy sloupců, pokud je máš v DB jinak)
    $stmt = $db->prepare("INSERT INTO users (username, email, passwd, role_id, family_id) VALUES (?, ?, ?, ?, ?)");
    
    // 4. Provedení dotazu s našimi daty
    $stmt->execute([
        $jmeno,
        $email,
        $heslo_zasifrovane,
        $role_id, // Sem propadne 1 (Admin), 2 (Rodič) nebo 3 (Dítě) podle toho, co vybrali v modálu
        $family_id
    ]);

    // 5. Přesměrování zpět na seznam rodiny, kde už bude nový člen svítit
    header("Location: family.php");
    exit();

} else {
    // Pokud se sem pokusí vlézt někdo, kdo není přihlášený nebo není Admin
    header("Location: dashboard.php");
    exit();
}