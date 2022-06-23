<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["suche"]) and is_string($_GET["suche"])
    and isset($_GET["filter"]) and is_string($_GET["filter"])) {
    $sammlungen = $dao->sammlungen_erhalten(htmlspecialchars($_GET["suche"]), htmlspecialchars($_GET["filter"]));
} else {
    $sammlungen = $dao->sammlungen_erhalten("", "");
}

//erste GemaeldeID als Vorschaubild der jeweiligen Sammlung nehmen
foreach ($sammlungen as $sammlung_reihe) {
    foreach ($sammlung_reihe as $sammlung) {
        if (isset($sammlung[2][0])) { //vllt int und nicht string
            $vorschaubild = $dao->gemaelde_erhalten(htmlspecialchars($sammlung[2][0]));
            $sammlung[2] = $vorschaubild;
        }
    }
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
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img
                            alt="<?php echo htmlspecialchars($reihe[3]) ?>"
                            src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                    >
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img
                            alt="<?php echo htmlspecialchars($reihe[3]) ?>"
                            src="<?php echo htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                    >
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img
                            alt="<?php echo htmlspecialchars($reihe[3]) ?>"
                            src="<?php echo htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                    >
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img
                            alt="<?php echo htmlspecialchars($reihe[3]) ?>"
                            src="<?php echo htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                    >
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>