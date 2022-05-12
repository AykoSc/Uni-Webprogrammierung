<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Neuer Eintrag</title>
</head>


<body>

    <?php include 'php/header.php'; ?>

    <main>

        <h1>Neuer Eintrag</h1>

        <section>
            <h3>Hier kannst du einen neuen Eintrag erstellen</h3>
            <p> Gemälde auswählen</p>
            <form>
                <input type="file" name="img">
                <input type="submit">
            </form>
            <div>
                Gemälde Vorschau hier
            </div>
            <form>
                <div>
                    <label for="beschreibung">Beschreibung:</label>
                    <textarea id="beschreibung" name="beschreibung" cols="40" rows="5" maxlength="1000" wrap="soft" placeholder="Fügen Sie eine Beschreibung ein">
                     </textarea>
                </div>
                <h3>Zusätzliche Daten angeben</h3>
                <div>
                    <label for="titel">Titel:</label>
                    <div>
                        <input type="text" id="titel" name="titel" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="artist">Künstler:</label>
                    <div>
                        <input type="text" id="artist" name="artist" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="location">Datum der Erstellung:</label>
                    <div>
                        <input type="date" id="date" name="date">
                    </div>
                </div>
                <div>
                    <label for="location">Ort:</label>
                    <div>
                        <input type="text" id="location" name="location" maxlength="100">
                    </div>
                </div>
                <div>
                    <a href="index.php">Abbrechen</a>
                    <button type="submit">Fertigstellen</button>
                </div>
            </form>
        </section>

    </main>

    <?php include 'php/footer.php'; ?>

</body>

</html>