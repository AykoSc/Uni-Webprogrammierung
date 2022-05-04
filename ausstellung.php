<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Virtuelle Online-Ausstellung von Gemälden">
    <meta name="author" content="Jonas Brüggemann, Ayko Schwedler, Jan Niklas Pollak">
    <link rel="stylesheet" href="css/main.css" />
    <title>Online-Ausstellung - Ausstellung</title>
</head>


<body>

    <?php include 'php/header.php';?>

    <main>
        <section> 
            <h1>Ausstellung</h1>
            <div>
                <div>
                    <form action="/ausstellung.php">
                        <input type="text" placeholder="Suche.." name="suche">
                        <button><i> Suche </i> </button>   
                    </form>
                </div>
                <div>
                    <select name="Filter" size="1">
                        <option value="relevance" selected>Beliebteste</option>
                        <option value="date">Datum</option>
                        <option value="artist">Künstler</option>
                    </select>       
                </div>
            </div>
            

            <div> 
                <a href="gemaelde.php">
                    <img alt="Profil" src="images/stockblume_2.jpg"
                     width="150">
                </a>
            </div>
            <div> 
                <a href="gemaelde.php">
                    <img alt="Profil" src="images/stockblume_1.jpg"
                     width="150">
                </a>
            </div>

        </section>
        

    </main>

    <?php include 'php/footer.php';?>

</body>

</html>