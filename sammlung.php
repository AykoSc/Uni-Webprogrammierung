<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Sammlung";
include $abs_path . 'php/head.php';
?>

<body>

<?php include $abs_path . 'php/header.php'; ?>

<main>

    <h1>Sammlung</h1>

    <div class="sammlung">
        <h3>Test Sammlung</h3>
        <a href="gemaelde.php"> <img alt="Profil" src="images/picture2.jpg" width="150"> </a>
    </div>

</main>

<?php include $abs_path . 'php/footer.php'; ?>

</body>

</html>