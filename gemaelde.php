<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

if (isset($_GET["id"]) and is_string($_GET["id"])) {
    $kommentare = $user->kommentar_erhalten(htmlentities($_GET["id"]));
    $gemaelde = $user->gemaelde_erhalten(htmlentities($_GET["id"]));
} else {
    header("location: index.php");
}
$id = $gemaelde[0];
$nutzer = $gemaelde[1];
$titel = htmlentities($gemaelde[2]);
$kuenstler = htmlentities($gemaelde[3]);
$beschreibung = htmlentities($gemaelde[4]);
$erstellungsdatum = htmlentities($gemaelde[5]);
$ort = htmlentities($gemaelde[6]);
$bewertung = htmlentities($gemaelde[7]);
$hochladedatum = htmlentities($gemaelde[8]);
$aufrufe = htmlentities($gemaelde[9]);

/*
if (isset($_SESSION["id"]) and isset($_POST["kommentar"]) && is_string($_POST["kommentar"])){
    $user->kommentar_anlegen(htmlspecialchars($_POST["kommentar"]), "3", $_SESSION["id"]);
}

if (isset($_SESSION["id"]) and isset($_POST["like"]) and is_string($_POST["like"])){
    $user->kommentar_liken($_SESSION["id"], htmlspecialchars($_POST["like"]));
}

if (isset($_SESSION["id"]) and isset($_POST["delete"]) and is_string($_POST["delete"])){
    $user->kommentar_entfernen($_SESSION["id"], htmlspecialchars($_POST["delete"]));
}

$comments = $user->kommentar_erhalten("3");
$gemaelde = $user->gemaelde_getByID("3");
*/
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
                <form method="post" action="gemaelde.php">
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
                        <a href="profil.php?<?php echo htmlentities($kommentar[2]) ?>"><?php //TODO Name vom Verfasser als String bekommen ?></a>
                        <span><?php echo htmlentities($kommentar[5]) ?></span>
                    </div>
                    <a class="avatar" href="profil.php?<?php echo htmlentities($kommentar[2]) ?>">
                        <img src="images/start.jpg" width="35" alt="Profil-Avatar"/>
                    </a>
                    <p>
                        <?php echo htmlentities($kommentar[4]); ?>
                    </p>
                    <a class="likes">
                        <form method="post" action="gemaelde.php">
                            <label for="like" class="invisible">Like</label>
                            <input type="hidden" name="like" value="<?php echo htmlentities($kommentar[3]); ?>">
                            <input type="image" alt="thumbsUp" src="images/thumbsUp.png" width="20">
                        </form>

                        <?php echo htmlentities($kommentar[3]) ?>
                    </a>
                    <?php if (isset($_SESSION["id"]) and $kommentar[2] == $_SESSION["id"]) : ?>
                        <a class="delete">
                            <form method="post" action="gemaelde.php">
                                <label for="like" class="invisible">Like</label>
                                <input type="hidden" name="delete" value="<?php echo htmlentities($kommentar[0]); ?>">
                                <input type="image" alt="trashbin" src="images/trashbin.png" width="20">
                            </form>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

    </section>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>