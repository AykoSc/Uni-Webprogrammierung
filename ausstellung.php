<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/ausstellung.css"/>
    <title>Online-Ausstellung - Ausstellung</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Ausstellung</h1>


    <h3>Hier findest du alle Gemälde</h3>
    <div>
        <div>
            <form class="suche" action="ausstellung.php">
                <input type="text" placeholder="Suche..." name="suche">
                <button><img src="images/suche.svg" alt="search" height="16" width="16"></button>
            </form>
        </div>
        <div class="filter">
            <select name="Filter" size="1">
                <option value="relevance" selected>Beliebteste</option>
                <option value="date">Datum</option>
            </select>
        </div>
    </div>

    <div class="reihe">
        <div class="spalte">
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture2.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture2.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
        </div>

        <div class="spalte">
            <a href="gemaelde.php"> <img src="images/picture2.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
        </div>

        <div class="spalte">
            <a href="gemaelde.php"> <img src="images/picture2.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
        </div>

        <div class="spalte">
            <a href="gemaelde.php"> <img src="images/picture2.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture1.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
            <a href="gemaelde.php"> <img src="images/picture3.jpg" alt="Stockbild"> </a>
        </div>

    </div>


</main>

<?php include 'php/footer.php'; ?>

</body>

</html>