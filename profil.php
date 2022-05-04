<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Mein Profil</title>
</head>


<body>

    <?php include 'php/header.php'; ?>

    <main>

        <h1>Mein Profil</h1>

        <section>
            <h3>Willkommen auf dem Profil!</h3>
            <div>
                <img src="images/profilbild.png" alt="Profil" width="200">
            </div>
            <div>Name</div>
            <div>Herkunft</div>
            <div>Geburtstag</div>
            <div>Über mich</div>
            <div>
                <p>Ausgestellte Gemälde</p>
                <span>
                    <a href="gemaelde.php">
                        <img src="images/stockblume_1.jpg" alt="Profil" width="150">
                    </a>
                </span>
                <span>
                    <a href="gemaelde.php">
                        <img src="images/stockblume_2.jpg" alt="Profil" width="150">
                    </a>
                </span>
            </div>
            <div>

            </div>
        </section>

    </main>

    <?php include 'php/footer.php'; ?>

</body>

</html>