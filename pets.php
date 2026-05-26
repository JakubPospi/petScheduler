 <?php
require_once "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

if (!isset($uzivatel) || $uzivatel === null) {
    die("Chyba: Uživatel není přihlášen nebo se nepodařilo načíst jeho data.");
}
  $stmtPets = $db->prepare("SELECT id, name, species FROM animals WHERE family_id = ?");
$stmtPets->execute([$uzivatel["family_id"]]);
$mazlicci = $stmtPets->get_result();


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
    $nazev = $_POST["name"];
    $druh = $_POST["species"];
    $datumNarozeni = $_POST["birthDate"];
    $popis = $_POST["desc"];
    $familyId = $uzivatel['family_id'];

    $stmt = mysqli_prepare($db, "
        INSERT INTO animals (name, species, birth_date, description, family_id) 
        VALUES (?, ?, ?, ?, ?)
    ");

    if ($stmt === false) {
        die("Mazlíčka se nepodařilo připravit k zápisu: " . mysqli_error($db));
    }

    mysqli_stmt_bind_param($stmt, "ssssi", $nazev, $druh, $datumNarozeni, $popis, $familyId);
    $result = mysqli_execute($stmt);

    if ($result === false) {
        die("Mazlíčka se nepodařilo přidat do databáze: " . mysqli_error($db));
    }
    header("Location: pets.php");
    exit;
}

require "./layout/header2.phtml";
require "./petsList.phtml";
require "./layout/footer.phtml";
?>