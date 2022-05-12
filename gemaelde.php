<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Gemälde</title>
</head>


<body>

    <?php include 'php/header.php'; ?>

    <main>

        <h1>Titel</h1>

        <section>
            <div>
                <img alt="Blume" src="images/stockblume_2.jpg" width="150">
            </div>
            <div>Beschreibung</div>
            <h2> Kommentarbereich </h2>
            <form>
                <div>
                    <label for="kommentar">Kommentar:</label>
                    <textarea id="kommentar" name="kommentar" cols="40" rows="5" maxlength="1000" wrap="soft" placeholder="Neuen Kommentar schreiben..." required></textarea>
                    <input type="submit" value="Kommentieren">
                </div>
            </form>
            <div>
                <p>Kommentar</p>
            </div>
            <div>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                </p>
            </div>
        </section>

    </main>

    <?php include 'php/footer.php'; ?>

</body>

</html>