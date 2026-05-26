<?php
require "./utils/init.php";

// Kontrola, zda je uživatel přihlášený
if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

$uzivatel = $_SESSION['uzivatel'];
$user_id = $uzivatel['id'];

// 1. ZPRACOVÁNÍ FORMULÁŘE (POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // AKCE A: Úprava profilu (Jméno a Email)
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        if (!empty($username) && !empty($email)) {
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
            
            // Aktualizujeme data i v session, aby se změna projevila hned v menu / headeru
            $_SESSION['uzivatel']['username'] = $username;
            $_SESSION['uzivatel']['email'] = $email;
        }
    }
    
    // AKCE B: Změna hesla
    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $password = $_POST['password'];
        
        if (!empty($password)) {
            // Zahashujeme nové heslo před uložením do databáze
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt = $db->prepare("UPDATE users SET passwd = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
        }
    }
    
    // Po zpracování přesměrujeme sami na sebe, aby se promazal POST a nefungovalo F5 (refresh)
    header("Location: account.php");
    exit();
}

// 2. NAČTENÍ AKTUÁLNÍCH DAT UŽIVATELE (včetně názvu role z propojené tabulky)
// Předpokládám, že tabulka rolí se jmenuje 'roles' (nebo 'user_roles') a má sloupce 'id' a 'role_name'
// Pokud se jmenuje jinak, uprav si názvy v JOINu níže.
$stmt = $db->prepare("SELECT u.username, u.email, u.role_id, r.name FROM users u LEFT JOIN role_type r ON u.role_id = r.id_role WHERE u.id = ?");
$stmt->execute([$user_id]);
$profil = $stmt->get_result()->fetch_assoc();

// Pokud by se náhodou role nepodařila propojit textově, vypíšeme zálohu podle ID
if (empty($profil['role_name'])) {
    if($profil['role_id'] === 1) $profil['role_name'] = 'Admin';
    if($profil['role_id'] === 2) $profil['role_name'] = 'Rodič';
    if($profil['role_id'] === 3) $profil['role_name'] = 'Dítě';
}

// Načtení vzhledu
require "./layout/header2.phtml";
require "./account.phtml";
require "./layout/footer.phtml";
?>