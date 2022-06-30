<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();


if (isset($_GET["abmelden"]) and is_string($_GET["abmelden"]) and $_GET["abmelden"] === "1") {
    if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
        $abmeldung = $dao->abmelden(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]));
        if (!$abmeldung) $fehlermeldung = 'Datenbankverbindung verloren. Kontaktiere einen Administrator.';
    }
    session_unset();
    session_destroy();
    $erfolgreich = 'Du hast dich erfolgreich abgemeldet!';
}

if (isset($_GET["anmelden"]) and is_string($_GET["anmelden"]) and $_GET["anmelden"] === "1" and isset($_SESSION["id"]) and is_string($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
    $erfolgreich = 'Du hast dich erfolgreich angemeldet!';
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Start";
include $abs_path . '/php/head.php';
?>

<body>

<script src="js/aktionscountdown.js" async></script>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($erfolgreich) and is_string($erfolgreich)): ?>
        <p class="nachricht"><?php echo htmlspecialchars($erfolgreich);?></p>
    <?php endif ?>
    <?php if (isset($fehlermeldung) and is_string($fehlermeldung)): ?>
        <p class="nachricht fehler"><?php echo htmlspecialchars($fehlermeldung);?></p>
    <?php endif ?>

    <h1>Hauptseite</h1>

    <img class="presentation" src="images/start.jpg" alt="Start">
    <p>Der Schwur der Horatier (französisch Le Serment des Horaces) ist ein 1784 fertiggestelltes Gemälde von Jacques-Louis David. Das großformatige Bild (330 × 425 cm) wurde mit Ölfarbe auf Leinwand gemalt.</p>

    <noscript><h4>Am 24. Dezember 2022 um 23:59:59 findet die Auktion für das Gemälde statt.</h4></noscript>
    <h4 id="aktionscountdown"></h4>
</main>


<?php include $abs_path . '/php/footer.php'; ?>
</body>

</html>