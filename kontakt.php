<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

if (isset($_POST['email']) && is_string($_POST['email']) && isset($_POST['kommentar']) && is_string($_POST['kommentar'])) {
    $erfolgreich = $dao->kontakt_aufnehmen($_POST['email'], $_POST['kommentar']);
}
?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Kontakt";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>
    <?php if (isset($erfolgreich) && is_bool($erfolgreich) && $erfolgreich): ?>
        <p class="nachricht">Kontakt erfolgreich aufgenommen. Wir werden uns um ihr Anliegen schnellstmöglich
            kümmern!</p>
    <?php endif ?>

    <h1>Kontakt</h1>

    <div class="usermanagement">
        <h3>Hier kannst du Kontakt aufnehmen</h3>
        <form method="post">
            <hr>
            <label for="email">*E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" required placeholder="E-Mail eingeben...">

            <label for="kommentar">*Kommentar:</label>
            <textarea id="kommentar" name="kommentar" cols="40" rows="10" maxlength="1000" wrap="soft"
                      placeholder="Neuen Kommentar schreiben..." required></textarea>
            <hr>
            <p>Die mit * gekennzeichneten Felder sind Pflichtfelder.</p>
            <button type="submit">Senden</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>