<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Start";
include $abs_path . '/php/head.php';
?>

<body>
<?php include $abs_path . '/php/header.php'; ?>


<main>
    <h1>Hauptseite</h1>

    <img class="presentation" src="images/start.jpg" alt="Start">


    <p>
        Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
        labore et
        dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.
        Stet
        clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit
        amet,
        consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam
        erat, sed
        diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no
        sea
        takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
</main>


<?php include $abs_path . '/php/footer.php'; ?>
</body>

</html>