<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Eintrag bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_REQUEST["id"]) and is_string($_REQUEST["id"]) and
    isset($_POST['titel']) and is_string($_POST['titel']) and
    isset($_POST['beschreibung']) and is_string($_POST['beschreibung'])) {
    $editierung = $dao->sammlung_editieren(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($_REQUEST["id"]), htmlspecialchars($_POST['titel']), htmlspecialchars($_POST['beschreibung']));
    if (!$editierung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
}

// Eintrag laden
if (isset($_REQUEST["id"]) and is_string($_REQUEST["id"])) {
    if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
        isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
        $eigene_bewertung = $dao->eigene_sammlung_bewertung_erhalten(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_GET["id"]));
    }

    $sammlung = $dao->sammlung_erhalten(htmlspecialchars($_REQUEST["id"]));
} else {
    header("location: index.php?fehler=Sammlung");
}

// Eintrag bewerten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_GET["id"]) and is_string($_GET["id"]) and
    isset($_POST["bewertung"]) and is_string($_POST["bewertung"])) {
    $bewertet = $dao->sammlung_bewerten(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_GET["id"]), htmlspecialchars($_POST["bewertung"]));
}

// Eintrag löschen
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $loeschung = $dao->sammlung_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_GET["id"]));
    if ($loeschung) {
        header("location: index.php?entfernt=Sammlung");
    } else {
        $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "nichtbestaetigt") {
    $fehlermeldung = "Um diese Sammlung zu löschen müssen Sie den Bestätigungshaken setzen.";
}

if (isset($sammlung) and is_array($sammlung) and $sammlung !== [-1]) {
    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    $id = $sammlung[0];
    $anbieter = $dao->profil_erhalten($sammlung[1]); //$sammlung[1] ist anbieterID
    $alle_gemaelde = array();
    foreach ($sammlung[2] as $gemaeldeID) { //$sammlung[2] sind gemaeldeIDs
        $alle_gemaelde[] = $dao->gemaelde_erhalten(htmlspecialchars($gemaeldeID));
    }
    $titel = htmlspecialchars($sammlung[3]);
    $beschreibung = htmlspecialchars($sammlung[4]);
    $bewertung = htmlspecialchars($sammlung[5]);
    $hochladedatum = htmlspecialchars($sammlung[6]);
    $aufrufe = htmlspecialchars($sammlung[7]);
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
<script src="js/bewertungaktion.js"></script>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung)): ?>
        <p class="nachricht fehler">Es gab einen Fehler: <?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($editierung) and is_bool($editierung) and $editierung): ?>
        <p class="nachricht">Editierung erfolgreich!</p>
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
    <?php endforeach; ?>

    <?php if (isset($_SESSION["id"]) and htmlspecialchars($sammlung[1]) == htmlspecialchars($_SESSION["id"])) : ?>
        <form method="post">
            <h3>Sammlung löschen?</h3>
            <input type="hidden" name="loeschen" value="nichtbestaetigt"/>
            <input id="loeschen" type="checkbox" name="loeschen" value="loeschbestaetigung"/>
            <label for="loeschen">Löschen bestätigen</label>
            <input type="submit" value="Löschen"/>
        </form>

        <h2>Infos zur Sammlung</h2>
        <form method="post">
            <h3>Beschreibung</h3>
            <label for="beschreibung" class="invisible">Beschreibung</label>
            <textarea cols="70" rows="10" id="beschreibung" name="beschreibung"><?php echo $beschreibung ?></textarea>

            <h3>Titel</h3>
            <label for="titel" class="invisible">Titel</label>
            <input id="titel" type="text" name="titel" value="<?php echo htmlspecialchars($titel) ?>">
            <input type="submit" name="Submit" value="Speichern"/>
        </form>

    <?php else: ?>
        <h2>Infos zur Sammlung</h2>
        <h3>Beschreibung</h3>
        <p><?php echo $beschreibung ?></p>
    <?php endif ?>


    <div id="bewertung">
        <h3>Bewertung</h3>
        <p>Gesamtbewertung:</p>
        <?php for ($i = 1; $i <= $bewertung; $i++) { ?>
            <img class="icons" src="images/stern_gelb.svg" alt="bewertunggesamt"/>
        <?php } ?>
        <?php for ($i = $bewertung + 1; $i <= 5; $i++) { ?>
            <img class="icons" src="images/stern_schwarz.svg" alt="bewertunggesamt"/>
        <?php } ?>

        <?php if (isset($_SESSION["id"])): ?>
            <p>Deine Bewertung:</p>
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


    <p><?php echo $bewertung ?></p>
    <h3>Hochladedatum</h3>
    <p><?php echo date("d.m.Y", strtotime($hochladedatum)); ?></p>
    <h3>Aufrufe</h3>
    <p><?php echo $aufrufe ?></p>
</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>