<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Profil bearbeiten
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST['submit'])) {
    $sessionId = $_SESSION["id"];
    $sessionToken = $_SESSION["token"];
    $neuBeschreibung = (isset($_POST['beschreibung']) && is_string($_POST['beschreibung'])) ? $_POST['beschreibung'] : '';
    $neuGeschlecht = (isset($_POST['geschlecht']) && is_string($_POST['geschlecht'])) ? $_POST['geschlecht'] : '';
    $neuVollstaendigerName = (isset($_POST['vollstaendigerName']) && is_string($_POST['vollstaendigerName'])) ? $_POST['vollstaendigerName'] : '';
    $neuAdresse = (isset($_POST['adresse']) && is_string($_POST['adresse'])) ? $_POST['adresse'] : '';
    $neuSprache = (isset($_POST['sprache']) && is_string($_POST['sprache'])) ? $_POST['sprache'] : '';
    $neuGeburtsdatum = (isset($_POST['geburtsdatum']) && is_string($_POST['geburtsdatum'])) ? $_POST['geburtsdatum'] : '';

    if ($neuGeschlecht !== "m" && $neuGeschlecht !== "w" && $neuGeschlecht !== "") {
        $profilbearbeitung = false;
        $fehlermeldung = "Sie haben Ihr Geschlecht im falschen Format angegeben. Bitte wählen Sie 'm', 'w' oder lassen Sie das Feld leer.";
    } else {
        $profilbearbeitung = $dao->profil_editieren($sessionId, $sessionToken, $neuBeschreibung, $neuGeschlecht, $neuVollstaendigerName, $neuAdresse, $neuSprache, $neuGeburtsdatum);
        if (!$profilbearbeitung) $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}

// Profil laden
$selektiert = ""; //default value
if (isset($_REQUEST["id"]) && is_string($_REQUEST["id"])) {
    $profil = $dao->profil_erhalten($_REQUEST["id"]);
    if ($profil !== [-1]) {
        $id = $profil[0];
        $nutzername = $profil[1];
        $beschreibung = $profil[2];
        $geschlecht = $profil[3];
        if (isset($profil[3])) {
            $selektiert = $profil[3];
        }
        $vollstaendigerName = $profil[4];
        $adresse = $profil[5];
        $sprache = $profil[6];
        $geburtsdatum = $profil[7];
        $registrierungsdatum = $profil[8];
    } else {
        header("location: index.php?fehler=Profil");
    }

    //Nutzer Gemaelde erhalten
    $nutzerGemaelde = $dao->gemaelde_von_anbieter_erhalten($id);
    //Nutzer Sammlungen erhalten
    $nutzerSammlungen = $dao->sammlungen_von_anbieter_erhalten($id);

    // Erstes Gemaelde als Vorschaubild der jeweiligen Sammlung nehmen
    for ($j = 0; $j < sizeof($nutzerSammlungen); $j++) { //$reihe as $sammlung
        if (isset($nutzerSammlungen[$j][2][0]) && is_int($nutzerSammlungen[$j][2][0])) {
            $nutzerSammlungen[$j][2] = $dao->gemaelde_erhalten($nutzerSammlungen[$j][2][0]);
        }
    }
} else {
    header("location: index.php?fehler=Profil");
}

// Profil löschen
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && $_POST["loeschen"] === "loeschbestaetigung") {
    $loeschung = $dao->profil_entfernen($_SESSION["id"], $_SESSION["token"], $_GET["id"]);
    if ($loeschung) {
        session_unset();
        session_destroy();
        header("location: index.php?entfernt=Profil");
    } else {
        $fehlermeldung = "Sie sind möglicherweise nicht mehr angemeldet oder Ihre Session ist abgelaufen. Bitte melden Sie sich erneut an.";
    }
}
if (isset($_SESSION["id"]) && is_string($_SESSION["id"]) && isset($_SESSION["token"]) && is_string($_SESSION["token"]) && isset($_POST["loeschen"]) && is_string($_POST["loeschen"]) && $_POST["loeschen"] === "nichtbestaetigt") {
    $fehlermeldung = "Um dieses Profil zu löschen müssen Sie den Bestätigungshaken setzen.";
}

?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Profil";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($fehlermeldung) && is_string($fehlermeldung)): ?>
        <p class="nachricht fehler"><?php echo htmlspecialchars($fehlermeldung) ?></p>
    <?php endif ?>
    <?php if (isset($profilbearbeitung) && is_bool($profilbearbeitung) && $profilbearbeitung): ?>
        <p class="nachricht">Editierung erfolgreich!</p>
    <?php endif ?>

    <div class="usermanagement">
        <h1>Mein Profil</h1>

        <div class="profil">
            <?php if (isset($_SESSION["id"]) && $id == $_SESSION["id"]) : ?>
                <h2>Willkommen auf deinem Profil!</h2>
                <form method="post">
                    <h3>Nutzername</h3>
                    <p><?php echo htmlspecialchars($nutzername) ?></p>

                    <h3>Registrierungsdatum</h3>
                    <p><?php echo date("d.m.Y", strtotime($registrierungsdatum)); ?></p>

                    <h3>Beschreibung</h3>
                    <label for="beschreibung" class="invisible">Beschreibung</label>
                    <textarea id="beschreibung" cols="70" rows="10"
                              name="beschreibung"><?php echo htmlspecialchars($beschreibung) ?></textarea>

                    <h3>Geschlecht</h3>
                    <label for="geschlecht" class="invisible">Geschlecht</label>
                    <select id="geschlecht" name="geschlecht">
                        <option value="" <?php echo ($geschlecht === '') ? 'selected' : '' ?>>Keine Angabe</option>
                        <option value="m" <?php echo ($geschlecht === 'm') ? 'selected' : '' ?>>Männlich</option>
                        <option value="w" <?php echo ($geschlecht === 'w') ? 'selected' : '' ?>>Weiblich</option>
                    </select>


                    <h3>Vollständiger Name</h3>
                    <label for="vollstaendigerName" class="invisible">Vollständiger Name</label>
                    <input id="vollstaendigerName" type="text" name="vollstaendigerName"
                           value="<?php echo htmlspecialchars($vollstaendigerName) ?>"/>

                    <h3>Adresse</h3>
                    <label for="adresse" class="invisible">Adresse</label>
                    <input id="adresse" type="text" name="adresse"
                           value="<?php echo htmlspecialchars($adresse) ?>"/>

                    <h3>Sprache</h3>
                    <label for="sprache" class="invisible">Sprache</label>
                    <input id="sprache" type="text" name="sprache"
                           value="<?php echo htmlspecialchars($sprache) ?>"/>

                    <h3>Geburtsdatum</h3>
                    <label for="geburtsdatum" class="invisible">Geburtsdatum</label>
                    <input id="geburtsdatum" type="date" name="geburtsdatum"
                           value="<?php echo date("Y-m-d", strtotime($geburtsdatum)); ?>"/>
                    <br>
                    <br>
                    <button id="submit" name="submit" type="submit">Speichern</button>
                </form>

                <form method="post">
                    <h3>Profil löschen?</h3>
                    <input type="hidden" name="loeschen" value="nichtbestaetigt"/>
                    <input id="loeschen" type="checkbox" name="loeschen" value="loeschbestaetigung"/>
                    <label for="loeschen">Löschen bestätigen</label>
                    <button id="submit_delete" name="submit" type="submit">Löschen</button>
                </form>

            <?php else: ?>
                <h2>Willkommen auf dem Profil!</h2>
                <h3>Nutzername</h3>
                <p><?php echo htmlspecialchars($nutzername) ?></p>
                <?php if ($beschreibung !== '') : ?>
                    <h3>Beschreibung</h3>
                    <p><?php echo htmlspecialchars($beschreibung) ?></p>
                <?php endif ?>
                <?php if ($geschlecht !== '') : ?>
                    <h3>Geschlecht</h3>
                    <p><?php echo ($geschlecht === 'w') ? 'Weiblich' : '' ?><?php echo ($geschlecht === 'm') ? 'Männlich' : '' ?></p>
                <?php endif ?>
                <?php if ($vollstaendigerName !== '') : ?>
                    <h3>Vollständiger Name</h3>
                    <p><?php echo htmlspecialchars($vollstaendigerName) ?></p>
                <?php endif ?>
                <?php if ($adresse !== '') : ?>
                    <h3>Adresse</h3>
                    <p><?php echo htmlspecialchars($adresse) ?></p>
                <?php endif ?>
                <?php if ($sprache !== '') : ?>
                    <h3>Sprache</h3>
                    <p><?php echo htmlspecialchars($sprache) ?></p>
                <?php endif ?>
                <?php if ($geburtsdatum !== '') : ?>
                    <h3>Geburtsdatum</h3>
                    <p><?php echo date("d.m.Y", strtotime($geburtsdatum)); ?></p>
                <?php endif ?>
                <h3>Registrierungsdatum</h3>
                <p><?php echo date("d.m.Y", strtotime($registrierungsdatum)); ?></p>
            <?php endif ?>

            <h3>Ausgestellte Gemaelde</h3>
            <div class="profilausstellung">

                <?php if (count($nutzerGemaelde) == 0): ?>
                    <p> Hier gibt es noch nichts zu sehen..</p>
                <?php else: ?>

                    <?php foreach ($nutzerGemaelde as $reihe): ?>
                        <div class="eintrag">
                            <a href="gemaelde.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                                <img src="<?php echo 'images/' . htmlspecialchars($reihe[0]) . "." . htmlspecialchars($reihe[10]) ?>"
                                     alt="<?php echo htmlspecialchars($reihe[2]) ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>

                <?php endif ?>
            </div>

            <h3>Ausgestellte Sammlungen</h3>
            <div class="profilausstellung">

                <?php if (empty($nutzerSammlungen)): ?>
                    <p> Hier gibt es noch nichts zu sehen..</p>
                <?php else: ?>

                    <?php foreach ($nutzerSammlungen as $reihe): ?>
                        <div class="eintrag">
                            <a href="sammlung.php?id=<?php echo htmlspecialchars($reihe[0]) ?>">
                                <img src="<?php echo "images/" . htmlspecialchars($reihe[2][0]) . "." . htmlspecialchars($reihe[2][10]) ?>"
                                     alt="<?php echo htmlspecialchars($reihe[3]) ?>">
                            </a>
                        </div>
                    <?php endforeach; ?>

                <?php endif ?>
            </div>

        </div>

    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>