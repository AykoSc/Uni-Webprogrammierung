<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Profil bearbeiten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST['submit'])) {
    $sessionId = htmlspecialchars($_SESSION["id"]);
    $sessionToken = htmlspecialchars($_SESSION["token"]);
    $neuBeschreibung = (isset($_POST['beschreibung']) && is_string($_POST['beschreibung'])) ? htmlspecialchars($_POST['beschreibung']) : '';
    $neuGeschlecht = (isset($_POST['geschlecht']) && is_string($_POST['geschlecht'])) ? htmlspecialchars($_POST['geschlecht']) : '';
    $neuVollstaendigerName = (isset($_POST['vollstaendigerName']) && is_string($_POST['vollstaendigerName'])) ? htmlspecialchars($_POST['vollstaendigerName']) : '';
    $neuAdresse = (isset($_POST['adresse']) && is_string($_POST['adresse'])) ? htmlspecialchars($_POST['adresse']) : '';
    $neuSprache = (isset($_POST['sprache']) && is_string($_POST['sprache'])) ? htmlspecialchars($_POST['sprache']) : '';
    $neuGeburtsdatum = (isset($_POST['geburtsdatum']) && is_string($_POST['geburtsdatum'])) ? htmlspecialchars($_POST['geburtsdatum']) : '';

    if ($neuGeschlecht !== "m" && $neuGeschlecht !== "w" && $neuGeschlecht !== "") {
        $profilbearbeitung = false;
        $fehlermeldung = "Sie haben Ihr Geschlecht im falschen Format angegeben. Bitte wählen Sie 'm', 'w' oder lassen Sie das Feld leer.";
    } else {
        $profilbearbeitung = $dao->profil_editieren($sessionId, $sessionToken, $neuBeschreibung, $neuGeschlecht, $neuVollstaendigerName, $neuAdresse, $neuSprache, $neuGeburtsdatum);
        if (!$profilbearbeitung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}

// Profil laden
$selektiert = ""; //default value
if (isset($_REQUEST["id"]) && is_string($_REQUEST["id"])) {
    $profil = $dao->profil_erhalten(htmlspecialchars($_REQUEST["id"]));
    if ($profil !== [-1]) {
        $id = htmlspecialchars($profil[0]);
        $nutzername = htmlspecialchars($profil[1]);
        $beschreibung = htmlspecialchars($profil[2]);
        $geschlecht = htmlspecialchars($profil[3]);
        if (isset($profil[3])) {
            $selektiert = htmlspecialchars($profil[3]);
        }
        $vollstaendigerName = htmlspecialchars($profil[4]);
        $adresse = htmlspecialchars($profil[5]);
        $sprache = htmlspecialchars($profil[6]);
        $geburtsdatum = htmlspecialchars($profil[7]);
        $registrierungsdatum = htmlspecialchars($profil[8]);
    } else {
        header("location: index.php?fehler=401");
    }
} else {
    header("location: index.php?fehler=402");
}

// Profil löschen
if (isset($_SESSION["id"]) && isset($_SESSION["token"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $loeschung = $dao->profil_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_GET["id"]));
    if ($loeschung) {
        session_unset();
        session_destroy();
        header("location: index.php?entfernt=Profil");
    } else {
        $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}
if (isset($_SESSION["id"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && htmlspecialchars($_POST["loeschen"]) === "nichtbestaetigt") {
    $fehlermeldung = "Um dieses Profil zu löschen müssen Sie den Bestätigungshaken setzen.";
}

?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Mein Profil";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($profilbearbeitung) && is_bool($profilbearbeitung) && !$profilbearbeitung && isset($fehlermeldung) && is_string($fehlermeldung)): ?>
        <p class="nachricht fehler">Profil fehlgeschlagen: <?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($profilbearbeitung) && is_bool($profilbearbeitung) && $profilbearbeitung): ?>
        <p class="nachricht">Editierung erfolgreich!</p>
    <?php endif ?>

    <h1>Mein Profil</h1>

    <div class="profil">
        <?php if (isset($_SESSION["id"]) && $id == htmlspecialchars($_SESSION["id"])) : ?>
        <h2>Willkommen auf deinem Profil!</h2>
        <form method="post">
            <h3>Nutzername</h3>
            <p><?php echo $nutzername ?></p>

            <h3>Registrierungsdatum</h3>
            <p><?php echo date("d.m.Y", strtotime($registrierungsdatum)); ?></p>

            <h3>Beschreibung</h3>
            <label for="beschreibung" class="invisible">Beschreibung</label>
            <textarea id="beschreibung" cols="70" rows="10" name="beschreibung"><?php echo $beschreibung ?></textarea>

            <h3>Geschlecht</h3>
            <label for="geschlecht" class="invisible">Geschlecht</label>
            <select id="geschlecht" name="geschlecht">
                <option value="" <?php echo ($geschlecht === '') ? 'selected' : ''?>>Keine Angabe</option>
                <option value="m" <?php echo ($geschlecht === 'm') ? 'selected' : ''?>>Männlich</option>
                <option value="w" <?php echo ($geschlecht === 'w') ? 'selected' : ''?>>Weiblich</option>
            </select>


            <h3>Vollständiger Name</h3>
            <label for="vollstaendigerName" class="invisible">Vollständiger Name</label>
            <input id="vollstaendigerName" type="text" name="vollstaendigerName"
                   value="<?php echo htmlspecialchars($vollstaendigerName) ?>"/>

            <h3>Adresse</h3>
            <label for="adresse" class="invisible">Adresse</label>
            <input id="adresse" type="text" name="adresse"
                   value="<?php echo htmlspecialchars($adresse) ?>"/>

            <h3>Sprache</h3>
            <label for="sprache" class="invisible">Sprache</label>
            <input id="sprache" type="text" name="sprache"
                   value="<?php echo htmlspecialchars($sprache) ?>"/>

            <h3>Geburtsdatum</h3>
            <label for="geburtsdatum" class="invisible">Geburtsdatum</label>
            <input id="geburtsdatum" type="date" name="geburtsdatum"
                   value="<?php echo date("Y-m-d", strtotime(htmlspecialchars($geburtsdatum))); ?>"/>
            <br>
            <br>
            <button id="submit" name="submit" type="submit">Speichern</button>

        <form method="post">
                <h3>Profil löschen?</h3>
                <input type="hidden" name="loeschen" value="nichtbestaetigt"/>
                <input id="loeschen" type="checkbox" name="loeschen" value="loeschbestaetigung"/>
                <label for="loeschen">Löschen bestätigen</label>
                <input type="submit" value="Löschen"/>
            </form>

            <?php else: ?>
            <h2>Willkommen auf dem Profil!</h2>
            <h3>Nutzername</h3>
            <p><?php echo $nutzername ?></p>
            <?php if ($beschreibung !== '') : ?>
                 <h3>Beschreibung</h3>
                 <p><?php echo $beschreibung ?></p>
            <?php endif ?>
            <?php if ($geschlecht !== '') : ?>
                <h3>Geschlecht</h3>
                <p><?php echo ($geschlecht === 'w') ? 'Weiblich' : ''?><?php echo ($geschlecht === 'm') ? 'Männlich' : ''?></p>
            <?php endif ?>
            <?php if ($vollstaendigerName !== '') : ?>
                <h3>Vollständiger Name</h3>
                <p><?php echo $vollstaendigerName ?></p>
            <?php endif ?>
            <?php if ($adresse !== '') : ?>
                <h3>Adresse</h3>
                <p><?php echo $adresse ?></p>
            <?php endif ?>
            <?php if ($sprache !== '') : ?>
                <h3>Sprache</h3>
                <p><?php echo $sprache ?></p>
            <?php endif ?>
            <?php if ($geburtsdatum !== '') : ?>
                <h3>Geburtsdatum</h3>
                <p><?php echo date("d.m.Y", strtotime($geburtsdatum)); ?></p>
            <?php endif ?>
            <h3>Registrierungsdatum</h3>
            <p><?php echo date("d.m.Y", strtotime($registrierungsdatum)); ?></p>
        <?php endif ?>

    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>