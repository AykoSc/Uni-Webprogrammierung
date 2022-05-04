<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Gemälde</title>
</head>


<body>

    <?php include 'php/header.php';?>

    <main>
        <section>
            <h1>Titel</h1>

            <div> 
                <img alt="Profil" src="images/stockblume_2.jpg"
                    width="150"> 
            </div>
            <div>Beschreibung</div>
        
            <h2> Kommentarbereich </h2> 
            <form method="post">
                <div>
                    <textarea cols="40" rows="5" 
                    maxlength="1000" wrap="soft" placeholder="Neuen Kommentar schreiben...">Neuer Kommentar:</textarea>
                    <input type="submit" value="Kommentieren">
                </div>
            </form> 
            
            <div>
                <p> Kommentar</p>
             </div>

            <div> Lorem ipsum dolor sit amet, consetetur sadipscing elitr,
                 sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
                 sed diam voluptua. 
            </div>

        </section>
    </main>

    <?php include 'php/footer.php';?>

</body>

</html>