<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODbImpl.php';
$user = NutzerDAODbImpl::getInstance();

/* Funktioniert nicht

$wrongFilterLinkFormat = false;
$filter = "";
if (isset($_GET["filter"]) and is_string($_GET["filter"])) {
    if ($_GET["filter"] === "") {
        $wrongFilterLinkFormat = true;
    } else {
        $filter = htmlspecialchars($_GET["filter"]);
    }
}

$wrongSucheLinkFormat = false;
$suche = "";
if (isset($_GET["suche"]) and is_string($_GET["suche"])) {
    if ($_GET["suche"] === "") {
        $wrongSucheLinkFormat = true;
    } else {
        $suche = htmlspecialchars($_GET["suche"]);
    }
}

if ($wrongFilterLinkFormat or $wrongSucheLinkFormat) {
    //"?filter=" . htmlspecialchars($filter)
    //"?suche=" . htmlspecialchars($suche)
    header("location: ausstellung.php"
        . ($wrongSucheLinkFormat ? ("?suche=" . $suche) : "") . "&" .
        . ($wrongFilterLinkFormat ? ("?filter=" . $filter) : ""));
}
*/

if (isset($_GET["suche"]) and is_string($_GET["suche"])
    and isset($_GET["filter"]) and is_string($_GET["filter"])) {
    $ausstellung = $user->ausstellung_erhalten(htmlspecialchars($_GET["suche"]), htmlspecialchars($_GET["filter"]));
} else {
    //TODO gegebenenfalls anpassen, aber sonst bringt der check mit isset nichts
    $ausstellung = $user->ausstellung_erhalten(null, null);
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
            <?php if (true): ?>
                <input type="text" placeholder="Suche..." name="suche" id="suche">
            <?php endif ?>
            <button>
                <img src="images/suche.svg" alt="suchen" height="16" width="16">
            </button>
        </div>

        <label for="filter">Filtern nach:</label>
        <select id="filter" name="filter">
            <option value="" selected>-</option>
            <option value="relevance">Beliebteste</option>
            <option value="date">Datum</option>
            +
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