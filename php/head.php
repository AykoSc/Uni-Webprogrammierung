<?php if (!isset($abs_path)) include_once '../path.php'; ?>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <?php
    if (!empty($name)) {
        if ($name == 'Ausstellung' or $name == 'Sammlungen') {
            echo '<link rel="stylesheet" href="css/ausstellung.css"/>';
        }
        if ($name == 'Gemälde') {
            echo '<link rel="stylesheet" href="css/gemaelde.css"/>';
        }
        echo '<title>Online-Ausstellung - ' . $name . '</title>';
    } else {
        echo '<title>Online-Ausstellung</title>';
    }
    ?>
</head>