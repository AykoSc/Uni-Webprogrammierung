<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';

include_once $abs_path . "/controller/NutzerDAODummyImpl.php";

$user = new NutzerDAODummyImpl();

if (isset($_POST["email"]) and is_string($_POST["email"]) and isset($_POST["passwort"]) and is_string($_POST["passwort"])) {
    $result = $user->anmelden(htmlentities($_POST["email"]), htmlentities($_POST["passwort"]));
    if ($result[0] != -1) {
        $_SESSION["id"] = $result[0];
        $_SESSION["token"] = $result[1];
        header("location: index.php");
    }
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Anmeldung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Anmeldung</h1>

    <div class="usermanagement">
        <h3>Hier kannst du dich anmelden</h3>
        <form method="post" action="anmeldung.php">
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required
                <?php echo (isset($_POST["email"]) and is_string($_POST["email"])) ? 'value=' . $_POST["email"] : '' ?>>
            <label for="passwort">Passwort:</label>
            <input type="password" id="passwort" name="passwort" maxlength="100" placeholder="Passwort eingeben"
                   required>
            <hr>
            <button type="submit">Anmelden</button>
            <p><a href="registrierung.php">Noch kein Account? Zur Registrierung</a></p>
            <p><a href="index.php">Zurück zur Startseite</a></p>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>