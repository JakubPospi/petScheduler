 <?php
// 1. Inicializace aplikace a session (zde se definuje $uzivatel)
require "./utils/init.php";
$uzivatel = $_SESSION['uzivatel'];

// 2. Kontrola, zda je uživatel přihlášen
if (!isset($uzivatel) || $uzivatel === null) {
    die("Chyba: Uživatel není přihlášen nebo se nepodařilo načíst jeho data.");
}
  $stmtPets = $db->prepare("SELECT name, species FROM animals WHERE family_id = ?");
$stmtPets->execute([$uzivatel["family_id"]]);
$mazlicci = $stmtPets->get_result();
// 3. Zpracování formuláře (Spustí se POUZE při odeslání metodou POST)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"])) {
    $nazev = $_POST["name"];
    $druh = $_POST["species"];
    $datumNarozeni = $_POST["birthDate"];
    $popis = $_POST["desc"];
    $familyId = $uzivatel['family_id'];

    // Příprava SQL dotazu
    $stmt = mysqli_prepare($db, "
        INSERT INTO animals (name, species, birth_date, description, family_id) 
        VALUES (?, ?, ?, ?, ?)
    ");

    if ($stmt === false) {
        die("Mazlíčka se nepodařilo připravit k zápisu: " . mysqli_error($db));
    }

    // Navázání parametrů a spuštění
    mysqli_stmt_bind_param($stmt, "ssssi", $nazev, $druh, $datumNarozeni, $popis, $familyId);
    $result = mysqli_execute($stmt);

    if ($result === false) {
        die("Mazlíčka se nepodařilo přidat do databáze: " . mysqli_error($db));
    }
    // Přesměrování na samého sebe (ochrana před dvojitým odesláním formuláře při F5)
    header("Location: pets.php");
    exit;
}

// 4. Vykreslení HTML šablon (Všechny proměnné jako $uzivatel jsou nyní bezpečně dostupné)
require "./layout/header2.phtml";
require "./petsList.phtml";
require "./layout/footer.phtml";
?>