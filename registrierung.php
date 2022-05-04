<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Registrierung</title>
</head>


<body>

    <?php include 'php/header.php'; ?>

    <main>

        <h1>Registrierung</h1>

        <section>
            <h3>Hier kannst du dich registieren</h3>
            <form>
                <div>
                    <label for="username">Benutzername:</label>
                    <div>
                        <input type="text" id="username" name="username" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="email">E-Mail:</label>
                    <div>
                        <input type="email" id="email" name="email" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="password">Passwort:</label>
                    <div>
                        <input type="password" id="password" name="password" minlength="8" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <label for="password_repeat">Passwort wiederholen:</label>
                    <div>
                        <input type="password" id="password_repeat" name="password_repeat" minlength="8" maxlength="100" required>
                    </div>
                </div>
                <div>
                    <a href="index.php">Abbrechen</a>
                    <button type="submit">Registrieren</button>
                </div>
            </form>
        </section>

    </main>

    <?php include 'php/footer.php'; ?>

</body>

</html>