<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

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
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>