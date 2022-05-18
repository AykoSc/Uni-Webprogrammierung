<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/ausstellung.css"/>
    <title>Online-Ausstellung - Sammlungen</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Sammlungen</h1>

    <div class="sammlungen">
        <h3>Hier findest du alle Sammlungen</h3>
        <div>
            <form class="suche" action="sammlungen.php">
                <label for="suche" style="display:none;">Suche</label>
                <input type="text" placeholder="Suche..." name="suche" id="suche">
                <button><img src="images/suche.svg" alt="search" height="16" width="16"></button>
            </form>
        </div>
        <div class="filter">
            <label>
                <select name="Filter" size="1">
                    <option value="relevance" selected>Beliebteste</option>
                    <option value="date">Datum</option>
                </select>
            </label>
        </div>
        <div class="reihe">
            <div class="spalte">
                <a href="sammlung.php"><img alt="Sammlung" src="images/picture2.jpg"> </a>
            </div>
        </div>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>