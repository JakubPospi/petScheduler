<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// Kontrola, zda je uživatel přihlášen, je Admin (role_id === 1) a data přišla přes POST
if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. Načtení ID uživatele, kterého jdeme upravovat
    $user_to_edit_id = intval($_POST['user_to_edit']);
    
    // Načtení ostatních dat z formuláře
    $jmeno = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role_id = isset($_POST['role_id']) ? intval($_POST['role_id']) : null;
    $heslo_surove = $_POST['password'];

    // 2. Sestavení dynamického SQL dotazu podle toho, co uživatel vyplnil
    // Začneme základem: chceme upravit jméno a email (předpokládáme, že jsou povinné nebo vyplněné)
    $sql = "UPDATE users SET username = ?, email = ?";
    $params = [$jmeno, $email];

    // Pokud admin vybral novou roli, přidáme ji do dotazu
    if ($role_id !== null) {
        $sql .= ", role_id = ?";
        $params[] = $role_id;
    }

    // Pokud admin zadal nové heslo, zašifrujeme ho a přidáme do dotazu
    if (!empty($heslo_surove)) {
        $heslo_zasifrovane = password_hash($heslo_surove, PASSWORD_BCRYPT);
        $sql .= ", passwd = ?";
        $params[] = $heslo_zasifrovane;
    }

    // Na konec dotazu MUSÍME dát podmínku WHERE, abychom upravili jen toho jednoho správného člověka!
    $sql .= " WHERE id = ?";
    $params[] = $user_to_edit_id;

    // 3. Příprava a provedení dotazu
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    // 4. Přesměrování zpět na seznam rodiny
    header("Location: family.php");
    exit();

} else {
    // Pokud se sem pokusí vlézt někdo, kdo není přihlášený nebo není Admin
    header("Location: dashboard.php");
    exit();
}