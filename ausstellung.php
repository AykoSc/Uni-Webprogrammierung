<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Ausstellung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Ausstellung</h1>

    <h3>Hier findest du alle Gemälde</h3>

    <form class="suche">
        <label for="suche" class="invisible">Suche</label>
        <input type="text" placeholder="Suche..." name="suche" id="suche">
        <button>
            <img src="images/suche.svg" alt="suchen" height="16" width="16">
        </button>
    </form>
    <div class="filter">
        <label for="filter">Filtern nach:</label>
        <select id="filter" name="Filter">
            <option value="relevance" selected>Beliebteste</option>
            <option value="date">Datum</option>
        </select>
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

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>