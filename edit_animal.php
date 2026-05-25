<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// Kontrola práv (Přihlášen, Admin a metoda POST)
if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. Načtení ID mazlíčka a dat z formuláře
    $animal_id = intval($_POST['animal_id']);
    $jmeno = trim($_POST['name']);
    $druh = trim($_POST['species']);
    $datum_narozeni = $_POST['birthDate'];
    $popis = trim($_POST['desc']);
    $family_id = $uzivatel['family_id']; // Pojistka pro ověření rodiny

    // 2. Sestavení dynamického SQL dotazu
    $updates = [];
    $params = [];

    if (!empty($jmeno)) {
        $updates[] = "name = ?";
        $params[] = $jmeno;
    }

    if (!empty($druh)) {
        $updates[] = "species = ?";
        $params[] = $druh;
    }

    if (!empty($datum_narozeni)) {
        $updates[] = "birth_date = ?";
        $params[] = $datum_narozeni;
    }

    if (!empty($popis)) {
        $updates[] = "description = ?";
        $params[] = $popis;
    }

    // Pokud admin nic nevyplnil a jen kliknul na uložit, nic neděláme
    if (empty($updates)) {
        header("Location: pets.php");
        exit();
    }

    // Složíme SQL dotaz
    $sql = "UPDATE animals SET " . implode(", ", $updates);
    
    // Bezpečnostní podmínka: Upravíme zvíře podle ID, ale ZÁROVEŇ musí patřit do adminovy rodiny!
    $sql .= " WHERE id = ? AND family_id = ?";
    $params[] = $animal_id;
    $params[] = $family_id;

    // 3. Provedení dotazu
    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    // 4. Přesměrování zpět na seznam mazlíčků
    header("Location: pets.php");
    exit();

} else {
    // Neoprávněný přístup
    header("Location: dashboard.php");
    exit();
}