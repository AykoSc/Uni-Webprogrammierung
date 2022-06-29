<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_SESSION["id"]) and isset($_SESSION["token"]) and isset($_POST["kommentar"]) and is_string($_POST["kommentar"]) and isset($_GET["id"]) and is_string($_GET["id"])) {
    $angelegt = $dao->kommentar_anlegen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_POST["kommentar"]), htmlspecialchars($_GET["id"]));
}

if (isset($_SESSION["id"]) and isset($_SESSION["token"]) and isset($_POST["like"]) and is_string($_POST["like"])) {
    $geliked = $dao->kommentar_liken(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_POST["like"]));
}

if (isset($_SESSION["id"]) and isset($_SESSION["token"]) and isset($_POST["delete"]) and is_string($_POST["delete"])) {
    $entfernung = $dao->kommentar_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_POST["delete"]));
}

if (isset($_SESSION["id"]) and isset($_SESSION["token"]) and isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "loeschbestaetigung") {
    $loeschung = $dao->gemaelde_entfernen(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]), htmlspecialchars($_GET["id"]));
    if (!$loeschung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
}

if (isset($_SESSION["id"]) and isset($_POST["loeschen"]) and is_string($_POST["loeschen"]) and htmlspecialchars($_POST["loeschen"]) === "nichtbestaetigt") {
    $fehlermeldung = "Um dieses Gemälde zu löschen müssen Sie den Bestätigungshaken setzen.";
}

//Eintrag bearbeiten
if (isset($_SESSION["id"]) and is_string($_SESSION["id"]) and
    isset($_SESSION["token"]) and is_string($_SESSION["token"]) and
    isset($_GET["id"]) and is_string($_GET["id"]) and
    isset($_POST['beschreibung']) and is_string($_POST['beschreibung']) and
    isset($_POST['erstellungsdatum']) and is_string($_POST['erstellungsdatum']) and
    isset($_POST['ort']) and is_string($_POST['ort'])) {
    $gemaelde = $dao->gemaelde_editieren(htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]),
        htmlspecialchars($_GET["id"]), htmlspecialchars($_POST['beschreibung']),
        htmlspecialchars($_POST['erstellungsdatum']), htmlspecialchars($_POST['ort']));
}

if (isset($_GET["id"]) and is_string($_GET["id"])
    and isset($_SESSION["id"]) and is_string($_SESSION["id"])
    and isset($_SESSION["token"]) and is_string($_SESSION["token"])) {
    $kommentare = $dao->kommentare_erhalten(htmlspecialchars($_GET["id"]),htmlspecialchars($_SESSION["id"]), htmlspecialchars($_SESSION["token"]));
    $gemaelde = $dao->gemaelde_erhalten(htmlspecialchars($_GET["id"]));
}else if(isset($_GET["id"]) and is_string($_GET["id"])){
    $kommentare = $dao->kommentare_erhalten(htmlspecialchars($_GET["id"]),"", "");
    $gemaelde = $dao->gemaelde_erhalten(htmlspecialchars($_GET["id"]));
    }
else {
    header("location: index.php");
}

if (isset($gemaelde) and is_array($gemaelde) and $gemaelde !== [-1]) {
    $id = htmlspecialchars($gemaelde[0]);
    $nutzer = htmlspecialchars($gemaelde[1]);
    $titel = htmlspecialchars($gemaelde[2]);
    $kuenstler = htmlspecialchars($gemaelde[3]);
    $beschreibung = htmlspecialchars($gemaelde[4]);
    $erstellungsdatum = htmlspecialchars($gemaelde[5]);
    $ort = htmlspecialchars($gemaelde[6]);
    $bewertung = htmlspecialchars($gemaelde[7]);
    $hochladedatum = htmlspecialchars($gemaelde[8]);
    $aufrufe = htmlspecialchars($gemaelde[9]);
    $dateityp = htmlspecialchars($gemaelde[10]);
} else {
    header("location: index.php");
}


?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Gemälde";
include $abs_path . '/php/head.php';
?>

<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
<script src="js/kommentaraktionen.js" ></script>


<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung)): ?>
        <p class="nachricht fehler">Es gab einen Fehler: <?php echo $fehlermeldung ?></p>
    <?php endif ?>
    <?php if (isset($angelegt) and is_bool($angelegt) and $angelegt): ?>
        <p class="nachricht">Kommentar erfolgreich angelegt</p>
    <?php endif ?>
    <?php if (isset($angelegt) and is_bool($angelegt) and !$angelegt): ?>
        <p class="nachricht fehler">Kommentar Erstellung fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($geliked) and is_bool($geliked) and $geliked): ?>
        <p class="nachricht">Kommentar erfolgreich bewertet</p>
    <?php endif ?>
    <?php if (isset($geliked) and is_bool($geliked) and !$geliked): ?>
        <p class="nachricht fehler">Kommentar liken fehlgeschlagen</p>
    <?php endif ?>
    <?php if (isset($entfernung) and is_bool($entfernung) and $entfernung): ?>
        <p class="nachricht">Kommentar erfolgreich gelöscht</p>
    <?php endif ?>
    <?php if (isset($entfernung) and is_bool($entfernung) and !$entfernung): ?>
        <p class="nachricht fehler">Kommentar Löschung fehlgeschlagen</p>
    <?php endif ?>

    <h1><?php echo $titel ?></h1>
    <img class="presentation" alt="<?php echo $titel ?>" src="images/<?php echo $id . "." . $dateityp ?>">
    <div class="align_container">
        <div class="description">
            <?php if (isset($_SESSION["id"]) and $nutzer == htmlspecialchars($_SESSION["id"])) : ?>

                <form method="post">
                    <h2> Über das Gemaelde </h2>
                    <label for="beschreibung" class="invisible">Beschreibung</label>
                    <textarea cols="70" rows="10" id="beschreibung" name="beschreibung"><?php echo $beschreibung ?></textarea>
                    <div class="grid">
                        <div class="item">
                            <h3>KünstlerIn</h3>
                            <p><?php echo $kuenstler ?></p>
                        </div>
                        <div class="item">
                            <h3>Erstellungsdatum</h3>
                            <label for="erstellungsdatum" class="invisible">Erstellungsdatum</label>
                            <input id="erstellungsdatum" type="date" name="erstellungsdatum"
                                   value="<?php echo $erstellungsdatum ?>"/>
                        </div>
                        <div class="item">
                            <h3>Ort</h3>
                            <label for="ort" class="invisible">Ort</label>
                            <input id="ort" type="text" name="ort" value="<?php echo $ort ?>">
                        </div>
                        <div class="item">
                            <h3>Bewertung</h3>
                            <p><?php echo $bewertung ?>/10</p>
                        </div>
                        <div class="item">
                            <h3>Hochladedatum</h3>
                            <p><?php echo $hochladedatum ?></p>
                        </div>
                        <div class="item">
                            <h3>Aufrufe</h3>
                            <p><?php echo $aufrufe ?></p>
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
            <p><?php echo $beschreibung ?></p>

            <details class="extended_description">

                <summary data-open="Weniger anzeigen" data-close="Mehr anzeigen"></summary>

                <div class="grid">
                    <div class="item">
                        <h3>KünstlerIn</h3>
                        <p><?php echo $kuenstler ?></p>
                    </div>
                    <div class="item">
                        <h3>Erstellungsdatum</h3>
                        <p><?php echo date("d.m.Y", strtotime($erstellungsdatum)); ?></p>
                    </div>
                    <div class="item">
                        <h3>Ort</h3>
                        <p><?php echo $ort ?></p>
                    </div>
                    <div class="item">
                        <h3>Bewertung</h3>
                        <p><?php echo $bewertung ?>/10</p>
                    </div>
                    <div class="item">
                        <h3>Hochladedatum</h3>
                        <p><?php echo $hochladedatum ?></p>
                    </div>
                    <div class="item">
                        <h3>Aufrufe</h3>
                        <p><?php echo $aufrufe ?></p>
                    </div>
                </div>

            </details>


        </div>
        <?php endif ?>

    </div>
    <section id="comment_section">
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

                        <?php if (isset($_SESSION["id"])) /*and $kommentar[2] != $_SESSION["id"])//TODO zum testen draußen*/ : ?>
                            <form method="post">
                                <input type="hidden" name="like" value="<?php echo htmlspecialchars($kommentar[0]) ?>">
                                <input id="thumbsup" type="image" alt="thumbsup" <?php if($kommentar[6] == 1): ?>src="images/thumbsup.png"
                                    <?php else:?> src="images/thumbsupgrey.png" <?php endif ?>
                                       width="20">
                                <script>console.log(document.readyState); </script>
                            </form>
                        <?php else: ?>
                            <img src="images/thumbsup.png" width="20" alt="thumbsup"/>
                        <?php endif; ?>
                        <?php echo htmlspecialchars($kommentar[3]) ?>
                    </div>
                    <?php if (isset($_SESSION["id"]) and $kommentar[2] == $_SESSION["id"]) : ?>
                        <div class="delete">
                            <form method="post">
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