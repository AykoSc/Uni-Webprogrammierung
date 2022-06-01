<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

if (isset($_GET["id"]) and is_string($_GET["id"])) {
    $kommentare = $user->kommentar_erhalten(htmlspecialchars($_GET["id"]));
    $gemaelde = $user->gemaelde_erhalten(htmlspecialchars($_GET["id"]));
} else {
    header("location: index.php");
}
if (isset($gemaelde) and is_array($gemaelde) and $gemaelde !== [-1]) {
    $id = $gemaelde[0];
    $nutzer = $gemaelde[1];
    $titel = htmlspecialchars($gemaelde[2]);
    $kuenstler = htmlspecialchars($gemaelde[3]);
    $beschreibung = htmlspecialchars($gemaelde[4]);
    $erstellungsdatum = htmlspecialchars($gemaelde[5]);
    $ort = htmlspecialchars($gemaelde[6]);
    $bewertung = htmlspecialchars($gemaelde[7]);
    $hochladedatum = htmlspecialchars($gemaelde[8]);
    $aufrufe = htmlspecialchars($gemaelde[9]);
} else {
    header("location: index.php");
}

if (isset($_SESSION["id"]) and isset($_POST["kommentar"]) && is_string($_POST["kommentar"])){
    $result = $user->kommentar_anlegen(htmlspecialchars($_POST["kommentar"]), htmlspecialchars($_GET["id"]), htmlspecialchars($_SESSION["id"]));
}

if (isset($_SESSION["id"]) and isset($_POST["like"]) and is_string($_POST["like"])){
    $result = $user->kommentar_liken(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_POST["like"]));
}

if (isset($_SESSION["id"]) and isset($_POST["delete"]) and is_string($_POST["delete"])){
    $result = $user->kommentar_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_POST["delete"]));
}

?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Gemälde";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1><?php echo $titel ?></h1>
    <img class="presentation" alt="<?php echo $titel ?>" src="images/<?php echo $id ?>.jpg">

    <div class="description">
        <h2>Über das Gemälde</h2>
        <h3>Beschreibung</h3>
        <p><?php echo $beschreibung ?></p>
        <h3>KünstlerIn</h3>
        <p><?php echo $kuenstler ?></p>
        <h3>Erstellungsdatum</h3>
        <p><?php echo $erstellungsdatum ?></p>
        <h3>Ort</h3>
        <p><?php echo $ort ?></p>
        <h3>Bewertung</h3>
        <p><?php echo $bewertung ?>/10</p>
        <h3>Hochladedatum</h3>
        <p><?php echo $hochladedatum ?></p>
        <h3>Aufrufe</h3>
        <p><?php echo $aufrufe ?></p>
    </div>

    <section id="comment_section">
        <div class="align_container">
            <div class="container">
                <h2>Einen Kommentar verfassen</h2>
                <form method="post" action="gemaelde.php?id=<?php echo $_GET["id"] ?>">
                    <label for="kommentar" class="invisible">Kommentar</label>
                    <textarea id="kommentar" name="kommentar" maxlength="1000"
                              placeholder="Neuen Kommentar schreiben..."
                              required></textarea>
                    <input type="submit" value="Kommentar">
                </form>
            </div>
        </div>
        <ul class="comment-section">
            <?php foreach ($kommentare as $kommentar): ?>
                <li class="comment">
                    <div class="info">
                        <a href="profil.php?id=<?php echo htmlspecialchars($kommentar[2]) ?>">
                            <span><?php echo htmlspecialchars($kommentar[5]) ?></span></a>
                    </div>
                    <a class="avatar" href="profil.php?id=<?php echo htmlspecialchars($kommentar[2]) ?>">
                        <img src="images/start.jpg" width="35" alt="Profil-Avatar"/>
                    </a>
                    <p>
                        <?php echo htmlspecialchars($kommentar[4]); ?>
                    </p>
                    <div class="likes">
                        <form method="post" action="gemaelde.php?id=<?php echo $_GET["id"] ?>">
                            <input type="hidden" name="like" value="<?php echo htmlspecialchars($kommentar[0]) ?>">
                            <input type="image" alt="thumbsUp" src="images/thumbsUp.png" width="20">
                        </form>

                        <?php echo htmlspecialchars($kommentar[3]) ?>
                    </div>
                    <?php if (isset($_SESSION["id"]) and $kommentar[2] == $_SESSION["id"]) : ?>
                        <div class="delete">
                            <form method="post" action="gemaelde.php?id=<?php echo $_GET["id"] ?>">
                                <input type="hidden" name="delete"
                                       value="<?php echo htmlspecialchars($kommentar[0]) ?>">
                                <input type="image" alt="trashbin" src="images/trashbin.png" width="20">
                            </form>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </section>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>