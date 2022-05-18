<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css"/>
    <link rel="stylesheet" href="css/gemaelde.css"/>
    <title>Online-Ausstellung - Gemälde</title>
</head>


<body>

<?php include 'php/header.php'; ?>

<main>

    <h1>Beispiel Gemälde</h1>
    <img alt="Stockbild" src="images/start.jpg" style="width: 100%">

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
                <form>
                    <label for="kommentar" style="display:none;">Kommentar</label>
                    <textarea id="kommentar" name="kommentar" maxlength="1000"
                              placeholder="Neuen Kommentar schreiben..."
                              required></textarea>
                    <input type="submit" value="Kommentieren">
                </form>
            </div>
        </div>
        <ul class="comment-section">
            <li class="comment">
                <div class="info">
                    <a href="profil.php">Tim Wiese</a>
                    <span>4 hours ago</span>
                </div>
                <a class="avatar" href="profil.php">
                    <img src="images/start.jpg" width="35" alt="Profile Avatar"/>
                </a>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>

                <a class="likes">
                    <img alt="thumbsUp" src="images/thumbsUp.png" width="20">
                    12
                </a>
            </li>
            <li class="comment">
                <div class="info">
                    <a href="profil.php">Ole Eule</a>
                    <span>4 hours ago</span>
                </div>
                <a class="avatar" href="profil.php">
                    <img src="images/start.jpg" width="35" alt="Profile Avatar"/>
                </a>
                <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores
                    et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
                    labore et dolore magna aliquyam erat, sed diam voluptua.</p>
                <a class="likes">
                    <img alt="thumbsUp" src="images/thumbsUp.png" width="20">
                    4
                </a>
            </li>
        </ul>

    </section>

</main>

<?php include 'php/footer.php'; ?>

</body>

</html>