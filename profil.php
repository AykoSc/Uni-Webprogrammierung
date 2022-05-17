<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Mein Profil</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Mein Profil</h1>

    <div class="profil">
        <h3>Willkommen auf dem Profil!</h3>
        <img src="images/profilbild.png" alt="Profil" width="200">
        <p>Name</p>
        <p>Herkunft</p>
        <p>Geburtstag</p>
        <p>Über mich</p>
        <div class="profilausstellung">
            <p>Ausgestellte Gemälde</p>
            <span><a href="gemaelde.php"><img src="images/stockblume_1.jpg" alt="Profil" width="150"></a></span>
            <span><a href="gemaelde.php"><img src="images/stockblume_2.jpg" alt="Profil" width="150"></a></span>
        </div>
        <div class="profilsammlungen">
            <p>Meine Sammlungen</p>
        </div>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>