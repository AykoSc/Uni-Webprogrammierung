<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_GET["id"]) and is_string($_GET["id"])) {
    $sammlung = $dao->sammlung_erhalten(htmlspecialchars($_GET["id"]));
} else {
    header("location: index.php");
}
if (isset($sammlung) and is_array($sammlung) and $sammlung !== [-1]) {
    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    $id = $sammlung[0];
    $anbieter = $dao->profil_erhalten($sammlung[1]); //$sammlung[1] ist anbieterID
    $alle_gemaelde = array();
    foreach ($sammlung[2] as $gemaeldeID) { //$sammlung[2] sind gemaeldeIDs
        $alle_gemaelde[] = $dao->gemaelde_erhalten(htmlspecialchars($gemaeldeID));
    }
    $titel = $sammlung[3];
    $beschreibung = $sammlung[4];
    $bewertung = $sammlung[5];
    $hochladedatum = $sammlung[6];
    $aufrufe = $sammlung[7];
} else {
    header("location: index.php");
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Sammlung";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Sammlung</h1>
    <h2><?php echo $titel ?></h2>

    <?php foreach ($alle_gemaelde as $gemaelde): ?>
        <h3><?php echo $gemaelde[2] ?></h3>
        <a href="gemaelde.php?id=<?php echo htmlspecialchars($gemaelde[0]) ?>">
            <img class="presentation" src="<?php echo "images/" . htmlspecialchars($gemaelde[0]) . "." . htmlspecialchars($gemaelde[10]) ?>"
                 alt="<?php echo htmlspecialchars($gemaelde[2]) ?>">
        </a>
    <?php endforeach; ?>

    <h2>Infos zur Sammlung</h2>
    <h3>Beschreibung</h3>
    <p><?php echo $beschreibung ?></p>
    <h3>Bewertung</h3>
    <p><?php echo $bewertung ?></p>
    <h3>Hochladedatum</h3>
    <p><?php echo $hochladedatum ?></p>
    <h3>Aufrufe</h3>
    <p><?php echo $aufrufe ?></p>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>