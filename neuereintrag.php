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
    header("location: index.php?fehler=Neuer-Eintrag-Typ");
}

if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
    $angemeldet = true;
} else {
    $angemeldet = false;
}

if ($gemaelde && $angemeldet && isset($_FILES['datei']) && isset($_POST['titel']) && is_string($_POST['titel']) && isset($_POST['kuenstler']) && is_string($_POST['kuenstler']) && isset($_POST['beschreibung']) && is_string($_POST['beschreibung']) && isset($_POST['datum']) && is_string($_POST['datum']) && isset($_POST['ort']) && is_string($_POST['ort'])) {
    $dateityp = strtolower(pathinfo($_FILES['datei']['name'], PATHINFO_EXTENSION));
    $erstellung = $dao->gemaelde_anlegen($_SESSION["id"], $_SESSION["token"], $dateityp, $_POST['titel'], $_POST['kuenstler'], $_POST['beschreibung'], $_POST['datum'], $_POST['ort']);

    if ($erstellung !== -1) {
        $speichern_unter = $abs_path . '/images/' . $erstellung . '.' . $dateityp;
        if (move_uploaded_file($_FILES['datei']['tmp_name'], $speichern_unter)) {
            $bilddaten = getimagesize($speichern_unter);
            $seitenverhaeltnis = $bilddaten[0] / $bilddaten[1];
            if ($seitenverhaeltnis < 0.2 || $seitenverhaeltnis > 5) {
                $hochladen = false;
                $dao->gemaelde_entfernen($_SESSION["id"], $_SESSION["token"], $erstellung);
                $begruendung = "Das hochgeladene Bild hat mit $seitenverhaeltnis ein nicht erlaubtes Seitenverhältnis.";
            } else {
                $hochladen = true;
                header("location: gemaelde.php?id=" . $erstellung);
            }
        } else {
            $hochladen = false;
        }
    }
}

if (!$gemaelde && $angemeldet && isset($_POST['auswahl']) && is_string($_POST['auswahl']) && isset($_POST['titel']) && is_string($_POST['titel']) && isset($_POST['beschreibung']) && is_string($_POST['beschreibung'])) {
    $erstellung = $dao->sammlung_anlegen($_SESSION["id"], $_SESSION["token"],
        $_POST['auswahl'], $_POST['titel'], $_POST['beschreibung']);

    if ($erstellung !== -1) header("location: sammlung.php?id=" . $erstellung);
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Neuer Eintrag";
include $abs_path . '/php/head.php';
?>

<body>

<script>
    function suchvorschlaege(suche) {
        if (suche.length === 0) {
            document.getElementById("suchvorschlag").innerHTML = "";
            document.getElementById("suchvorschlag").style.padding = "0px";
            document.getElementById("suchvorschlag").style.border = "0px";
            return;
        }
        const request = new XMLHttpRequest();
        request.onload = function () {
            document.getElementById("suchvorschlag").innerHTML = this.responseText;
            document.getElementById("suchvorschlag").style.padding = "10px";
            document.getElementById("suchvorschlag").style.border = "1px solid grey";
        }
        request.open("GET", "suche.php?herkunft=neuereintrag&suche=" + suche);
        request.send();
    }

    function ideinfuegen(angeklickt) {
        const id = angeklickt.split(": ");
        const neueID = id[id.length - 1];
        if (id.length > 0) {
            let jetzigeAuswahl = document.getElementById("auswahl").value;
            if (jetzigeAuswahl.includes(neueID)) {
                alert("Diese Gemälde-ID haben Sie bereits eingetragen.");
                return;
            }

            if (jetzigeAuswahl.length > 0) {
                jetzigeAuswahl += "," + neueID;
            } else {
                jetzigeAuswahl = neueID;
            }

            document.getElementById("auswahl").value = jetzigeAuswahl;
        }
    }
</script>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($angemeldet) && is_bool($angemeldet) && !$angemeldet): ?>
        <p class="nachricht fehler">Du bist nicht angemeldet!</p>
    <?php endif ?>
    <?php if (isset($erstellung) && is_int($erstellung) && $erstellung !== -1 && !isset($begruendung)): ?>
        <p class="nachricht">Eintragerstellung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($erstellung) && is_int($erstellung) && $erstellung === -1): ?>
        <p class="nachricht fehler">Eintragerstellung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($hochladen) && is_bool($hochladen) && $hochladen): ?>
        <p class="nachricht">Datei erfolgreich hochgeladen</p>
    <?php endif ?>
    <?php if (isset($hochladen) && is_bool($hochladen) && !$hochladen): ?>
        <p class="nachricht fehler">Datei hochladen
            fehlgeschlagen<?php echo (isset($begruendung) && is_string($begruendung)) ? ": " . htmlspecialchars($begruendung) : "" ?></p>
    <?php endif ?>

    <h1>Neuer Eintrag</h1>

    <div class="usermanagement">
        <h3>Hier kannst du einen neuen Eintrag erstellen</h3>
        <form>
            <hr>
            <?php $gegenteil = ($gemaelde) ? 'Sammlung' : 'Gemälde' ?>
            <input type="hidden" name="typ" value="<?php echo htmlspecialchars($gegenteil) ?>">
            <button type="submit"><?php echo htmlspecialchars($gegenteil) ?> erstellen?</button>
            <hr>
        </form>

        <?php if ($gemaelde): ?>
            <h3>Gemälde erstellen</h3>
            <form method="post" action="neuereintrag.php" enctype="multipart/form-data">
                <hr>
                <label for="datei">*Gemälde auswählen (erlaubt ist ein Seitenverhältnis zwischen 0,2 und 5):</label>
                <input type="file" accept=".png, .jpg" id="datei" name="datei" required>

                <label for="titel">*Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="50" required placeholder="Titel eingeben..."
                    <?php echo (isset($_POST["titel"]) && is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Beschreibung eingeben..."><?php echo (isset($_POST["beschreibung"]) && is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

                <label for="kuenstler">*Künstler:</label>
                <input type="text" id="kuenstler" name="kuenstler" maxlength="100" required
                       placeholder="Künstlernamen eingeben..."
                    <?php echo (isset($_POST["kuenstler"]) && is_string($_POST["kuenstler"])) ? 'value=' . htmlspecialchars($_POST["kuenstler"]) : '' ?>>

                <label for="datum">Datum der Erstellung:</label>
                <input type="date" id="datum" name="datum"
                    <?php echo (isset($_POST["datum"]) && is_string($_POST["datum"])) ? 'value=' . htmlspecialchars($_POST["datum"]) : '' ?>>

                <label for="ort">Ort:</label>
                <input type="text" id="ort" name="ort" maxlength="100" placeholder="Erstellungsort eingeben..."
                    <?php echo (isset($_POST["ort"]) && is_string($_POST["ort"])) ? 'value=' . htmlspecialchars($_POST["ort"]) : '' ?>>

                <input type="hidden" name="typ" value="Gemälde">
                <hr>
                <p>Die mit * gekennzeichneten Felder sind Pflichtfelder.</p>
                <button type="submit">Fertigstellen</button>
                <a href="index.php">Abbrechen</a>
            </form>
        <?php else: ?>
            <h3>Sammlung erstellen</h3>
            <form method="post" action="neuereintrag.php">
                <hr>

                <label for="auswahl">*Gemälde-IDs: (z.B.: 1,2,6)</label>
                <input type="text" id="auswahl" name="auswahl" pattern="(([0-9]|[1-9][0-9]*),)*([0-9]|[1-9][0-9]*)+"
                       required placeholder="Gemälde-IDs eingeben..."
                    <?php echo (isset($_POST["auswahl"]) && is_string($_POST["auswahl"])) ? 'value=' . htmlspecialchars($_POST["auswahl"]) : '' ?>>

                <div id="jsOnly">
                    <p>Suchen Sie hier Gemälde, die Sie eintragen möchten, oder fügen Sie sie selber oben hinzu</p>
                    <label for="suche" class="invisible">Gemälde-IDs Suchhilfe</label>
                    <input autocomplete="off" type="text" placeholder="Gemäldenamen eingeben..."
                           name="suche" id="suche"
                           onkeyup="suchvorschlaege(this.value)"
                        <?php echo (isset($_POST["suche"]) && is_string($_POST["suche"])) ? 'value=' . htmlspecialchars($_POST["suche"]) : '' ?>>
                    <div id="suchvorschlag"></div>
                </div>
                <script>
                    document.getElementById('jsOnly').style.display = 'block';
                </script>

                <label for="titel">*Titel:</label>
                <input type="text" id="titel" name="titel" maxlength="100" required placeholder="Titel eingeben..."
                    <?php echo (isset($_POST["titel"]) && is_string($_POST["titel"])) ? 'value=' . htmlspecialchars($_POST["titel"]) : '' ?>>

                <label for="beschreibung">Beschreibung:</label>
                <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                          placeholder="Beschreibung eingeben..."><?php echo (isset($_POST["beschreibung"]) && is_string($_POST["beschreibung"])) ? htmlspecialchars($_POST["beschreibung"]) : '' ?></textarea>

                <input type="hidden" name="typ" value="Sammlung">
                <hr>
                <p>Die mit * gekennzeichneten Felder sind Pflichtfelder.</p>
                <button type="submit">Fertigstellen</button>
                <a href="index.php">Abbrechen</a>
            </form>
        <?php endif ?>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>