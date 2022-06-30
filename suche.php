<?php
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Erhalte die Titel aller potenziellen Ergebnisse
$ergebnis = "";
if (isset($_GET["suche"]) and is_string($_GET["suche"]) and
    isset($_GET["herkunft"]) and is_string($_GET["herkunft"])) {
    if ($_GET["herkunft"] === "ausstellung") {
        $ausstellung = $dao->ausstellung_erhalten(htmlspecialchars($_GET["suche"]), "");
        $ergebniszaehler = 0;
        foreach ($ausstellung as $reihe) {
            foreach ($reihe as $gemaelde) {
                if ($ergebnis !== "") $ergebnis .= "<br/>";
                $ergebnis .= "<a href='gemaelde.php?id=" . htmlspecialchars($gemaelde[0]) . "'>" . "<img style='margin-right: 15px' src='images/suche_schwarz.svg' alt='suche' width='13' height='13'>" . htmlspecialchars($gemaelde[2]) . "</a>";
                $ergebniszaehler++;
                if ($ergebniszaehler == 10) break 2;
            }
        }
    } else if ($_GET["herkunft"] === "sammlungen") {
        $sammlungen = $dao->sammlungen_erhalten(htmlspecialchars($_GET["suche"]), "");
        $ergebniszaehler = 0;
        foreach ($sammlungen as $reihe) {
            foreach ($reihe as $sammlung) {
                if ($ergebnis !== "") $ergebnis .= "<br/>";
                $ergebnis .= "<a href='sammlung.php?id=" . htmlspecialchars($sammlung[0]) . "'>" . "<img style='margin-right: 15px' src='images/suche_schwarz.svg' alt='suche' width='13' height='13'>" . htmlspecialchars($sammlung[3]) . "</a>";
                $ergebniszaehler++;
                if ($ergebniszaehler == 10) break 2;
            }
        }
    } else if ($_GET["herkunft"] === "registrierung") {
        $ergebnis = $dao->nutzername_unbenutzt(htmlspecialchars($_GET["suche"]));
    }
}

echo $ergebnis === "" ? "Keine Vorschläge" : $ergebnis;