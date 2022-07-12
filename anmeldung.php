<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["Email"]) and is_string($_GET["Email"])
    and isset($_GET["Verifizierungscode"]) and is_string($_GET["Verifizierungscode"])) {
    $erfolgreich_bestaetigt = $dao->registrieren_bestaetigen(htmlspecialchars($_GET["Email"]), htmlspecialchars($_GET["Verifizierungscode"]));
    if ($erfolgreich_bestaetigt) {
        $erfolgreich = "Erfolgreich bestätigt. Sie können sich nun einloggen.";
    } else {
        $fehlermeldung = "Der Verifizierungsprozess schlug fehl, da die Daten falsch waren. Bitte kontaktieren Sie einen Administrator.";
    }
}

if (isset($_POST["email"]) and is_string($_POST["email"]) and isset($_POST["passwort"]) and is_string($_POST["passwort"])) {
    $email = htmlspecialchars($_POST["email"]);
    $passwort = htmlspecialchars($_POST["passwort"]);

    $anmeldung = $dao->anmelden($email, $passwort);
    if (!empty($anmeldung) and $anmeldung[0] != -1) {
        $_SESSION["id"] = $anmeldung[0];
        $_SESSION["token"] = $anmeldung[1];
        header("location: index.php?anmelden=1");
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
    <?php if (isset($anmeldung) and is_array($anmeldung) and $anmeldung[0] === -1): ?>
        <p class="nachricht fehler">Anmeldung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($_GET["registrieren"]) and is_string($_GET["registrieren"]) and $_GET["registrieren"] === "1"): ?>
        <p class="nachricht">Registrierung erfolgreich</p>
    <?php endif ?>
    <?php if (isset($fehlermeldung) and is_string($fehlermeldung)): ?>
        <p class="nachricht fehler"><?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($erfolgreich) and is_string($erfolgreich)): ?>
        <p class="nachricht"><?php echo $erfolgreich ?></p>
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