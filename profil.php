<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

//Profil bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"]) and isset($_POST['submit'])) {
    $sessionId = htmlspecialchars($_SESSION["id"]);
    $sessionToken = htmlspecialchars($_SESSION["token"]);
    $neuBeschreibung = (isset($_POST['beschreibung']) and is_string($_POST['beschreibung'])) ? htmlspecialchars($_POST['beschreibung']) : '';
    $neuGeschlecht = (isset($_POST['geschlecht']) and is_string($_POST['geschlecht'])) ? htmlspecialchars($_POST['geschlecht']) : '';
    $neuVollstaendigerName = (isset($_POST['vollstaendigerName']) and is_string($_POST['vollstaendigerName'])) ? htmlspecialchars($_POST['vollstaendigerName']) : '';
    $neuAdresse = (isset($_POST['adresse']) and is_string($_POST['adresse'])) ? htmlspecialchars($_POST['adresse']) : '';
    $neuSprache = (isset($_POST['sprache']) and is_string($_POST['sprache'])) ? htmlspecialchars($_POST['sprache']) : '';
    $neuGeburtsdatum = (isset($_POST['geburtsdatum']) and is_string($_POST['geburtsdatum'])) ? htmlspecialchars($_POST['geburtsdatum']) : '';

    if ($neuGeschlecht !== "m" and $neuGeschlecht !== "w" and $neuGeschlecht !== "") {
        $profilbearbeitung = false;
        $fehlermeldung = "Sie haben Ihr Geschlecht im falschen Format angegeben. Bitte wählen Sie 'm', 'w' oder lassen Sie das Feld leer.";
    } else {
        $profilbearbeitung = $dao->profil_editieren($sessionId, $sessionToken, $neuBeschreibung, $neuGeschlecht, $neuVollstaendigerName, $neuAdresse, $neuSprache, $neuGeburtsdatum);
        if (!$profilbearbeitung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}

//Profil laden
if (isset($_REQUEST["id"]) and is_string($_REQUEST["id"])) {
    $profil = $dao->profil_erhalten(htmlspecialchars($_REQUEST["id"]));
    if ($profil !== [-1]) {
        $id = htmlspecialchars($profil[0]);
        $nutzername = htmlspecialchars($profil[1]);
        $beschreibung = htmlspecialchars($profil[2]);
        $geschlecht = htmlspecialchars($profil[3]);
        $vollstaendigerName = htmlspecialchars($profil[4]);
        $adresse = htmlspecialchars($profil[5]);
        $sprache = htmlspecialchars($profil[6]);
        $geburtsdatum = htmlspecialchars($profil[7]);
        $registrierungsdatum = htmlspecialchars($profil[8]);
    } else {
        header("location: index.php");
    }
} else {
    header("location: index.php");
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
    <?php if (isset($profilbearbeitung) and is_bool($profilbearbeitung) and !$profilbearbeitung and isset($fehlermeldung) and is_string($fehlermeldung)): ?>
        <p class="nachricht fehler">Profilbearbeitung fehlgeschlagen: <?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($profilbearbeitung) and is_bool($profilbearbeitung) and $profilbearbeitung): ?>
        <p class="nachricht">Editierung erfolgreich!</p>
    <?php endif ?>

    <h1>Mein Profil</h1>

    <div class="profil">
        <?php if (isset($_SESSION["id"]) and $id == htmlspecialchars($_SESSION["id"])) : ?>
        <h2>Willkommen auf deinem Profil!</h2>
        <form method="post">
            <h3>Nutzername</h3>
            <p><?php echo $nutzername ?></p>

            <h3>Registrierungsdatum</h3>
            <p><?php echo $registrierungsdatum ?></p>

            <h3>Beschreibung</h3>
            <label for="beschreibung" class="invisible">Beschreibung</label>
            <textarea id="beschreibung" cols="70" rows="10" name="beschreibung"><?php echo $beschreibung ?></textarea>

            <h3>Geschlecht</h3>
            <label for="geschlecht" class="invisible">Geschlecht</label>
            <input id="geschlecht" type="text" name="geschlecht"
                   value="<?php echo htmlspecialchars($geschlecht) ?>"/>

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
                   value="<?php echo htmlspecialchars($geburtsdatum) ?>"/>

            <button id="submit" name="submit" type="submit">Speichern</button>

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
                    <p><?php echo $geschlecht ?></p>
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
                <p><?php echo $registrierungsdatum ?></p>
            <?php endif ?>

    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>