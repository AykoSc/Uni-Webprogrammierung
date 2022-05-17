<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
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
            <form>
                <input type="text" placeholder="Suche..." name="suche">
                <button><i>Suche</i></button>
            </form>
        </div>
        <div>
            <select name="Filter" size="1">
                <option value="relevance" selected>Beliebteste</option>
                <option value="date">Datum</option>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="column">
            <a href="gemaelde.php"> <img src="images/christ_blessing_1937.1.2.b.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/narcissus_in_a_blue_and_white_vase_2017.12.1.jpg" alt="Stockblume">
            </a>
            <a href="gemaelde.php"> <img src="images/christ_blessing_1937.1.2.b.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/enthroned_madonna_and_child_1949.7.1.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/narcissus_in_a_blue_and_white_vase_2017.12.1.jpg" alt="Stockblume">
            </a>
            <a href="gemaelde.php"> <img src="images/christ_blessing_1937.1.2.b.jpg" alt="Stockblume"> </a>
        </div>

        <div class="column">
            <a href="gemaelde.php"> <img src="images/narcissus_in_a_blue_and_white_vase_2017.12.1.jpg" alt="Stockblume">
            </a>
            <a href="gemaelde.php"> <img src="images/christ_blessing_1937.1.2.b.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/christ_blessing_1937.1.2.b.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/enthroned_madonna_and_child_1949.7.1.jpg" alt="Stockblume"> </a>
            <a href="gemaelde.php"> <img src="images/enthroned_madonna_and_child_1949.7.1.jpg" alt="Stockblume"> </a>
        </div>

        <div class="column">
            <img src="images/stockblume_3.jpg" style="width:100%">
            <img src="images/stockblume_2.jpg" style="width:100%">
            <img src="images/stockblume_1.jpg" style="width:100%">
            <img src="images/stockblume_2.jpg" style="width:100%">
        </div>

        <div class="column">
            <img src="images/stockblume_2.jpg" style="width:100%">
            <img src="images/stockblume_2.jpg" style="width:100%">
            <img src="images/stockblume_1.jpg" style="width:100%">
        </div>

    </div>


</main>

<?php include 'php/footer.php'; ?>

</body>

</html>