<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

if (isset($_GET["suche"]) and is_string($_GET["suche"])) {
    $ausstellung = $user->ausstellung_erhalten(htmlspecialchars($_GET["suche"]), "Beliebteste");
} else {
    $ausstellung = $user->ausstellung_erhalten("", "Beliebteste");
}

$reihe0 = $ausstellung[0];
$reihe1 = $ausstellung[1];
$reihe2 = $ausstellung[2];
$reihe3 = $ausstellung[3];
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Ausstellung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Ausstellung</h1>

    <h3>Hier findest du alle Gemälde</h3>

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
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>"> <img
                            src="images/<?php echo htmlspecialchars($reihe[0]) ?>.jpg"
                            alt="<?php echo htmlspecialchars($reihe[2]) ?>"> </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>"> <img
                            src="images/<?php echo htmlspecialchars($reihe[0]) ?>.jpg"
                            alt="<?php echo htmlspecialchars($reihe[2]) ?>"> </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>"> <img
                            src="images/<?php echo htmlspecialchars($reihe[0]) ?>.jpg"
                            alt="<?php echo htmlspecialchars($reihe[2]) ?>"> </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>"> <img
                            src="images/<?php echo htmlspecialchars($reihe[0]) ?>.jpg"
                            alt="<?php echo htmlspecialchars($reihe[2]) ?>"> </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>