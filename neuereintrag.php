<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

if (isset($_POST['file']) and isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
    isset($_POST['titel']) and is_string($_POST['titel']) and isset($_POST['artist']) and is_string($_POST['artist']) and
    isset($_POST['date']) and is_string($_POST['date']) and isset($_POST['location']) and is_string($_POST['location'])) {
    $erstellung = $user->gemaelde_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_POST['file']), htmlspecialchars($_POST['beschreibung']),
        htmlspecialchars($_POST['titel']), htmlspecialchars($_POST['artist']),
        htmlspecialchars($_POST['date']), htmlspecialchars($_POST['location']));
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Neuer Eintrag";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($erstellung) and is_bool($erstellung) and $erstellung): ?>
        <p class="nachricht">Eintragerstellung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($erstellung) and is_bool($erstellung) and !$erstellung): ?>
        <p class="nachricht fehler">Eintragerstellung fehlgeschlagen</p>
    <?php endif ?>

    <h1>Neuer Eintrag</h1>

    <div class="usermanagement">
        <h3>Hier kannst du einen neuen Eintrag erstellen</h3>
        <form method="post" action="neuereintrag.php">
            <hr>
            <label for="file">Gemälde auswählen:</label>
            <input type="file" id="file" name="file" class="invisible" required>

            <label for="beschreibung">Beschreibung:</label>
            <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                      placeholder="Fügen Sie eine Beschreibung ein..."></textarea>

            <label for="titel">Titel:</label>
            <input type="text" id="titel" name="titel" maxlength="100" required>

            <label for="artist">Künstler:</label>
            <input type="text" id="artist" name="artist" maxlength="100" required>

            <label for="date">Datum der Erstellung:</label>
            <input type="date" id="date" name="date">

            <label for="location">Ort:</label>
            <input type="text" id="location" name="location" maxlength="100">

            <hr>
            <button type="submit">Fertigstellen</button>
            <a href="index.php">Abbrechen</a>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>