<!DOCTYPE html>
<html lang="de">

<?php
$name = "Anmeldung";
include "php/head.php";
?>

<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Anmeldung</h1>

    <div class="usermanagement">
        <h3>Hier kannst du dich anmelden</h3>
        <form>
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required>
            <label for="password">Passwort:</label>
            <input type="password" id="password" name="password" maxlength="100" placeholder="Passwort eingeben"
                   required>
            <hr>
            <button type="submit">Anmelden</button>
            <p><a href="index.php">Noch kein Account? Zur Registrierung</a></p>
            <p><a href="index.php">Zurück zur Startseite</a></p>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>