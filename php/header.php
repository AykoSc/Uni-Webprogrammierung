<?php if (!isset($abs_path)) include_once '../path.php'; ?>

<header>
    <nav>
        <div class="header-left">
            <a href="index.php" class="textlogo">Art Place</a>
        </div>

        <div class="header-right">
            <a href="ausstellung.php" class="navitem">Ausstellung</a>
            <a href="sammlungen.php" class="navitem">Sammlungen</a>

            <?php if (!isset($_SESSION["id"]) and !isset($_SESSION["token"])): ?>
                <a href="anmeldung.php" class="navitem">Anmeldung</a>
                <a href="registrierung.php" class="navitem">Registrierung</a>
            <?php endif ?>

            <?php if (isset($_SESSION["id"]) and isset($_SESSION["token"])): ?>
                <a href="neuereintrag.php" class="navitem">Neuer Eintrag</a>
                <a href="profil.php?id=<?php echo htmlspecialchars($_SESSION['id']) ?>" class="navitem">Mein Profil</a>
                <a href="index.php?abmelden=1" class="navitem">Abmeldung</a>
            <?php endif ?>
        </div>
    </nav>
</header>