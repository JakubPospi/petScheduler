<?php
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// Kontrola, zda je uživatel přihlášen, je Admin (role_id === 1) a data přišla přes POST
if ($uzivatel !== null && $uzivatel['role_id'] === 1 && $_SERVER["REQUEST_METHOD"] === "POST") {
    
    // 1. Načtení ID uživatele k odebrání z formuláře (převedeme na číslo jako pojistku)
    $id_k_smazani = intval($_POST['user_to_remove']);

    // 2. Kontrola, aby admin omylem nesmazal sám sebe
    if ($id_k_smazani === intval($uzivatel['id'])) {
        header("Location: family.php?error=cannot_delete_self");
        exit();
    }

    // 3. Příprava SQL dotazu pro smazání uživatele podle jeho ID
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    
    // 4. Provedení dotazu
    $stmt->execute([
        $id_k_smazani
    ]);

    // 5. Přesměrování zpět na seznam rodiny, kde už člen nebude
    header("Location: family.php");
    exit();

} else {
    // Pokud se sem pokusí vlézt někdo neoprávněný
    header("Location: dashboard.php");
    exit();
}