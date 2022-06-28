<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

//Profil bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
    isset($_POST['geschlecht']) and is_string($_POST['geschlecht']) and
    isset($_POST['vollstaendigerName']) and is_string($_POST['vollstaendigerName']) and
    isset($_POST['adresse']) and is_string($_POST['adresse']) and
    isset($_POST['sprache']) and is_string($_POST['sprache']) and
    isset($_POST['geburtsdatum']) and is_string($_POST['geburtsdatum'])) {
    if (htmlspecialchars($_POST['geschlecht']) !== "m" && htmlspecialchars($_POST['geschlecht']) !== "w"
        && htmlspecialchars($_POST['geschlecht']) !== "") {
        $fehlermeldung = "Sie haben Ihr Geschlecht im falschen Format angegeben. Bitte wählen Sie 'm', 'w' oder lassen Sie das Feld leer.";
    }
    if (isset($fehlermeldung) and is_string($fehlermeldung)) {
        $profilbearbeitung = false;
    } else {
        $profilbearbeitung = $dao->profil_editieren(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
            htmlspecialchars($_POST['beschreibung']),
            htmlspecialchars($_POST['geschlecht']), htmlspecialchars($_POST['vollstaendigerName']),
            htmlspecialchars($_POST['adresse']), htmlspecialchars($_POST['sprache']), htmlspecialchars($_POST['geburtsdatum']));
        if (!$profilbearbeitung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}


if (isset($_REQUEST["id"]) and is_string($_REQUEST["id"])) {
    $profil = $dao->profil_erhalten(htmlspecialchars($_REQUEST["id"]));
} else {
    header("location: index.php");
}
if (isset($profil) and is_array($profil) and $profil !== [-1]) {
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
        <?php if (isset($_SESSION["id"]) and htmlspecialchars($id) == htmlspecialchars($_SESSION["id"])) : ?>
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

            <button type="submit">Speichern</button>

            <?php else: ?>
                <h2>Willkommen auf dem Profil!</h2>
                <h3>Nutzername</h3>
                <p><?php echo $nutzername ?></p>
                <h3>Beschreibung</h3>
                <p><?php echo $beschreibung ?></p>
                <h3>Geschlecht</h3>
                <p><?php echo $geschlecht ?></p>
                <h3>Vollständiger Name</h3>
                <p><?php echo $vollstaendigerName ?></p>
                <h3>Adresse</h3>
                <p><?php echo $adresse ?></p>
                <h3>Sprache</h3>
                <p><?php echo $sprache ?></p>
                <h3>Geburtsdatum</h3>
                <p><?php echo $geburtsdatum ?></p>
                <h3>Registrierungsdatum</h3>
                <p><?php echo $registrierungsdatum ?></p>
            <?php endif ?>

    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>