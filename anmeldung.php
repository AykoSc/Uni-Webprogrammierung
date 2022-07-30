<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["Email"]) && is_string($_GET["Email"]) && isset($_GET["Verifizierungscode"]) && is_string($_GET["Verifizierungscode"])) {
    $erfolgreich_bestaetigt = $dao->registrieren_bestaetigen($_GET["Email"], $_GET["Verifizierungscode"]);
    if ($erfolgreich_bestaetigt) {
        $erfolgreich = "Erfolgreich bestätigt. Sie können sich nun einloggen.";
    } else {
        $fehlermeldung = "Der Verifizierungsprozess schlug fehl, da die Daten falsch oder veraltet waren. Bitte kontaktieren Sie einen Administrator, versuchen Sie es erneut mit dem Verifizierungscode in der E-Mail oder versuchen Sie sich erneut zu registrieren.";
    }
}

if (isset($_POST["email"]) && is_string($_POST["email"]) && isset($_POST["passwort"]) && is_string($_POST["passwort"])) {
    $email = $_POST["email"];
    $passwort = $_POST["passwort"];

    $anmeldung = $dao->anmelden($email, $passwort);
    if (!empty($anmeldung) && $anmeldung[0] != -1) {
        $_SESSION["id"] = $anmeldung[0];
        $_SESSION["token"] = $anmeldung[1];
        header("location: index.php?anmelden=1");
    } else {
        $fehlermeldung = "Anmeldung fehlgeschlagen";
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
    <?php if (isset($fehlermeldung) && is_string($fehlermeldung)): ?>
        <p class="nachricht fehler"><?php echo htmlspecialchars($fehlermeldung) ?></p>
    <?php endif ?>
    <?php if (isset($erfolgreich) && is_string($erfolgreich)): ?>
        <p class="nachricht"><?php echo htmlspecialchars($erfolgreich) ?></p>
    <?php endif ?>

    <h1>Anmeldung</h1>

    <div class="usermanagement">
        <h3>Hier kannst du dich anmelden</h3>
        <form method="post" action="anmeldung.php">
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required
                <?php echo (isset($_POST["email"]) && is_string($_POST["email"])) ? 'value=' . htmlspecialchars($_POST["email"]) : '' ?>>
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