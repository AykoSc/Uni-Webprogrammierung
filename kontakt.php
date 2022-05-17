<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
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
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" required>
            <label for="kommentar">Kommentar:</label>
            <textarea id="kommentar" name="kommentar" cols="40" rows="5" maxlength="1000" wrap="soft"
                      placeholder="Neuen Kommentar schreiben..." required></textarea>
            <a href="index.php">Abbrechen</a>
            <button type="submit">Senden</button>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>