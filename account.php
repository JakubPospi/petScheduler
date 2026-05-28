<?php
require "./utils/init.php";

if (!isset($_SESSION['uzivatel'])) {
    header("Location: login.php");
    exit();
}

$uzivatel = $_SESSION['uzivatel'];
$user_id = $uzivatel['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        
        if (!empty($username) && !empty($email)) {
            $stmt = $db->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
            $stmt->execute([$username, $email, $user_id]);
            
            $_SESSION['uzivatel']['username'] = $username;
            $_SESSION['uzivatel']['email'] = $email;
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $new_password_confirm = $_POST['new_password_confirm'];
        
        if (!empty($old_password) && !empty($new_password) && !empty($new_password_confirm)) {
            
            // 1. Krok: Vytáhneme stávající hash hesla z databáze
            $stmt_check = $db->prepare("SELECT passwd FROM users WHERE id = ?");
            $stmt_check->execute([$user_id]);
            $user_data = $stmt_check->get_result()->fetch_assoc();
            
            if ($user_data) {
                $current_hashed_password = $user_data['passwd'];
                
                // 2. Krok: Ověření, že staré heslo souhlasí s databází
                if (!password_verify($old_password, $current_hashed_password)) {
                    die("Chyba: Zadané staré heslo není správné.");
                }
                
                // 3. Krok: Ověření, že se dvě nová hesla rovnají
                if ($new_password !== $new_password_confirm) {
                    die("Chyba: Nová hesla se neshodují.");
                }
                
                // 4. Krok: Ověření, že nové heslo NENÍ stejné jako staré
                if ($old_password === $new_password) {
                    die("Chyba: Nové heslo nesmí být stejné jako staré heslo.");
                }
                
                // Pokud všechny kontroly prošly, zahešujeme a uložíme nové heslo
                $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
                $stmt = $db->prepare("UPDATE users SET passwd = ? WHERE id = ?");
                $stmt->execute([$hashed_password, $user_id]);
            }
        }
    }
    header("Location: account.php");
    exit();
}

$stmt = $db->prepare("SELECT u.username, u.email, u.role_id, r.name FROM users u LEFT JOIN role_type r ON u.role_id = r.id_role WHERE u.id = ?");
$stmt->execute([$user_id]);
$profil = $stmt->get_result()->fetch_assoc();

if (empty($profil['role_name'])) {
    if($profil['role_id'] === 1) $profil['role_name'] = 'Admin';
    if($profil['role_id'] === 2) $profil['role_name'] = 'Rodič';
    if($profil['role_id'] === 3) $profil['role_name'] = 'Dítě';
}

require "./layout/header2.phtml";
require "./account.phtml";
require "./layout/footer.phtml";
?>