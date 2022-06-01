<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Gemälde";
include $abs_path . '/php/head.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<?php $user = new NutzerDAODummyImpl();

//TODO gemaelde_id noch fest
if (isset($_SESSION["id"]) and isset($_POST["kommentar"]) && is_string($_POST["kommentar"])){
    ($user -> kommentar_schreiben(htmlspecialchars($_POST["kommentar"]), "3", $_SESSION["id"]));
}

if (isset($_SESSION["id"]) and isset($_POST["like"]) and is_string($_POST["like"])){
    $user -> kommentar_liken($_SESSION["id"], htmlspecialchars($_POST["like"]));
}

print_r($_POST);
if (isset($_SESSION["id"]) and isset($_POST["delete"]) and is_string($_POST["delete"])){
    $user -> kommentar_entfernen($_SESSION["id"], htmlspecialchars($_POST["delete"]));
}

$comments = $user->kommentar_getAll("3");
?>


<main>

    <h1>Beispiel Gemälde</h1>
    <img class="presentation" alt="Stockbild" src="images/start.jpg">

    <div class="description">
        <h2>Über das Gemälde</h2>
        <p>
            Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
            labore et dolore magna aliquyam erat, sed diam voluptua.
        </p>
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
            <?php foreach($comments as $comment): ?>
                <li class="comment">
                    <div class="info">
                        <a href="profil.php"><?php echo htmlspecialchars($user->nutzer_getNameByID($comment["author"])) ?></a>
                        <span> jetzt gerade </span>
                    </div>
                    <a class="avatar" href="profil.php">
                        <img src="images/start.jpg" width="35" alt="Profile Avatar"/>
                    </a>
                    <p>
                        <?php echo htmlspecialchars($comment["text"]); ?>
                    </p>
                    <a class="likes">
                        <form method="post" action="gemaelde.php">
                            <label for="like" class="invisible">Like</label>
                            <input type="hidden" name="like" value="<?php echo htmlspecialchars($comment["kommentar_id"]); ?>">
                            <input type="image" alt="thumbsUp" src="images/thumbsUp.png" width="20">
                        </form>

                        <?php echo htmlspecialchars($comment["likes"]) ?>
                    </a>
                    <?php if(isset($_SESSION["id"]) and $comment["author"] == $_SESSION["id"]) : ?>
                        <a class="delete">
                            <form method="post" action="gemaelde.php">
                                <label for="like" class="invisible">Like</label>
                                <input type="hidden" name="delete" value="<?php echo htmlspecialchars($comment["kommentar_id"]); ?>">
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