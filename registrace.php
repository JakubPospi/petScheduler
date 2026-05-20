<?php 
require "./utils/init.php";
require "./layout/header.phtml";
require "./registrace.phtml";
require "./layout/footer.phtml";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    // zahashování hesla
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // 1. vytvoření rodiny
    $familyName = "rodina 1";

    $sqlFamily = "INSERT INTO family (family_name) VALUES (?)";
    $stmtFamily = mysqli_prepare($db, $sqlFamily);

    mysqli_stmt_bind_param($stmtFamily, "s", $familyName);
    mysqli_stmt_execute($stmtFamily);

    // získání ID nově vytvořené rodinyS
    $family_id = mysqli_insert_id($db);

    // 2. vytvoření uživatele
    $role_id = 1;

    $sqlUser = "INSERT INTO users (username, passwd, email, role_id, family_id)
                VALUES (?, ?, ?, ?, ?)";

    $stmtUser = mysqli_prepare($db, $sqlUser);

    mysqli_stmt_bind_param(
        $stmtUser,
        "sssii",
        $username,
        $hashedPassword,
        $email,
        $role_id,
        $family_id
    );

    if (mysqli_stmt_execute($stmtUser)) {
        echo "Registrace proběhla úspěšně.";
    } else {
        echo "Chyba při registraci.";
    }
}
?>

