<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["id"]) and is_string($_GET["id"])) {
    $sammlung = $dao->sammlung_erhalten(htmlspecialchars($_GET["id"]));
} else {
    header("location: index.php");
}

if (isset($_SESSION["id"]) and isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $result = $dao->sammlung_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_GET["id"]));
    if (!$result) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
}

if (isset($_SESSION["id"]) and isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "nichtbestaetigt") {
    $fehlermeldung = "Um diese Sammlung zu löschen müssen Sie den Bestätigungshaken setzen.";
}

//Eintrag bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_GET["id"]) and is_string($_GET["id"]) and
    isset($_POST['titel']) and is_string($_POST['titel']) and
    isset($_POST['beschreibung']) and is_string($_POST['beschreibung'])) {
    $sammlung = $dao->sammlung_editieren(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($_GET["id"]), htmlspecialchars($_POST['titel']), htmlspecialchars($_POST['beschreibung']));
}

if (isset($sammlung) and is_array($sammlung) and $sammlung !== [-1]) {
    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    $id = $sammlung[0];
    $anbieter = $dao->profil_erhalten($sammlung[1]); //$sammlung[1] ist anbieterID
    $alle_gemaelde = array();
    foreach ($sammlung[2] as $gemaeldeID) { //$sammlung[2] sind gemaeldeIDs
        $alle_gemaelde[] = $dao->gemaelde_erhalten(htmlspecialchars($gemaeldeID));
    }
    $titel = $sammlung[3];
    $beschreibung = $sammlung[4];
    $bewertung = $sammlung[5];
    $hochladedatum = $sammlung[6];
    $aufrufe = $sammlung[7];
} else {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Sammlung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung)): ?>
        <p class="nachricht fehler">Es gab einen Fehler: <?php echo $fehlermeldung ?></p>
    <?php endif ?>

    <h1>Sammlung</h1>
    <h2><?php echo $titel ?></h2>

    <?php foreach ($alle_gemaelde as $gemaelde): ?>
        <h3><?php echo $gemaelde[2] ?></h3>
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

    <h3>Bewertung</h3>
    <p><?php echo $bewertung ?></p>
    <h3>Hochladedatum</h3>
    <p><?php echo $hochladedatum ?></p>
    <h3>Aufrufe</h3>
    <p><?php echo $aufrufe ?></p>
</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>