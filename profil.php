<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Mein Profil";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

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
            <span><a href="gemaelde.php"><img src="images/1.jpg" alt="Profil" width="150"></a></span>
            <span><a href="gemaelde.php"><img src="images/0.jpg" alt="Profil" width="150"></a></span>
        </div>
        <div class="profilsammlungen">
            <p>Meine Sammlungen</p>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>