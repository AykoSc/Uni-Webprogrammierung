<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$user = NutzerDAODBImpl::getInstance();

if (isset($_POST["email"]) and is_string($_POST["email"]) and isset($_POST["passwort"]) and is_string($_POST["passwort"])) {
    $anmeldung = $user->anmelden(htmlspecialchars($_POST["email"]), htmlspecialchars($_POST["passwort"]));
    if (!empty($anmeldung) and $anmeldung[0] != -1) {
        $_SESSION["id"] = $anmeldung[0];
        $_SESSION["token"] = $anmeldung[1];
        header("location: index.php?anmelden=1");
    }
}

if (isset($_GET["registrieren"]) and is_string($_GET["registrieren"]) and $_GET["registrieren"] === "1") {
    $registrierung = true;
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
    <?php if (isset($anmeldung) and is_array($anmeldung) and $anmeldung[0] === -1): ?>
        <p class="nachricht fehler">Anmeldung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($registrierung) and is_bool($registrierung) and $registrierung): ?>
        <p class="nachricht">Registrierung erfolgreich</p>
    <?php endif ?>

    <h1>Anmeldung</h1>

    <div class="usermanagement">
        <h3>Hier kannst du dich anmelden</h3>
        <form method="post" action="anmeldung.php">
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required
                <?php echo (isset($_POST["email"]) and is_string($_POST["email"])) ? 'value=' . htmlspecialchars($_POST["email"]) : '' ?>>
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