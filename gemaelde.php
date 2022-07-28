<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Kommentar anlegen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["kommentar"]) && is_string($_POST["kommentar"]) && isset($_GET["id"]) && is_string($_GET["id"])) {
    $angelegt = $dao->kommentar_anlegen($_SESSION["id"], $_SESSION["token"], $_POST["kommentar"], $_GET["id"]);
}

// Kommentar liken
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["like"]) && is_string($_POST["like"])) {
    $geliked = $dao->kommentar_liken($_SESSION["id"], $_SESSION["token"], $_POST["like"]);
}

// Kommentar entfernen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["delete"]) && is_string($_POST["delete"])) {
    $entfernung = $dao->kommentar_entfernen($_SESSION["id"], $_SESSION["token"], $_POST["delete"]);
}

// Eintrag bewerten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_GET["id"]) && is_string($_GET["id"]) && isset($_POST["bewertung"]) && is_string($_POST["bewertung"])) {
    $bewertet = $dao->gemaelde_bewerten($_SESSION["id"], $_SESSION["token"], $_GET["id"], $_POST["bewertung"]);
}

// Eintrag bearbeiten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_GET["id"]) && is_string($_GET["id"]) && isset($_POST['beschreibung']) && is_string($_POST['beschreibung']) && isset($_POST['erstellungsdatum']) && is_string($_POST['erstellungsdatum']) && isset($_POST['ort']) && is_string($_POST['ort'])) {
    $editierung = $dao->gemaelde_editieren($_SESSION["id"], $_SESSION["token"],
        $_GET["id"], $_POST['beschreibung'],
        $_POST['erstellungsdatum'], $_POST['ort']);
    if (!$editierung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
}

// Eintrag laden
if (isset($_GET["id"]) && is_string($_GET["id"])) {
    if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"])) {
        $kommentare = $dao->kommentare_erhalten($_REQUEST["id"], $_SESSION["id"], $_SESSION["token"]);
        $eigene_bewertung = $dao->eigene_gemaelde_bewertung_erhalten($_SESSION["id"], $_GET["id"]);
    } else {
        $kommentare = $dao->kommentare_erhalten($_REQUEST["id"], "", "");
    }
    $gemaelde = $dao->gemaelde_erhalten($_REQUEST["id"]);
} else {
    header("location: index.php?fehler=Gemälde");
}

// Eintrag löschen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $loeschung = $dao->gemaelde_entfernen($_SESSION["id"], $_SESSION["token"], $_GET["id"]);
    if ($loeschung) {
        header("location: index.php?entfernt=Gemälde");
    } else {
        $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && htmlspecialchars($_POST["loeschen"]) === "nichtbestaetigt") {
    $fehlermeldung = "Um dieses Gemälde zu löschen müssen Sie den Bestätigungshaken setzen.";
}

if (isset($gemaelde) && is_array($gemaelde) && $gemaelde !== [-1]) {
    $id = $gemaelde[0];
    $nutzer = $gemaelde[1];
    $titel = $gemaelde[2];
    $kuenstler = $gemaelde[3];
    $beschreibung = $gemaelde[4];
    $erstellungsdatum = $gemaelde[5];
    $ort = $gemaelde[6];
    $bewertung = $gemaelde[7];
    $hochladedatum = $gemaelde[8];
    $aufrufe = $gemaelde[9];
    $dateityp = $gemaelde[10];
} else {
    header("location: index.php?fehler=Gemälde");
}


?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Gemälde";
include $abs_path . '/php/head.php';
?>

<body>
<script src="js/jquery.min.js"></script>
<script src="js/kommentaraktionen.js"></script>
<script src="js/bewertungaktion.js"></script>
<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung)): ?>
        <p class="nachricht fehler">Es gab einen Fehler: <?php echo htmlspecialchars($fehlermeldung) ?></p>
    <?php endif ?>
    <?php if (isset($angelegt) && is_bool($angelegt) && $angelegt): ?>
        <p class="nachricht">Kommentar erfolgreich angelegt</p>
    <?php endif ?>
    <?php if (isset($angelegt) && is_bool($angelegt) && !$angelegt): ?>
        <p class="nachricht fehler">Kommentar Erstellung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($geliked) && is_bool($geliked) && $geliked): ?>
        <p class="nachricht">Kommentar erfolgreich bewertet</p>
    <?php endif ?>
    <?php if (isset($geliked) && is_bool($geliked) && !$geliked): ?>
        <p class="nachricht fehler">Kommentar liken fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($entfernung) && is_bool($entfernung) && $entfernung): ?>
        <p class="nachricht">Kommentar erfolgreich gelöscht</p>
    <?php endif ?>
    <?php if (isset($entfernung) && is_bool($entfernung) && !$entfernung): ?>
        <p class="nachricht fehler">Kommentar Löschung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($bewertet) && is_bool($bewertet) && $bewertet): ?>
        <p class="nachricht">Gemälde erfolgreich bewertet</p>
    <?php endif ?>
    <?php if (isset($bewertet) && is_bool($bewertet) && !$bewertet): ?>
        <p class="nachricht fehler">Gemälde Bewertung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($editierung) && is_bool($editierung) && $editierung): ?>
        <p class="nachricht">Gemälde erfolgreich editiert</p>
    <?php endif ?>
    <?php if (isset($editierung) && is_bool($editierung) && !$editierung): ?>
        <p class="nachricht fehler">Gemälde Editieren fehlgeschlagen</p>
    <?php endif ?>

    <h1><?php echo htmlspecialchars($titel) ?></h1>
    <img class="presentation" alt="<?php echo htmlspecialchars($titel) ?>"
         src="images/<?php echo htmlspecialchars($id . "." . $dateityp) ?>">
    <div class="align_container">
        <div class="description">
            <?php if (isset($_SESSION["id"]) && $nutzer == htmlspecialchars($_SESSION["id"])) : ?>

                <form method="post">
                    <h2> Über das Gemaelde </h2>
                    <label for="beschreibung" class="invisible">Beschreibung</label>
                    <textarea cols="70" rows="10" id="beschreibung"
                              name="beschreibung"><?php echo htmlspecialchars($beschreibung) ?></textarea>
                    <div class="grid">
                        <div class="item">
                            <h3>KünstlerIn</h3>
                            <p><?php echo htmlspecialchars($kuenstler) ?></p>
                        </div>
                        <div class="item">
                            <h3>Erstellungsdatum</h3>
                            <label for="erstellungsdatum" class="invisible">Erstellungsdatum</label>
                            <input id="erstellungsdatum" type="date" name="erstellungsdatum"
                                   value="<?php echo date("Y-m-d", strtotime($erstellungsdatum)); ?>"/>
                        </div>
                        <div class="item">
                            <h3>Ort</h3>
                            <label for="ort" class="invisible">Ort</label>
                            <input id="ort" type="text" name="ort" value="<?php echo htmlspecialchars($ort) ?>">
                        </div>
                        <div class="item">
                            <p>Gesamtbewertung:</p>
                            <?php for ($i = 1; $i <= $bewertung; $i++) { ?>
                                <img class="icons" src="images/stern_gelb.svg" alt="bewertunggesamt"/>
                            <?php } ?>
                            <?php for ($i = $bewertung + 1; $i <= 5; $i++) { ?>
                                <img class="icons" src="images/stern_schwarz.svg" alt="bewertunggesamt"/>
                            <?php } ?>
                        </div>
                        <div class="item">
                            <h3>Hochladedatum</h3>
                            <p><?php echo date("d.m.Y", strtotime($hochladedatum)); ?></p>
                        </div>
                        <div class="item">
                            <h3>Aufrufe</h3>
                            <p><?php echo htmlspecialchars($aufrufe) ?></p>
                        </div>
                    </div>
                    <input type="submit" name="Submit" value="Speichern"/>
                </form>

                <form method="post">
                    <h3>Gemälde löschen?</h3>
                    <input type="hidden" name="loeschen" value="nichtbestaetigt"/>
                    <input id="loeschen" type="checkbox" name="loeschen" value="loeschbestaetigung"/>
                    <label for="loeschen">Löschen bestätigen</label>
                    <input type="submit" value="Löschen"/>
                </form>
            <?php else: ?>

            <h2>Über das Gemälde</h2>
            <p><?php echo htmlspecialchars($beschreibung) ?></p>

            <details class="extended_description">

                <summary data-open="Weniger anzeigen" data-close="Mehr anzeigen"></summary>

                <div class="grid">
                    <div class="item">
                        <h3>KünstlerIn</h3>
                        <p><?php echo htmlspecialchars($kuenstler) ?></p>
                    </div>
                    <div class="item">
                        <h3>Erstellungsdatum</h3>
                        <p><?php echo date("d.m.Y", strtotime($erstellungsdatum)); ?></p>
                    </div>
                    <div class="item">
                        <h3>Ort</h3>
                        <p><?php echo htmlspecialchars($ort) ?></p>
                    </div>
                    <div id="bewertung" class="item">
                        <h3>Bewertung</h3>
                        <p>Gesamtbewertung:</p>
                        <?php for ($i = 1; $i <= $bewertung; $i++) { ?>
                            <img class="icons" src="images/stern_gelb.svg" alt="bewertunggesamt"/>
                        <?php } ?>
                        <?php for ($i = $bewertung + 1; $i <= 5; $i++) { ?>
                            <img class="icons" src="images/stern_schwarz.svg" alt="bewertunggesamt"/>
                        <?php } ?>

                        <?php if (isset($_SESSION["id"])): ?>
                            <p>Deine Bewertung:</p>
                            <form method="post">
                                <?php for ($i = 1; $i <= $eigene_bewertung; $i++) { ?>
                                    <button class="bewertung" name="bewertung" value="<?php echo $i ?>">
                                        <img class="icons" src="images/stern_gelb.svg" alt="eigenebewertung"/>
                                    </button>
                                <?php } ?>
                                <?php for ($i = $eigene_bewertung + 1; $i <= 5; $i++) { ?>
                                    <button class="bewertung" name="bewertung" value="<?php echo $i ?>">
                                        <img class="icons" src="images/stern_schwarz.svg" alt="eigenebewertung"/>
                                    </button>
                                <?php } ?>
                            </form>
                        <?php endif; ?>
                    </div>
                    <div class="item">
                        <h3>Hochladedatum</h3>
                        <p><?php echo date("d.m.Y", strtotime($hochladedatum)); ?></p>
                    </div>
                    <div class="item">
                        <h3>Aufrufe</h3>
                        <p><?php echo htmlspecialchars($aufrufe) ?></p>
                    </div>
                </div>

            </details>


        </div>
        <?php endif ?>

    </div>
    <div id="comment_section">
        <div class="align_container">
            <h2> Kommentarbereich</h2>
            <?php if (isset($_SESSION["id"])): ?>
                <div class="container">
                    <form method="post">
                        <label for="kommentar" class="invisible">Kommentar</label>
                        <textarea id="kommentar" name="kommentar" maxlength="1000"
                                  placeholder="Neuen Kommentar schreiben..."
                                  required></textarea>
                        <input type="submit" value="Kommentar">
                    </form>
                </div>
            <?php endif ?>
        </div>
        <ul class="comment-section" id="comment-section">
            <?php foreach ($kommentare as $kommentar): ?>
                <li class="comment">
                    <div class="info">
                        <a href="profil.php?id=<?php echo urlencode($kommentar[2]) ?>">
                            <span><?php echo date("d.m.Y", strtotime($kommentar[5])) ?></span></a>
                    </div>
                    <a class="avatar" href="profil.php?id=<?php echo urlencode($kommentar[2]) ?>">
                        <img src="images/start.jpg" width="35" alt="Profil-Avatar"/>
                    </a>
                    <p>
                        <?php echo nl2br(htmlspecialchars($kommentar[4])); ?>
                    </p>

                    <div class="likes">

                        <?php if (isset($_SESSION["id"])) : ?>
                            <form method="post">
                                <input type="hidden" name="like" value="<?php echo htmlspecialchars($kommentar[0]) ?>">
                                <input type="image" alt="thumbsup"
                                       <?php if ($kommentar[6] == 1): ?>src="images/daumenhoch_farbig.png"
                                    <?php else: ?> src="images/daumenhoch_grau.png" <?php endif ?>
                                       width="20">
                            </form>
                        <?php else: ?>
                            <img src="images/daumenhoch_farbig.png" width="20" alt="thumbsup"/>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($kommentar[3]) ?>
                    </div>
                    <?php if (isset($_SESSION["id"]) && $kommentar[2] == $_SESSION["id"]) : ?>
                        <div class="delete">
                            <form method="post">
                                <input type="hidden" name="delete"
                                       value="<?php echo htmlspecialchars($kommentar[0]) ?>">
                                <input type="image" alt="trashbin" src="images/mulleimer.png" width="20">
                            </form>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>