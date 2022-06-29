<?php if (!isset($abs_path)) include_once '../path.php'; ?>

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>

    <?php if (!empty($name) and ($name == 'Ausstellung' or $name == 'Sammlungen')) : ?>
        <link rel="stylesheet" href="css/ausstellung.css"/>
    <?php endif ?>

    <?php if (!empty($name) and ($name == 'Gemälde' or $name == 'Sammlung')) : ?>
        <link rel="stylesheet" href="css/gemaeldeundsammlung.css"/>
    <?php endif ?>

    <title>Online-Ausstellung<?php echo (!empty($name)) ? ' - ' . $name : '' ?></title>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <?php //TODO raus wenn geht?>

</head>