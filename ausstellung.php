<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["suche"]) and is_string($_GET["suche"])
    and isset($_GET["filter"]) and is_string($_GET["filter"])) {
    $ausstellung = $dao->ausstellung_erhalten(htmlspecialchars($_GET["suche"]), htmlspecialchars($_GET["filter"]));
} else {
    $ausstellung = $dao->ausstellung_erhalten("", "");
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

    <form>
        <div class="suche">
            <label for="suche" class="invisible">Suche</label>
                <input type="text" placeholder="Suche..." name="suche" id="suche">
            <button>
                <img src="images/suche.svg" alt="suchen" height="16" width="16">
            </button>
        </div>

        <label for="filter">Filtern nach:</label>
        <select id="filter" name="filter">
            <option value="beliebteste" selected>Beliebteste</option>
            <option value="datum">Datum</option>
        </select>
    </form>

    <!-- Filter überschreibt Suche, deswegen kommt Filter in Suche rein
    <form class="filter">

        <button type="submit">Filtern</button>
    </form>
    -->

    <div class="reihe">
        <div class="spalte">
            <?php foreach ($reihe0 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="images/<?php echo htmlspecialchars($reihe[0]) . "." . $reihe[10] ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="images/<?php echo htmlspecialchars($reihe[0]) . "." . $reihe[10] ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="images/<?php echo htmlspecialchars($reihe[0]) . "." . $reihe[10] ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="images/<?php echo htmlspecialchars($reihe[0]) . "." . $reihe[10] ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>