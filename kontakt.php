<?php
session_start();
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . '/controller/NutzerDAODummyImpl.php';
$user = new NutzerDAODummyImpl();

if (isset($_POST['email']) and is_string($_POST['email']) and
    isset($_POST['kommentar']) and is_string($_POST['kommentar'])) {
    $erfolgreich = $user->kontakt_aufnehmen(htmlspecialchars($_POST['email']), htmlspecialchars($_POST['kommentar']));
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
    <?php if (isset($erfolgreich) and is_bool($erfolgreich) and $erfolgreich): ?>
        <p class="nachricht">Kontakt erfolgreich aufgenommen. Wir werden uns um ihr Anliegen schnellstmöglich
            kümmern!</p>
    <?php endif ?>

    <h1>Kontakt</h1>

    <div class="usermanagement">
        <h3>Hier kannst du Kontakt aufnehmen</h3>
        <form method="post" action="kontakt.php">
            <hr>
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email" maxlength="100" required>

            <label for="kommentar">Kommentar:</label>
            <textarea id="kommentar" name="kommentar" cols="40" rows="10" maxlength="1000" wrap="soft"
                      placeholder="Neuen Kommentar schreiben..." required></textarea>
            <hr>
            <button type="submit">Senden</button>
            <a href="index.php">Zurück zur Startseite</a>
        </form>
    </div>

</main>

<?php include $abs_path . '/php/footer.php'; ?>

</body>

</html>