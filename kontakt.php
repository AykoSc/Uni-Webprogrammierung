<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Kontakt";
include $abs_path . '/php/head.php';
?>

<body>

<?php include $abs_path . '/php/header.php'; ?>

<main>

    <h1>Kontakt</h1>

    <div class="usermanagement">
        <h3>Hier kannst du Kontakt aufnehmen</h3>
        <form>
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