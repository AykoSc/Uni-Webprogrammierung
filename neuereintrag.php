<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = NutzerDAODummyImpl::getInstance();

if (isset($_REQUEST['typ']) and is_string($_REQUEST['typ'])) {
    if ($_REQUEST['typ'] === 'Gemälde') {
        $gemaelde = true;
    } else if ($_REQUEST['typ'] === 'Sammlung') {
        $gemaelde = false;
    }
}
if (!isset($gemaelde)) {
    header("location: index.php");
}

if (isset($_SESSION["id"]) and is_int($_SESSION["id"])) {
    $angemeldet = true;
} else {
    $angemeldet = false;
}

if ($gemaelde and $angemeldet) {
    if (isset($_POST['datei']) and
        isset($_POST['titel']) and is_string($_POST['titel']) and
        isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
        isset($_POST['kuenstler']) and is_string($_POST['kuenstler']) and
        isset($_POST['datum']) and is_string($_POST['datum']) and
        isset($_POST['ort']) and is_string($_POST['ort'])) {
        $erstellung = $user->gemaelde_anlegen(htmlspecialchars($_SESSION["id"]),
            htmlspecialchars($_POST['datei']), htmlspecialchars($_POST['beschreibung']),
            htmlspecialchars($_POST['titel']), htmlspecialchars($_POST['kuenstler']),
            htmlspecialchars($_POST['datum']), htmlspecialchars($_POST['ort']));
    }
}

if (!$gemaelde and $angemeldet) {
    if (isset($_POST['auswahl']) and is_string($_POST['auswahl']) and
        isset($_POST['titel']) and is_string($_POST['titel']) and
        isset($_POST['beschreibung']) and is_string($_POST['beschreibung'])) {
        $erstellung = $user->sammlung_anlegen(htmlspecialchars($_SESSION["id"]),
            htmlspecialchars($_POST['auswahl']),
            htmlspecialchars($_POST['titel']),
            htmlspecialchars($_POST['beschreibung']));
    }
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
    <?php if (isset($angemeldet) and is_bool($angemeldet) and !$angemeldet): ?>
        <p class="nachricht fehler">Du bist nicht angemeldet!</p>
    <?php endif ?>
    <?php if (isset($erstellung) and is_bool($erstellung) and $erstellung): ?>
        <p class="nachricht">Eintragerstellung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($erstellung) and is_bool($erstellung) and !$erstellung): ?>
        <p class="nachricht fehler">Eintragerstellung fehlgeschlagen</p>
    <?php endif ?>

    <h1>Neuer Eintrag</h1>

    <div class="usermanagement">
        <h3>Hier kannst du einen neuen Eintrag erstellen</h3>
        <form method="get" action="neuereintrag.php">
            <hr>
            <?php $gegenteil = ($gemaelde) ? 'Sammlung' : 'Gemälde' ?>
            <input type="hidden" name="typ" value="<?php echo $gegenteil ?>">
            <button type="submit"><?php echo $gegenteil ?> erstellen?</button>
            <hr>
        </form>

        <?php if ($gemaelde): ?>
            <h3>Gemälde erstellen</h3>
            <form method="post" action="neuereintrag.php">
                <hr>
                <label for="datei">Gemälde auswählen:</label>
                <input type="file" accept=".png, jpg" id="datei" name="datei" required>

                <label for="titel">Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="100" required
                    <?php echo (isset($_POST["titel"]) and is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Fügen Sie eine Beschreibung ein..."><?php echo (isset($_POST["beschreibung"]) and is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

                <label for="kuenstler">Künstler:</label>
                <input type="text" id="kuenstler" name="kuenstler" maxlength="100" required
                    <?php echo (isset($_POST["kuenstler"]) and is_string($_POST["kuenstler"])) ? 'value=' . htmlspecialchars($_POST["kuenstler"]) : '' ?>>

                <label for="datum">Datum der Erstellung:</label>
                <input type="date" id="datum" name="datum"
                    <?php echo (isset($_POST["datum"]) and is_string($_POST["datum"])) ? 'value=' . htmlspecialchars($_POST["datum"]) : '' ?>>

                <label for="ort">Ort:</label>
                <input type="text" id="ort" name="ort" maxlength="100"
                    <?php echo (isset($_POST["ort"]) and is_string($_POST["ort"])) ? 'value=' . htmlspecialchars($_POST["ort"]) : '' ?>>

                <input type="hidden" name="typ" value="Gemälde">
                <hr>
                <button type="submit">Fertigstellen</button>
                <a href="index.php">Abbrechen</a>
            </form>
        <?php else: ?>
            <h3>Sammlung erstellen</h3>
            <form method="post" action="neuereintrag.php">
                <hr>
                <label for="auswahl">Gemälde-IDs: (z.B.: 1,2,6)</label>
                <input type="text" id="auswahl" name="auswahl" required
                    <?php echo (isset($_POST["auswahl"]) and is_string($_POST["auswahl"])) ? 'value=' . htmlspecialchars($_POST["auswahl"]) : '' ?>>

                <label for="titel">Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="100" required
                    <?php echo (isset($_POST["titel"]) and is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Fügen Sie eine Beschreibung ein..."><?php echo (isset($_POST["beschreibung"]) and is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

                <input type="hidden" name="typ" value="Sammlung">
                <hr>
                <button type="submit">Fertigstellen</button>
                <a href="index.php">Abbrechen</a>
            </form>
        <?php endif ?>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>