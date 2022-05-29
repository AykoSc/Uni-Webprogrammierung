<?php if (!isset($abs_path)) include_once 'path.php'; ?>

<!DOCTYPE html>
<html lang="de">

<?php
$name = "Impressum";
include $abs_path . 'php/head.php';
?>

<body>

<?php include $abs_path . 'php/header.php'; ?>

<main>

    <h1>Impressum</h1>

    <div class="impressum">
        <h2>Angaben gem&auml;&szlig; &sect; 5 TMG</h2>
        <p>Max Mustermann<br/>
            Musterladen (Einzelunternehmer)<br/>
            Musterweg 1<br/>
            12345 Musterstadt</p>

        <h2>Kontakt</h2>
        <p>Telefon: +49 (0) 123 45 67 89<br/>
            Telefax: +49 (0) 123 45 67 89<br/>
            E-Mail: mustermann@email.de</p>

        <h2>Umsatzsteuer-ID</h2>
        <p>Umsatzsteuer-Identifikationsnummer gem&auml;&szlig; &sect; 27 a Umsatzsteuergesetz:<br/>
            DE999999999</p>

        <h2>Redaktionell verantwortlich</h2>
        <p>Beate Beispielhaft<br/>
            Musterweg 2<br/>
            12345 Musterstadt</p>

        <h2>Verbraucher&shy;streit&shy;beilegung/Universal&shy;schlichtungs&shy;stelle</h2>
        <p>Wir sind nicht bereit oder verpflichtet, an Streitbeilegungsverfahren vor einer Verbraucherschlichtungsstelle
            teilzunehmen.</p>

        <p>Quelle: <a href="https://www.e-recht24.de">https://www.e-recht24.de</a></p>
    </div>

</main>

<?php include $abs_path . 'php/footer.php'; ?>

</body>

</html>