<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Kontakt</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Kontakt</h1>

    <div class="usermanagement">
        <h3>Hier kannst du Kontakt aufnehmen</h3>
        <form>
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" required>
            <label for="kommentar">Kommentar:</label>
            <textarea id="kommentar" name="kommentar" cols="40" rows="10" maxlength="1000" wrap="soft"
                      placeholder="Neuen Kommentar schreiben..." required></textarea>
            <hr>
            <button type="submit">Senden</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>