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
        $password = $_POST['password'];
        
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE users SET passwd = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $user_id]);
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