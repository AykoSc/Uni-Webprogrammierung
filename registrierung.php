<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_POST["nutzername"]) and is_string($_POST["nutzername"])
    and isset($_POST["email"]) and is_string($_POST["email"])
    and isset($_POST["passwort"]) and is_string($_POST["passwort"])
    and isset($_POST["passwort_wiederholen"]) and is_string($_POST["passwort_wiederholen"])
    and isset($_POST["akzeptiert"]) and is_string($_POST["akzeptiert"])) {
    $nutzername = htmlspecialchars($_POST["nutzername"]);
    $email = htmlspecialchars($_POST["email"]);
    $passwort = htmlspecialchars($_POST["passwort"]);
    $passwort_wiederholen = htmlspecialchars($_POST["passwort_wiederholen"]);
    $akzeptiert = htmlspecialchars($_POST["akzeptiert"]);

    if ($passwort !== $passwort_wiederholen) {
        $fehlermeldung = "Das Passwort wurde falsch wiederholt.";
    }
    if ($passwort === $nutzername) {
        $fehlermeldung = "Das Passwort darf nicht dem Nutzernamen entsprechen.";
    }
    if ($passwort === $email) {
        $fehlermeldung = "Das Passwort darf nicht der Email entsprechen.";
    }
    if ($akzeptiert === "nein") {
        $fehlermeldung = "Sie stimmen nicht der Datenschutzerklärung zu und bestätigen nicht das Einhalten der Nutzungsbedingungen.";
    }
    if (isset($fehlermeldung) and is_string($fehlermeldung)) {
        $registrierung = false;
    } else {
        $registrierung = $dao->registrieren($nutzername, $email, $passwort);
        if (!$registrierung) {
            $fehlermeldung = "Der Nutzername oder die Email wird bereits verwendet.";
        }
    }

    if ($registrierung) {
        header("location: anmeldung.php?registrieren=1");
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

<script>
    function suchvorschlaege(suche) {
        if (suche.length === 0) {
            document.getElementById('nutzernamevergeben').innerHTML = 'Benutzername';
            return;
        }
        const request = new XMLHttpRequest();
        request.onload = function() {
            if(this.responseText === '1') {
                document.getElementById('nutzernamevergeben').innerHTML = 'Benutzername (Verfügbar)';
            } else {
                document.getElementById('nutzernamevergeben').innerHTML = 'Benutzername (Bereits vergeben)';
            }
            return this.responseText;
        }
        request.open("GET", "suche.php?herkunft=registrierung&suche=" + suche);
        request.send();
    }
</script>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($registrierung) and is_bool($registrierung) and !$registrierung and isset($fehlermeldung) and is_string($fehlermeldung)): ?>
        <p class="nachricht fehler">Registrierung fehlgeschlagen: <?php echo $fehlermeldung ?></p>
    <?php endif ?>

    <h1>Registrierung</h1>

    <div class="usermanagement">
        <h3>Bitte gib die folgende Informationen an, um dich zu registrieren.</h3>
        <form method="post" action="registrierung.php">
            <hr>
            <label id="nutzernamevergeben" for="nutzername">Benutzername</label>
            <input type="text" id="nutzername" name="nutzername" maxlength="100" placeholder="Name eingeben" required onkeyup="suchvorschlaege(this.value)"
                <?php echo (isset($_POST["nutzername"]) and is_string($_POST["nutzername"])) ? 'value=' . htmlspecialchars($_POST["nutzername"]) : '' ?>>
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required
                <?php echo (isset($_POST["email"]) and is_string($_POST["email"])) ? 'value=' . htmlspecialchars($_POST["email"]) : '' ?>>
            <label for="passwort">Passwort</label>
            <input type="password" id="passwort" name="passwort" minlength="8" maxlength="100"
                   placeholder="Passwort eingeben" required>
            <label for="passwort_wiederholen">Passwort wiederholen</label>
            <input type="password" id="passwort_wiederholen" name="passwort_wiederholen" minlength="8" maxlength="100"
                   placeholder="Passwort wiederholen" required>
            <input type="hidden" name="akzeptiert" value="nein"/>
            <input id="akzeptiert" type="checkbox" name="akzeptiert" value="ja"/>
            <label for="akzeptiert">Ich stimme der Datenschutzerklärung zu und bestätige das Einhalten der
                Nutzungsbedingungen.</label>
            <hr>
            <button type="submit">Registrieren</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>