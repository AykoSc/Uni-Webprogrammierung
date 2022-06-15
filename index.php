<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$user = NutzerDAODBImpl::getInstance();


if (isset($_GET["abmelden"]) and is_string($_GET["abmelden"]) and $_GET["abmelden"] === "1") {
    if (isset($_SESSION["id"]) and is_int($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
        $abmeldung = $user->abmelden(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]));
    }
    session_unset();
    session_destroy();
}

if (isset($_GET["anmelden"]) and is_string($_GET["anmelden"]) and $_GET["anmelden"] === "1" and isset($_SESSION["id"]) and is_int($_SESSION["id"]) and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
    $anmeldung = true;
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Start";
include $abs_path . '/php/head.php';
?>

<body>
<?php include $abs_path . '/php/header.php'; ?>


<main>
    <?php if (isset($abmeldung) and is_bool($abmeldung) and $abmeldung): ?>
        <p class="nachricht">Abmeldung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($anmeldung) and is_bool($anmeldung) and $anmeldung): ?>
        <p class="nachricht">Anmeldung erfolgreich</p>
    <?php endif ?>

    <h1>Hauptseite</h1>

    <img class="presentation" src="images/start.jpg" alt="Start">


    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
        labore et
        dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
        Stet
        clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
        amet,
        consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
        erat, sed
        diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no
        sea
        takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
</main>


<?php include $abs_path . '/php/footer.php'; ?>
</body>

</html>