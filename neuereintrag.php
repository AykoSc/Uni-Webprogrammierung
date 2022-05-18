<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Neuer Eintrag</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Neuer Eintrag</h1>

    <div class="usermanagement">
        <h3>Hier kannst du einen neuen Eintrag erstellen</h3>
        <form>
            <hr>
            <label for="file">Gemälde auswählen:</label>
            <input type="file" id="file" name="file" style="display:none;" required>
            <p><label for="file">Hier klicken um Datei hochzuladen!</label></p>
            <label for="beschreibung">Beschreibung:</label>
            <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft"
                      placeholder="Fügen Sie eine Beschreibung ein..."></textarea>
            <label for="titel">Titel:</label>
            <input type="text" id="titel" name="titel" maxlength="100" required>
            <label for="artist">Künstler:</label>
            <input type="text" id="artist" name="artist" maxlength="100" required>
            <label for="date">Datum der Erstellung:</label>
            <input type="date" id="date" name="date">
            <label for="location">Ort:</label>
            <input type="text" id="location" name="location" maxlength="100">
            <hr>
            <button type="submit">Fertigstellen</button>
            <a href="index.php">Abbrechen</a>
        </form>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>