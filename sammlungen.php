<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = NutzerDAODummyImpl::getInstance();

if (isset($_GET["suche"]) and is_string($_GET["suche"])) {
    $sammlungen = $user->sammlungen_erhalten(htmlspecialchars($_GET["suche"]), "Beliebteste");
} else {
    $sammlungen = $user->sammlungen_erhalten("", "Beliebteste");
}

$reihe0 = $sammlungen[0];
$reihe1 = $sammlungen[1];
$reihe2 = $sammlungen[2];
$reihe3 = $sammlungen[3];
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Sammlungen";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Sammlungen</h1>

    <h3>Hier findest du alle Sammlungen</h3>

    <form class="suche">
        <label for="suche" class="invisible">Suche</label>
        <input type="text" placeholder="Suche..." name="suche" id="suche">
        <button>
            <img src="images/suche.svg" alt="suchen" height="16" width="16">
        </button>
    </form>
    <div class="filter">
        <label for="filter">Filtern nach:</label>
        <select id="filter" name="Filter">
            <option value="relevance" selected>Beliebteste</option>
            <option value="date">Datum</option>
        </select>
    </div>

    <div class="reihe">
        <div class="spalte">
            <?php foreach ($reihe0 as $reihe): ?>
                <a href="sammlung.php"><img alt="<?php echo htmlspecialchars($reihe[3]) ?>" src="images/1.jpg"> </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="sammlung.php"><img alt="<?php echo htmlspecialchars($reihe[3]) ?>" src="images/1.jpg"> </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="sammlung.php"><img alt="<?php echo htmlspecialchars($reihe[3]) ?>" src="images/1.jpg"> </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="sammlung.php"><img alt="<?php echo htmlspecialchars($reihe[3]) ?>" src="images/1.jpg"> </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>