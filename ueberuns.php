<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Über Uns";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Über Uns</h1>

    <div class="ueberuns">
        <p>Wir möchten einen Platz für Künstler und Kunstinteressierte schaffen, um Werke zu präsentieren und zu
            bewerten sowie Sammlungen von Werken diversere Künstler zu erstellen. Zudem möchten wir unseren Nutzern die
            Möglichkeit bieten in der Kommentarsektion über die ausgestellten Werke und Sammlungen zu diskutieren.
        </p>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>