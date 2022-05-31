<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';

include_once $abs_path . "/controller/NutzerDAODummyImpl.php";

$user = new NutzerDAODummyImpl();

if (isset($_POST["nutzername"]) and is_string($_POST["nutzername"])
    and isset($_POST["email"]) and is_string($_POST["email"])
    and isset($_POST["passwort"]) and is_string($_POST["passwort"])
    and isset($_POST["passwort_wiederholen"]) and is_string($_POST["passwort_wiederholen"])) {
    if ($_POST["passwort"] === $_POST["passwort_wiederholen"]
        and $_POST["passwort"] !== $_POST["nutzername"]
        and $_POST["passwort"] !== $_POST["email"]) {
        $result = $user->registrieren(htmlentities($_POST["nutzername"]), htmlentities($_POST["email"]), htmlentities($_POST["passwort"]));
        if ($result) {
            header("location: anmeldung.php");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Registrierung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Registrierung</h1>

    <div class="usermanagement">
        <h3>Bitte gib die folgende Informationen an, um dich zu registrieren.</h3>
        <form method="post" action="registrierung.php">
            <hr>
            <label for="nutzername">Benutzername</label>
            <input type="text" id="nutzername" name="nutzername" maxlength="100" placeholder="Name eingeben" required
                <?php echo (isset($_POST["nutzername"]) and is_string($_POST["nutzername"])) ? 'value=' . $_POST["nutzername"] : '' ?>>
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required
                <?php echo (isset($_POST["email"]) and is_string($_POST["email"])) ? 'value=' . $_POST["email"] : '' ?>>
            <label for="passwort">Passwort</label>
            <input type="password" id="passwort" name="passwort" minlength="8" maxlength="100"
                   placeholder="Passwort eingeben" required>
            <label for="passwort_wiederholen">Passwort wiederholen</label>
            <input type="password" id="passwort_wiederholen" name="passwort_wiederholen" minlength="8" maxlength="100"
                   placeholder="Passwort wiederholen" required>
            <hr>
            <button type="submit">Registrieren</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>