<?php
require "./utils/init.php";
$chyba = false;

if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $passHASH = sha1($password); 
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND passwd = ?");
    $stmt->bind_param("ss", $username, $passHASH);
    
    $stmt->execute();
    $result = $stmt->get_result();     
    $uzivatel = $result->fetch_assoc(); 

    if ($uzivatel) {
        $_SESSION['uzivatel'] = $uzivatel['username'];
        header("Location: index.phhtml");
        exit;
    } 
    else {
        $chyba = true;
    }
    $stmt->close();
}

if ($chyba) echo "<b>Chybné přihlašovací údaje!</b><br>"; 

require "./layout/header.phtml";
require "./login.phtml";
require "./layout/footer.phtml";


?>