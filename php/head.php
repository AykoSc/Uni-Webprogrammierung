<?php if (!isset($abs_path)) include_once '../path.php'; ?>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/x-icon" href="images/logo.svg">
    <link rel="stylesheet" href="css/main.css"/>

    <?php if (!empty($name) && ($name == 'Ausstellung' || $name == 'Sammlungen')) : ?>
        <link rel="stylesheet" href="css/ausstellung.css"/>
    <?php endif ?>

    <?php if (!empty($name) && ($name == 'Gemälde' || $name == 'Sammlung')) : ?>
        <link rel="stylesheet" href="css/gemaeldeundsammlung.css"/>
    <?php endif ?>

    <title>Online-Ausstellung<?php echo (!empty($name)) ? ' - ' . $name : '' ?></title>

</head>