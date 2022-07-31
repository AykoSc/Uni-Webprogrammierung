<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

$selektiert = '';
if (isset($_GET["suche"]) && is_string($_GET["suche"]) && isset($_GET["filter"]) && is_string($_GET["filter"])) {
    $sammlungen = $dao->sammlungen_erhalten($_GET["suche"], $_GET["filter"]);
    $selektiert = $_GET["filter"];
} else {
    $sammlungen = $dao->sammlungen_erhalten("", "beliebteste");
}

// Erstes Gemaelde als Vorschaubild der jeweiligen Sammlung nehmen
for ($i = 0; $i < sizeof($sammlungen); $i++) { //$sammlungen as $reihe
    for ($j = 0; $j < sizeof($sammlungen[$i]); $j++) { //$reihe as $sammlung
        if (isset($sammlungen[$i][$j][2][0]) && is_int($sammlungen[$i][$j][2][0])) {
            $vorschaubild = $dao->gemaelde_erhalten($sammlungen[$i][$j][2][0]);
            $sammlungen[$i][$j][2] = $vorschaubild;
        } else {
            $sammlungen[$i][$j][2][0] = "kein_vorschau_gemaelde";
            $sammlungen[$i][$j][2][10] = "png";
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

<script>
    function suchvorschlaege(suche) {
        if (suche.length === 0) {
            document.getElementById("suchvorschlag").innerHTML = "";
            document.getElementById("suchvorschlag").style.padding = "0px";
            document.getElementById("suchvorschlag").style.border = "0px";
            return;
        }
        const request = new XMLHttpRequest();
        request.onload = function () {
            document.getElementById("suchvorschlag").innerHTML = this.responseText;
            document.getElementById("suchvorschlag").style.padding = "10px";
            document.getElementById("suchvorschlag").style.border = "1px solid grey";
        }
        request.open("GET", "suche.php?herkunft=sammlungen&suche=" + suche);
        request.send();
    }
</script>
<script src="js/filteranwenden.js" async></script>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Sammlungen</h1>

    <h3>Hier findest du alle Sammlungen</h3>

    <form>
        <div class="suche">
            <label for="suche" class="invisible">Suche</label>
            <input autocomplete="off" class="suchfeld" type="text" placeholder="Suche..." name="suche" id="suche"
                   onkeyup="suchvorschlaege(this.value)"
                <?php echo (isset($_GET["suche"]) && is_string($_GET["suche"])) ? 'value="' . htmlspecialchars($_GET["suche"]) . '"' : '' ?>>
            <button id="suchenknopf">
                <img src="images/suche.svg" alt="suchen" height="16" width="16">
            </button>
        </div>
        <div id="suchvorschlag"></div>

        <label for="filter">Filtern nach:</label>
        <select id="filter" name="filter" class="filter">
            <option value="beliebteste" <?php echo ($selektiert !== 'datum') ? 'selected' : '' ?>>
                Beliebteste
            </option>
            <option value="datum" <?php echo ($selektiert === 'datum') ? 'selected' : '' ?>>
                Datum
            </option>
        </select>
    </form>

    <div class="reihe">
        <div class="spalte">
            <?php foreach ($reihe0 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[3]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[3]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[3]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[3]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>