<?php
if (!isset($abs_path)) include_once 'path.php';
include_once $abs_path . "/controller/NutzerDAODBImpl.php";
$dao = NutzerDAODBImpl::getInstance();

// Erhalte die Titel aller potenziellen Ergebnisse
$vorschlaege = "";
if (isset($_GET["suche"]) and is_string($_GET["suche"]) and
    isset($_GET["herkunft"]) and is_string($_GET["herkunft"])) {
    if ($_GET["herkunft"] === "ausstellung") {
        $ausstellung = $dao->ausstellung_erhalten(htmlspecialchars($_GET["suche"]), "");
        $ergebniszaehler = 0;
        foreach ($ausstellung as $reihe) {
            foreach ($reihe as $gemaelde) {
                if ($vorschlaege === "") {
                    $vorschlaege .= $gemaelde[2];
                } else {
                    $vorschlaege .= ", " . $gemaelde[2];
                }
                $ergebniszaehler++;
                if ($ergebniszaehler == 10) break 2;
            }
        }
    } else if ($_GET["herkunft"] === "sammlungen") {
        $sammlungen = $dao->sammlungen_erhalten(htmlspecialchars($_GET["suche"]), "");
        $ergebniszaehler = 0;
        foreach ($sammlungen as $reihe) {
            foreach ($reihe as $sammlung) {
                if ($vorschlaege === "") {
                    $vorschlaege .= $sammlung[3];
                } else {
                    $vorschlaege .= ", " . $sammlung[3];
                }
                $ergebniszaehler++;
                if ($ergebniszaehler == 10) break 2;
            }
        }
    }
}

echo $vorschlaege === "" ? "Keine Vorschläge" : "Vorschläge: " . htmlspecialchars($vorschlaege);