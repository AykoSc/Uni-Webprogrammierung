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
        Wir möchten einen Platz für Künstler und Kunstinteressierte schaffen, um Werke zu präsentieren und zu
        bewerten sowie Sammlungen von Werken diversere Künstler zu erstellen. Zudem möchten wir unseren Nutzern die
        Möglichkeit bieten in der Kommentarsektion über die ausgestellten Werke und Sammlungen zu diskutieren.
    </p>
    <h2>Wo Sie uns finden können</h2>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2392.9644089075323!2d8.181704813819259!3d53.14673329156484!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47b6dfca0f089dff%3A0x283f06b42b139285!2sUniversity%20of%20Oldenburg%20Haarentor%20Campus!5e0!3m2!1sen!2sde!4v1657553852179!5m2!1sen!2sde"
            width="1000" height="500" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>