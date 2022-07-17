<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_REQUEST['typ']) && is_string($_REQUEST['typ'])) {
    if ($_REQUEST['typ'] === 'Gemälde') {
        $gemaelde = true;
    } else if ($_REQUEST['typ'] === 'Sammlung') {
        $gemaelde = false;
    }
}
if (!isset($gemaelde)) {
    header("location: index.php?fehler=301");
}

if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
    $angemeldet = true;
} else {
    $angemeldet = false;
}

if ($gemaelde && $angemeldet
    && isset($_FILES['datei']) &&
    isset($_POST['titel']) && is_string($_POST['titel']) &&
    isset($_POST['kuenstler']) && is_string($_POST['kuenstler']) &&
    isset($_POST['beschreibung']) && is_string($_POST['beschreibung']) &&
    isset($_POST['datum']) && is_string($_POST['datum']) &&
    isset($_POST['ort']) && is_string($_POST['ort'])) {
    $dateityp = strtolower(pathinfo(htmlspecialchars($_FILES['datei']['name']), PATHINFO_EXTENSION));
    $erstellung = $dao->gemaelde_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($dateityp), htmlspecialchars($_POST['titel']),
        htmlspecialchars($_POST['kuenstler']), htmlspecialchars($_POST['beschreibung']),
        htmlspecialchars($_POST['datum']), htmlspecialchars($_POST['ort']));

    if ($erstellung !== -1) {
        $speichern_unter = $abs_path . '/images/' . $erstellung . '.' . $dateityp;
        if (move_uploaded_file($_FILES['datei']['tmp_name'], $speichern_unter)) {
            $hochladen = true;
        } else {
            $hochladen = false;
        }
    }
}

if (!$gemaelde && $angemeldet
    && isset($_POST['auswahl']) && is_string($_POST['auswahl']) &&
    isset($_POST['titel']) && is_string($_POST['titel']) &&
    isset($_POST['beschreibung']) && is_string($_POST['beschreibung'])) {
    $erstellung = $dao->sammlung_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($_POST['auswahl']),
        htmlspecialchars($_POST['titel']),
        htmlspecialchars($_POST['beschreibung']));
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
    <?php if (isset($angemeldet) && is_bool($angemeldet) && !$angemeldet): ?>
        <p class="nachricht fehler">Du bist nicht angemeldet!</p>
    <?php endif ?>
    <?php if (isset($erstellung) && is_int($erstellung) && $erstellung !== -1): ?>
        <p class="nachricht">Eintragerstellung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($erstellung) && is_int($erstellung) && $erstellung === -1): ?>
        <p class="nachricht fehler">Eintragerstellung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($hochladen) && is_bool($hochladen) && $hochladen): ?>
        <p class="nachricht">Datei erfolgreich hochgeladen</p>
    <?php endif ?>
    <?php if (isset($hochladen) && is_bool($hochladen) && !$hochladen): ?>
        <p class="nachricht fehler">Datei hochladen fehlgeschlagen</p>
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
            <form method="post" action="neuereintrag.php" enctype="multipart/form-data">
                <hr>
                <label for="datei">Gemälde auswählen:</label>
                <input type="file" accept=".png, .jpg" id="datei" name="datei" required>

                <label for="titel">Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="50" required
                    <?php echo (isset($_POST["titel"]) && is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Fügen Sie eine Beschreibung ein..."><?php echo (isset($_POST["beschreibung"]) && is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

                <label for="kuenstler">Künstler:</label>
                <input type="text" id="kuenstler" name="kuenstler" maxlength="100" required
                    <?php echo (isset($_POST["kuenstler"]) && is_string($_POST["kuenstler"])) ? 'value=' . htmlspecialchars($_POST["kuenstler"]) : '' ?>>

                <label for="datum">Datum der Erstellung:</label>
                <input type="date" id="datum" name="datum"
                    <?php echo (isset($_POST["datum"]) && is_string($_POST["datum"])) ? 'value=' . htmlspecialchars($_POST["datum"]) : '' ?>>

                <label for="ort">Ort:</label>
                <input type="text" id="ort" name="ort" maxlength="100"
                    <?php echo (isset($_POST["ort"]) && is_string($_POST["ort"])) ? 'value=' . htmlspecialchars($_POST["ort"]) : '' ?>>

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
                <input type="text" id="auswahl" name="auswahl" pattern="(([0-9]|[1-9][0-9]*),)*([0-9]|[1-9][0-9]*)+"
                       required
                    <?php echo (isset($_POST["auswahl"]) && is_string($_POST["auswahl"])) ? 'value=' . htmlspecialchars($_POST["auswahl"]) : '' ?>>

                <label for="titel">Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="100" required
                    <?php echo (isset($_POST["titel"]) && is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Fügen Sie eine Beschreibung ein..."><?php echo (isset($_POST["beschreibung"]) && is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

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