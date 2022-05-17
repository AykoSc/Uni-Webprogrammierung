<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Anmeldung</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Anmeldung</h1>

    <div class="usermanagement">
        <h3>Hier kannst du dich anmelden</h3>
        <form>
            <div>
                <label for="email">E-Mail:</label>
                <div>
                    <input type="email" id="email" name="email" maxlength="100" required>
                </div>
            </div>
            <div>
                <label for="password">Passwort:</label>
                <div>
                    <input type="password" id="password" name="password" maxlength="100" required>
                </div>
            </div>
            <div>
                <a href="index.php">Abbrechen</a>
                <button type="submit">Anmelden</button>
            </div>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>