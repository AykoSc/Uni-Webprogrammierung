<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$user = NutzerDAODBImpl::getInstance();

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

if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
    $angemeldet = true;
} else {
    $angemeldet = false;
}

if ($gemaelde and $angemeldet) {
    if (isset($_FILES['datei']) and
        isset($_POST['titel']) and is_string($_POST['titel']) and
        isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
        isset($_POST['kuenstler']) and is_string($_POST['kuenstler']) and
        isset($_POST['datum']) and is_string($_POST['datum']) and
        isset($_POST['ort']) and is_string($_POST['ort'])) {
        $datei_typ = strtolower(pathinfo(htmlspecialchars($_FILES['datei']['name']),PATHINFO_EXTENSION));
        $erstellung = $user->gemaelde_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
            htmlspecialchars($datei_typ), htmlspecialchars($_POST['titel']),
            htmlspecialchars($_POST['beschreibung']), htmlspecialchars($_POST['kuenstler']),
            htmlspecialchars($_POST['datum']), htmlspecialchars($_POST['ort']));

        if ($erstellung != -1) {
            $speichern_unter = $abs_path . '/images/' . $erstellung . '.' . $datei_typ;
            if (move_uploaded_file($_FILES['datei']['tmp_name'], $speichern_unter)) {
                $hochladen = true;
            } else {
                $hochladen = false;
            }
        }

    }
}

if (!$gemaelde and $angemeldet) {
    if (isset($_POST['auswahl']) and is_string($_POST['auswahl']) and
        isset($_POST['titel']) and is_string($_POST['titel']) and
        isset($_POST['beschreibung']) and is_string($_POST['beschreibung'])) {
        $erstellung = $user->sammlung_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
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
    <?php if (isset($erstellung) and is_int($erstellung) and $erstellung != -1): ?>
        <p class="nachricht">Eintragerstellung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($erstellung) and is_int($erstellung) and !$erstellung == -1): ?>
        <p class="nachricht fehler">Eintragerstellung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($hochladen) and is_bool($hochladen) and $hochladen): ?>
        <p class="nachricht">Datei erfolgreich hochgeladen</p>
    <?php endif ?>
    <?php if (isset($hochladen) and is_bool($hochladen) and !$hochladen): ?>
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