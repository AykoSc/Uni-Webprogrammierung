<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Mein Profil</title>
</head>


<body>

    <?php include 'php/header.php';?>

    <main>
        <section>
        <h1>Mein Profil</h1>
        <div>
        <img src="images/profilbild_dummy.jpg" alt="Profil"
        width="200">
        </div>
        <div>Name</div>
        <div>Herkunft</div>
        <div>Geburtstag</div>
        <div>Über mich</div>   
        <div>
             <p>Ausgestellte Gemälde</p>
                <span>
                    <img src="images/stockblume_1.jpg" alt="Profil"
                    width="150"> 
                </span>
                <span>
                    <img src="images/stockblume_3.jpg" alt="Profil"
                    width="150"> 
                </span> 
        </div>
        <div>
            <p>Ausgestellte Sammlungen</p>
            <span>
                <a href="gemaelde.php">
                    <img alt="Profil" src="images/stockblume_2.jpg"
                     width="150">
                </a>
            </span> 
                
        </div>
        </section>
    </main>

    <?php include 'php/footer.php';?>

</body>

</html>