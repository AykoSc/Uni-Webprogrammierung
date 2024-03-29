<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();


if (isset($_GET["abmelden"]) && is_string($_GET["abmelden"]) && $_GET["abmelden"] === "1") {
    if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
        $abmeldung = $dao->abmelden($_SESSION["id"], $_SESSION["token"]);
        if (!$abmeldung) $fehlermeldung = 'Datenbankverbindung verloren. Kontaktiere einen Administrator.';
    }
    session_unset();
    session_destroy();
    $erfolgreich = 'Du hast dich erfolgreich abgemeldet!';
}

if (isset($_GET["fehler"]) && is_string($_GET["fehler"])) {
    $fehlermeldung = 'Es scheint ein kritischer Fehler aufgetreten zu sein! ' . $_GET["fehler"] . ' existiert nicht.';
}
if (isset($_GET["entfernt"]) && is_string($_GET["entfernt"])) {
    $erfolgreich = $_GET["entfernt"] . ' erfolgreich gelöscht!';
}
if (isset($_GET["autoloeschung"]) && $_GET["autoloeschung"] === "Sammlung") {
    $erfolgreich = "Die Sammlung wurde gelöscht, da Sie keine Gemälde mehr enthält.";
}
if (isset($_GET["anmelden"]) && $_GET["anmelden"] === "1" && isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
    $erfolgreich = 'Du hast dich erfolgreich angemeldet!';
}

// API für Währungskurswechsel https://www.alphavantage.co/documentation/#currency-exchange
if (isset($_POST["umrechnung_zeigen"]) && is_string($_POST["umrechnung_zeigen"])) {
    $json = file_get_contents('https://www.alphavantage.co/query?function=CURRENCY_EXCHANGE_RATE&from_currency=EUR&to_currency=USD&apikey=J7QN7XYS9PJLT1WZ');
    if ($json !== false) {
        $daten = json_decode($json, true);
        if (isset($daten["Realtime Currency Exchange Rate"]["2. From_Currency Name"]) && isset($daten["Realtime Currency Exchange Rate"]["4. To_Currency Name"]) && isset($daten["Realtime Currency Exchange Rate"]["5. Exchange Rate"])) {
            $wechselkurs = $daten["Realtime Currency Exchange Rate"];
            $von_name = $wechselkurs["2. From_Currency Name"];
            $von_preis = 5000.00;
            $zu_name = $wechselkurs["4. To_Currency Name"];
            $zu_preis = round($wechselkurs["5. Exchange Rate"] * $von_preis, 2);
        }
    }
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
    <?php if (isset($erfolgreich) && is_string($erfolgreich)): ?>
        <p class="nachricht"><?php echo htmlspecialchars($erfolgreich); ?></p>
    <?php endif ?>
    <?php if (isset($fehlermeldung) && is_string($fehlermeldung)): ?>
        <p class="nachricht fehler"><?php echo htmlspecialchars($fehlermeldung); ?></p>
    <?php endif ?>

    <h1>Hauptseite</h1>
    <h3>Willkommen auf ArtPlace - dem Forum für Kunstliebhaber</h3>
    <h4>Noch nie war kreativer Austausch so einfach!</h4>

    <img class="presentation" src="images/start.jpg" alt="Start">
    <p>Der Schwur der Horatier (französisch Le Serment des Horaces) ist ein 1784 fertiggestelltes Gemälde von
        Jacques-Louis David. Das großformatige Bild (330 × 425 cm) wurde mit Ölfarbe auf Leinwand gemalt.</p>

    <h4 id="aktionscountdown">Am 24. Dezember 2022 um 23:59:59 findet die Auktion für das Gemälde statt!</h4>

    <?php if (isset($daten["Realtime Currency Exchange Rate"])
        && isset($von_name) && is_string($von_name) && isset($von_preis) && is_double($von_preis)
        && isset($zu_name) && is_string($zu_name) && isset($zu_preis) && is_double($zu_preis)): ?>
        <ul>
            <li>Startpreis in <?php echo htmlspecialchars($von_name . ': ' . $von_preis); ?></li>
            <li>Startpreis in <?php echo htmlspecialchars($zu_name . ': ' . $zu_preis); ?></li>
        </ul>
    <?php elseif (isset($daten)): ?>
        <ul>
            <li>Startpreis in Euro: 5000</li>
            <li>Startpreis konnte nicht umgewandelt werden, da die API nur begrenzt Aufrufe zulässt.</li>
        </ul>
    <?php else: ?>
        <ul>
            <li>Startpreis in Euro: 5000</li>
            <li>
                <p>Mit dem Aufruf der Umrechnung des Preises von Euro in USD erklären Sie
                    sich einverstanden, dass Ihre Daten an Alphavantage Inc. übermittelt
                    werden und dass Sie die <a href="datenschutz.php">Datenschutzerklärung</a> gelesen haben.</p>
                <form method="post">
                    <button type="submit" name="umrechnung_zeigen" class="datenschutzbestaetigung">Bestätigen und
                        Umrechnung anzeigen
                    </button>
                </form>
            </li>
        </ul>
    <?php endif ?>

</main>


<?php include $abs_path . '/php/footer.php'; ?>
</body>

</html>