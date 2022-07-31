<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Eintrag bearbeiten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_REQUEST["id"]) && is_string($_REQUEST["id"]) && isset($_POST['titel']) && is_string($_POST['titel']) && isset($_POST['beschreibung']) && is_string($_POST['beschreibung'])) {
    $editierung = $dao->sammlung_editieren($_SESSION["id"], $_SESSION["token"], $_REQUEST["id"], $_POST['titel'], $_POST['beschreibung']);
    if (!$editierung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
}

// Eintrag bewerten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_GET["id"]) && is_string($_GET["id"]) && isset($_POST["bewertung"]) && is_string($_POST["bewertung"])) {
    $bewertet = $dao->sammlung_bewerten($_SESSION["id"], $_SESSION["token"], $_GET["id"], $_POST["bewertung"]);
}

// Gemaelde entfernen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["gemaelde_entfernen"]) && is_string($_POST["gemaelde_entfernen"])) {
    $gemaeldeEntfernung = $dao->gemaelde_aus_sammlung_entfernen($_SESSION["id"], $_SESSION["token"], $_REQUEST["id"], $_POST["gemaelde_entfernen"]);
}

// Eintrag laden
if (isset($_REQUEST["id"]) && is_string($_REQUEST["id"])) {
    if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
        $eigene_bewertung = $dao->eigene_sammlung_bewertung_erhalten($_SESSION["id"], $_GET["id"]);
    }
    $sammlung = $dao->sammlung_erhalten($_REQUEST["id"]);
    if ($sammlung[0] === -2) {
        header("location: index.php?autoloeschung=Sammlung");
        exit;
    }
} else {
    header("location: index.php?fehler=Sammlung");
}

// Eintrag löschen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $loeschung = $dao->sammlung_entfernen($_SESSION["id"], $_SESSION["token"], $_GET["id"]);
    if ($loeschung) {
        header("location: index.php?entfernt=Sammlung");
    } else {
        $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && $_POST["loeschen"] === "nichtbestaetigt") {
    $fehlermeldung = "Um diese Sammlung zu löschen müssen Sie den Bestätigungshaken setzen.";
}

if (isset($sammlung) && is_array($sammlung) && $sammlung !== [-1]) {
    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    $id = $sammlung[0];
    $alle_gemaelde = array();
    foreach ($sammlung[2] as $gemaeldeID) { //$sammlung[2] sind gemaeldeIDs
        $alle_gemaelde[] = $dao->gemaelde_erhalten($gemaeldeID);
    }
    $titel = htmlspecialchars($sammlung[3]);
    $beschreibung = htmlspecialchars($sammlung[4]);
    $bewertung = $sammlung[5];
    $hochladedatum = $sammlung[6];
    $aufrufe = htmlspecialchars($sammlung[7]);
    $anbieterID = urlencode($sammlung[1]);
    $anbieterName = htmlspecialchars($sammlung[8]);
} else {
    header("location: index.php?fehler=Sammlung");
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Sammlung";
include $abs_path . '/php/head.php';
?>

<body>
<script src="js/jquery.min.js"></script>
<script src="js/bewertungaktion.js"></script>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung)): ?>
        <p class="nachricht fehler">Es gab einen Fehler: <?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($editierung) && is_bool($editierung) && $editierung): ?>
        <p class="nachricht">Editieren erfolgreich!</p>
    <?php endif ?>

    <?php if (isset($bewertet) && is_bool($bewertet) && $bewertet): ?>
        <p class="nachricht">Sammlung erfolgreich bewertet</p>
    <?php endif ?>
    <?php if (isset($bewertet) && is_bool($bewertet) && !$bewertet): ?>
        <p class="nachricht fehler">Sammlung Bewertung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($gemaeldeEntfernung) && is_bool($gemaeldeEntfernung) && $gemaeldeEntfernung): ?>
        <p class="nachricht">Gemälde erfolgreich aus der Sammlung entfernt</p>
    <?php endif ?>
    <?php if (isset($gemaeldeEntfernung) && is_bool($gemaeldeEntfernung) && !$gemaeldeEntfernung): ?>
        <p class="nachricht fehler">Gemälde Entfernung fehlgeschlagen</p>
    <?php endif ?>

    <h1>Sammlung</h1>
    <h2><?php echo $titel ?></h2>

    <?php foreach ($alle_gemaelde as $gemaelde): ?>
        <h3><?php echo htmlspecialchars($gemaelde[2]) ?></h3>
        <a href="gemaelde.php?id=<?php echo htmlspecialchars($gemaelde[0]) ?>">
            <img class="presentation"
                 src="<?php echo "images/" . htmlspecialchars($gemaelde[0]) . "." . htmlspecialchars($gemaelde[10]) ?>"
                 alt="<?php echo htmlspecialchars($gemaelde[2]) ?>">
        </a>
        <?php if (isset($_SESSION["id"]) && $anbieterID === $_SESSION["id"]) : ?>
            <div class="delete">
                <form method="post">
                    <input type="hidden" name="gemaelde_entfernen"
                           value="<?php echo htmlspecialchars($gemaelde[0]) ?>">
                    <input type="image" alt="trashbin" src="images/mulleimer.png" width="20">
                </form>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <h2>Über die Sammlung</h2>

    <?php if (isset($_SESSION["id"]) && $sammlung[1] == $_SESSION["id"]) : ?>
        <form method="post">
            <h3>Beschreibung</h3>
            <label for="beschreibung" class="invisible">Beschreibung</label>
            <textarea cols="70" rows="10" id="beschreibung"
                      name="beschreibung"><?php echo $beschreibung ?></textarea>

            <div class="grid">
                <div class="item">
                    <h3>Titel</h3>
                    <label for="titel" class="invisible">Titel</label>
                    <input id="titel" type="text" name="titel" value="<?php echo $titel ?>" required>
                </div>

                <div class="item" id="bewertung">
                    <h3>Bewertung</h3>
                    <p>Gesamtbewertung</p>
                    <?php for ($i = 1; $i <= $bewertung; $i++) { ?>
                        <img class="icons" src="images/stern_gelb.svg" alt="bewertunggesamt"/>
                    <?php } ?>
                    <?php for ($i = $bewertung + 1; $i <= 5; $i++) { ?>
                        <img class="icons" src="images/stern_schwarz.svg" alt="bewertunggesamt"/>
                    <?php } ?>
                </div>

                <div class="item">
                    <h3>Hochladedatum</h3>
                    <p><?php echo date("d.m.Y", strtotime($hochladedatum)); ?></p>
                </div>

                <div class="item">
                    <h3>Aufrufe</h3>
                    <p><?php echo $aufrufe ?></p>
                </div>

                <div class="item">
                    <h3>Anbieter</h3>
                    <a href="profil.php?id=<?php echo $anbieterID ?>"><?php echo $anbieterName ?></a>
                </div>

                <div class="item">
                    <input type="submit" name="Submit" value="Speichern"/>
                </div>
            </div>
        </form>

        <form method="post">
            <h3>Sammlung löschen?</h3>
            <input type="hidden" name="loeschen" value="nichtbestaetigt"/>
            <input id="loeschen" type="checkbox" name="loeschen" value="loeschbestaetigung"/>
            <label for="loeschen">Löschen bestätigen</label>
            <input type="submit" value="Löschen"/>
        </form>

    <?php else: ?>
        <h3>Beschreibung</h3>
        <p><?php echo $beschreibung ?></p>
        <div class="grid">
            <div class="item">
                <h3>Titel</h3>
                <p><?php echo $titel ?></p>
            </div>

            <div id="bewertung" class="item">
                <h3>Bewertung</h3>
                <p>Gesamtbewertung</p>
                <?php for ($i = 1; $i <= $bewertung; $i++) { ?>
                    <img class="icons" src="images/stern_gelb.svg" alt="bewertunggesamt"/>
                <?php } ?>
                <?php for ($i = $bewertung + 1; $i <= 5; $i++) { ?>
                    <img class="icons" src="images/stern_schwarz.svg" alt="bewertunggesamt"/>
                <?php } ?>

                <?php if (isset($_SESSION["id"]) && $_SESSION["id"] != $sammlung[1]) : ?>
                    <p>Deine Bewertung</p>
                    <form method="post">
                        <?php for ($i = 1; $i <= $eigene_bewertung; $i++) { ?>
                            <button class="bewertung" name="bewertung" value="<?php echo $i ?>">
                                <img class="icons" src="images/stern_gelb.svg" alt="eigenebewertung"/>
                            </button>
                        <?php } ?>
                        <?php for ($i = $eigene_bewertung + 1; $i <= 5; $i++) { ?>
                            <button class="bewertung" name="bewertung" value="<?php echo $i ?>">
                                <img class="icons" src="images/stern_schwarz.svg" alt="eigenebewertung"/>
                            </button>
                        <?php } ?>
                    </form>
                <?php endif; ?>
            </div>

            <div class="item">
                <h3>Hochladedatum</h3>
                <p><?php echo date("d.m.Y", strtotime($hochladedatum)); ?></p>
            </div>

            <div class="item">
                <h3>Aufrufe</h3>
                <p><?php echo $aufrufe ?></p>
            </div>

            <div class="item">
                <h3>Aufrufe</h3>
                <p><?php echo $aufrufe ?></p>
            </div>

            <div class="item">
                <h3>Anbieter</h3>
                <a href="profil.php?id=<?php echo $anbieterID ?>"><?php echo $anbieterName ?></a>
            </div>
        </div>
    <?php endif ?>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>