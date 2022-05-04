<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Start</title>
</head>


<body>

    <?php include 'php/header.php';?>

    <main>
        <h1> Neues Gemälde hochladen </h1>
        <section>
            <h2> Gemälde auswählen </h2>
            <form action="/gemaelde-neu.php"> 
                <input type="file" name="img">
                <input type="submit">
            </form>

            <div> 
                Gemälde Vorschau hier
            </div>

            <form method="post">
                <div>
                    <textarea cols="40" rows="5" 
                    maxlength="1000" wrap="soft" placeholder="Fügen Sie eine Beschreibung ein">
                     </textarea>
                </div>   
                
                <h2> Zusätzliche Daten angeben </h2>

                <div>
                    <label for="titel">Titel:</label>
                    <div>
                        <input type="text" id="titel" name="titel" maxlength="100" required>
                    </div>
                </div>

                <div>
                    <label for="artist">Künstler:</label>
                    <div>
                        <input type="text" id="artist" name="artist" maxlength="100" value="Benutzername" required>
                    </div>
                </div>

                <div>
                    <label for="location">Datum der Erstellung:</label>
                    <div>
                        <input type="date" id="date" name="date">
                    </div>
                </div>

                <div>
                    <label for="location">Ort:</label>
                    <div>
                        <input type="text" id="location" name="location" maxlength="100" >
                    </div>
                </div>

                <div>
                    <a href="index.php">Abbrechen</a>
                    <button type="submit">Fertigstellen</button>
                </div>
            </form>

        </section>

    </main>



    <?php include 'php/footer.php';?>

</body>

</html>