<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

$selektiert = '';
if (isset($_GET["suche"]) && is_string($_GET["suche"]) && isset($_GET["filter"]) && is_string($_GET["filter"])) {
    $ausstellung = $dao->ausstellung_erhalten($_GET["suche"], $_GET["filter"]);
    $selektiert = $_GET["filter"];
} else {
    $ausstellung = $dao->ausstellung_erhalten("", "beliebteste");
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
        request.open("GET", "suche.php?herkunft=ausstellung&suche=" + suche);
        request.send();
    }
</script>
<script src="js/filteranwenden.js" async></script>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Ausstellung</h1>

    <h3>Hier findest du alle Gemälde</h3>

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
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo 'images/' . htmlspecialchars($reihe[0] . "." . $reihe[10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe1 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo 'images/' . htmlspecialchars($reihe[0] . "." . $reihe[10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe2 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo 'images/' . htmlspecialchars($reihe[0] . "." . $reihe[10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>

        <div class="spalte">
            <?php foreach ($reihe3 as $reihe): ?>
                <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                    <img src="<?php echo 'images/' . htmlspecialchars($reihe[0] . "." . $reihe[10]) ?>"
                         alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>