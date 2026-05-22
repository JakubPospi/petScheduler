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

    // 2. Sestavení DYNAMICKÉHO SQL dotazu podle vyplněných polí
    $updates = []; // Sem budeme ukládat kousky SQL kódu (např. "username = ?")
    $params = [];  // Sem budeme ukládat hodnoty pro otazníky

    // Pokud uživatel vyplnil jméno
    if (!empty($jmeno)) {
        $updates[] = "username = ?";
        $params[] = $jmeno;
    }

    // Pokud uživatel vyplnil email
    if (!empty($email)) {
        $updates[] = "email = ?";
        $params[] = $email;
    }

    // Pokud admin vybral novou roli
    if ($role_id !== null) {
        $updates[] = "role_id = ?";
        $params[] = $role_id;
    }

    // Pokud admin zadal nové heslo
    if (!empty($heslo_surove)) {
        $heslo_zasifrovane = password_hash($heslo_surove, PASSWORD_BCRYPT);
        $updates[] = "passwd = ?";
        $params[] = $heslo_zasifrovane;
    }

    // KONTROLA: Pokud uživatel neupravil vůbec nic, formulář jen přesměrujeme zpět
    if (empty($updates)) {
        header("Location: family.php");
        exit();
    }

    // Spojíme všechny kousky z pole $updates pomocí čárky
    // Výsledek bude např.: "username = ?, passwd = ?"
    $sql = "UPDATE users SET " . implode(", ", $updates);

    // Na úplný konec dotazu přidáme podmínku WHERE pro konkrétního uživatele
    $sql .= " WHERE id = ?";
    $params[] = $user_to_edit_id;

    // 3. Příprava a provedení dotazu (zůstává stejné)
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