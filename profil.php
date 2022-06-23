<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$user = NutzerDAODBImpl::getInstance();


//Profil bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_POST['nutzername']) and is_string($_POST['nutzername']) and
    isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
    isset($_POST['geschlecht']) and is_string($_POST['geschlecht']) and
    isset($_POST['vollständigerName']) and is_string($_POST['vollständigerName']) and
    isset($_POST['adresse']) and is_string($_POST['adresse']) and
    isset($_POST['sprache']) and is_string($_POST['sprache'])and
    isset($_POST['geburtsdatum']) and is_string($_POST['geburtsdatum'])) {
    $profil = $user->profil_editieren(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($_POST['nutzername']), htmlspecialchars($_POST['beschreibung']),
        htmlspecialchars($_POST['geschlecht']), htmlspecialchars($_POST['vollständigerName']),
        htmlspecialchars($_POST['adresse']),htmlspecialchars($_POST['sprache']),htmlspecialchars($_POST['geburtsdatum']));
}


if (isset($_GET["id"]) and is_string($_GET["id"])) {
    $profil = $user->profil_erhalten(htmlspecialchars($_GET["id"]));
} else {
    header("location: index.php");
}
if (isset($profil) and is_array($profil) and $profil !== [-1]) {
    $id = $profil[0];
    $nutzername = $profil[1];
    $beschreibung = $profil[2];
    $geschlecht = $profil[3];
    $vollstaendigerName = $profil[4];
    $adresse = $profil[5];
    $sprache = $profil[6];
    $geburtsdatum = $profil[7];
    $registrierungsdatum = $profil[8];
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

    <h1>Mein Profil</h1>

    <div class="profil">

        <?php if (isset($_SESSION["id"]) and htmlspecialchars($id) == htmlspecialchars($_SESSION["id"])) : ?>
        <h2>Willkommen auf deinem Profil!</h2>
        <form method="post">
            <div>
                <h2>Nutzername</h2>
                <label for="nutzername" class="invisible">Nutzername</label>
                <input type="text" name="nutzername"
                       value="<?php echo htmlspecialchars($nutzername) ?>"/>
            </div>

            <div>
                <h2> Beschreibung </h2>
                <label for="beschreibung" class="invisible">Beschreibung</label>
                <textarea cols="70" rows="10" name="beschreibung"><?php echo $beschreibung ?></textarea>
            </div>

            <div>
                <h2>Geschlecht</h2>
                <label for="nutzername" class="invisible">Geschlecht</label>
                <input type="text" name="geschlecht"
                       value="<?php echo htmlspecialchars($geschlecht) ?>"/>
            </div>

            <div>
                <h2>Vollständiger Name</h2>
                <label for="vollständigerName" class="invisible">Vollständiger Name</label>
                <input type="text" name="vollständigerName"
                       value="<?php echo htmlspecialchars($vollstaendigerName) ?>"/>
            </div>

            <div>
                <h2>Adresse</h2>
                <label for="adresse" class="invisible">Adresse</label>
                <input type="text" name="adresse"
                       value="<?php echo htmlspecialchars($adresse) ?>"/>
            </div>

            <div>
                <h2>Sprache</h2>
                <label for="sprache" class="invisible">Sprache</label>
                <input type="text" name="sprache"
                       value="<?php echo htmlspecialchars($sprache) ?>"/>
            </div>

            <div>
                <h2>Geburtsdatum</h2>
                <label for="geburtsdatum" class="invisible">Geburtsdatum</label>
                <input type="date" name="geburstdatum"
                       value="<?php echo htmlspecialchars($geburtsdatum) ?>"/>
            </div>

            <div>
                <h2>Registrierungsdatum</h2>
                <p><?php echo $registrierungsdatum ?></p>
            </div>
            <input type="submit" name="Submit" value="Speichern" />
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