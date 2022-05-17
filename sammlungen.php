<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css"/>
    <title>Online-Ausstellung - Sammlungen</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Sammlungen</h1>

    <div class="sammlungen">
        <h3>Hier findest du alle Sammlungen</h3>
        <div class="suche">
            <form>
                <input type="text" placeholder="Suche..." name="suche">
                <button><i>Suche</i></button>
            </form>
        </div>
        <div class="filter">
            <select name="Filter" size="1">
                <option value="relevance" selected>Beliebteste</option>
                <option value="date">Datum</option>
            </select>
        </div>
        <div>
            <a href="sammlung.php">
                <img alt="Sammlung" src="images/stockblume_1.jpg" width="150">
            </a>
        </div>
    </div>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>