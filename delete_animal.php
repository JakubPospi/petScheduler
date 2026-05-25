<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// Kontrola práv: Uživatel musí být přihlášen, mít roli Admin a požadavek musí jít přes POST
if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Načtení ID zvířete ke smazání
    $animal_id = intval($_POST['animal_id']);
    $family_id = $uzivatel['family_id']; // Pojistka, aby nesmazal zvíře cizí rodině

    // Příprava SQL dotazu pro smazání
    $stmt = $db->prepare("DELETE FROM animals WHERE id = ? AND family_id = ?");
    
    if ($stmt === false) {
        die("Chyba při přípravě požadavku na smazání.");
    }

    // Provedení dotazu
    $result = $stmt->execute([$animal_id, $family_id]);

    if ($result === false) {
        die("Mazlíčka se nepodařilo z databáze odstranit.");
    }

    // Přesměrování zpět na seznam mazlíčků, kde už zvíře nebude
    header("Location: pets.php");
    exit();

} else {
    // Neoprávněný přístup – vyhodíme na dashboard
    header("Location: dashboard.php");
    exit();
}