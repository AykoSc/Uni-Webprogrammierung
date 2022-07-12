<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
?>

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

    <p>
        Wir möchten einen Platz für Künstler und Kunstinteressierte schaffen, um Werke zu präsentieren, zu
        bewerten sowie Sammlungen von Werken diverser Künstler zu erstellen. Zudem möchten wir unseren Nutzern die
        Möglichkeit bieten, in der Kommentarsektion über die ausgestellten Werke und Sammlungen zu diskutieren.
    </p>
    <h2>Wo Sie uns finden können</h2>
    <iframe class="presentation presentation-height"
            src="https://www.openstreetmap.org/export/embed.html?bbox=8.173259496688845%2C53.14384239502062%2C8.187421560287477%2C53.14978171374889&amp;layer=mapnik"></iframe>
    <noscript>Mit JavaScript wäre hier eine Karte der Universität Oldenburg Campus Haarentor ersichtlich</noscript>
    <br/>
    <small>
        <a href="https://www.openstreetmap.org/#map=17/53.14681/8.18034">Größere Karte anzeigen</a>
    </small>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>