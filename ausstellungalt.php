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

    <div class="container">
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_3.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine Sonnenblume! </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_3.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine Sonnenblume! </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_3.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine Sonnenblume! </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_1.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine schöne Blume. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_2.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Wow ist das schön. </span>
                </div>
            </a>
        </div>
        <div class="item">
            <a href="gemaelde.php" class="item__link">
                <img alt="Profil" src="images/stockblume_3.jpg" class="item__image">
                <div class="item__overlay">
                    <span> Eine Sonnenblume! </span>
                </div>
            </a>
        </div>


    </div>


</main>

<?php include 'php/footer.php'; ?>

</body>

</html>