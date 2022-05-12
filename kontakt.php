<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Kontakt</title>
</head>


<body>

    <?php include 'php/header.php'; ?>

    <main>

        <h1>Kontakt</h1>

        <section>
            <h3>Hier kannst du Kontakt aufnehmen</h3>
            <form>
                <div>
                    <label for="email">E-Mail:</label>
                    <div>
                        <input type="email" id="email" name="email" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="kommentar">Kommentar:</label>
                    <div>
                        <textarea id="kommentar" name="kommentar" cols="40" rows="5" maxlength="1000" wrap="soft" placeholder="Neuen Kommentar schreiben..." required></textarea>
                    </div>
                </div>
                <div>
                    <a href="index.php">Abbrechen</a>
                    <button type="submit">Senden</button>
                </div>
            </form>
        </section>

        <p>
            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
        </p>

    </main>

    <?php include 'php/footer.php'; ?>

</body>

</html>