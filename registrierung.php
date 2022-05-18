<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Registrierung</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Registrierung</h1>

    <div class="usermanagement">
        <h3>Bitte gib die folgende Informationen an, um dich zu registrieren.</h3>
        <form>
            <hr>
            <label for="username">Benutzername</label>
            <input type="text" id="username" name="username" maxlength="100" placeholder="Name eingeben" required>
            <label for="email">E-Mail</label>
            <input type="email" id="email" name="email" maxlength="100" placeholder="E-Mail eingeben" required>
            <label for="password">Passwort</label>
            <input type="password" id="password" name="password" minlength="8" maxlength="100"
                   placeholder="Passwort eingeben" required>
            <label for="password_repeat">Passwort wiederholen</label>
            <input type="password" id="password_repeat" name="password_repeat" minlength="8" maxlength="100"
                   placeholder="Passwort wiederholen" required>
            <hr>
            <button type="submit">Registrieren</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>