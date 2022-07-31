<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";

class NutzerDAODummyImpl implements NutzerDAO
{
    /**
     * @implNote Datenspeicherung (ALT), soll nicht gelöscht werden
     */

    // [NutzerID, Email, Passwort, Token]
    private array $users = [
        [1, "test1@test.com", "test1!", "mA23zbjdkENShbk9ezqNp5nQMpyrVb7m"],
        [2, "test2@test.com", "test2!", "YRSPGgPjnDSuy7b5GuNFBEz9e4AAwaj7"]
    ];

    // [NutzerID, Nutzername, beschreibung, geschlecht, VollständigerName, Adresse, Sprache, Geburtsdatum, Registrierungsdatum]
    private array $users_profil = [
        [1, "test1", "Ich bin Test 1 !", "m", "Max Mustermann", "Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg", "deutsch", "04.10.2000", "01.06.2022"],
        [2, "test2", "Ich bin der User Test 2 !", "w", "Maxine Musterfrau", "Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg", "deutsch", "01.11.2000", "28.05.2022"]
    ];

    // [GemaeldeID, AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung (*/5), Hochladedatum, Aufrufe, Dateityp, Nutzername]
    // href: gemaelde.php?id=[GemaeldeID]
    // Datei: images/[GemaeldeID].jpg
    private array $gemaelde = [
        [1, 1, "Stockbild0", "Stockkünstler0", "Beschreibung von Bild 0", "04.09.1900", "München, Deutschland", 4, "06.09.2022", 56, "jpg", "test1"],
        [2, 2, "Stockbild1", "Stockkünstler1", "Beschreibung von Bild 1", "05.10.1234", "Oldenburg, Deutschland", 5, "01.06.2022", 4, "jpg", "test2"],
        [3, 2, "Stockbild2", "Stockkünstler2", "Beschreibung von Bild 2", "06.11.1432", "Berlin, Deutschland", 3, "07.10.2021", 8, "jpg", "test2"]
    ];

    // [KommentarID, gemaelde_GemaeldeID, users_NutzerID, Likeanzahl, Text, Erstellungsdatum]
    private array $kommentare = [
        [1, 1, 1, 274, "Dies ist ein Kommentar!", "05.10.2021"],
        [2, 1, 2, 346, "Dies ist auch ein Kommentar!!", "07.06.2022"],
        [3, 2, 2, 56, "Mein erster Kommentar.", "02.03.2022"],
        [4, 3, 1, 23, "Mein toller Kommentar.", "01.06.2022"]
    ];
    private array $sammlungen = [
        [1, 1, [1, 2, 3], "Sammlung0", "Beschreibung von Bild 0", 3, "06.04.2022", 2234, "test1"],
        [2, 1, [2, 1], "Sammlung1", "Beschreibung von Bild 1", 4, "02.03.2022", 34, "test1"],
        [3, 2, [3, 1], "Sammlung2", "Beschreibung von Bild 2", 5, "03.01.2021", 8673, "test1"]
    ];

    private static ?NutzerDAODummyImpl $instance = null;

    private function __construct()
    {
        //Setze Default Constructor privat, um damit direkte Instanziierungen zu verbieten

        //Stelle alle Errors und Warnings aus, damit Nutzer keine Errors oder Warnings angezeigt bekommen.
        //Dies wird zum Beispiel benötigt, um nicht den API-Key zu veröffentlichen, falls die API nicht erreichbar ist.
        error_reporting(0);
    }

    public static function getInstance(): NutzerDAODummyImpl
    {
        if (self::$instance == null) {
            self::$instance = new NutzerDAODummyImpl();
        }

        return self::$instance;
    }

    public function nutzername_unbenutzt($Nutzername): bool
    {
        return !($Nutzername === "test1" || $Nutzername === "test2");
    }

    public function registrieren($Nutzername, $Email, $Passwort): bool
    {
        // Registrierung wird in der DBImpl gemacht, da es eine schreibende Methode ist
        return true;
    }

    public function registrieren_bestaetigen($Email, $Verifizierungscode): bool
    {
        // Registrierung bestätigen wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }

    public function anmelden($Email, $Passwort): array
    {
        foreach ($this->users as $user) {
            if ($user[1] === $Email && $user[2] === $Passwort) {
                // Token wird hier nicht in Datenbank gespeichert und an User gesendet, da es schreibend wäre
                return array(strval($user[0]), $user[3]);
            }
        }
        return array("-1", "");
    }

    public function abmelden($AnbieterID, $Tokennummer): bool
    {
        // Token wird hier nicht aus der Tabelle für valide Token gelöscht, da es eine schreibende Methode ist
        return true;
    }

    public function kontakt_aufnehmen($EMail, $Kommentar): bool
    {
        // Kontaktaufnahme wird hier nicht gespeichert, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_anlegen($AnbieterID, $Tokennummer, $Dateityp, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort): int
    {
        // Gemälde wird hier nicht angelegt, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_editieren($AnbieterID, $Tokennummer, $GemaeldeID, $Beschreibung, $Erstellungsdatum, $Ort): bool
    {
        // Gemälde wird hier nicht editiert, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool
    {
        // Gemälde wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_erhalten($GemaeldeID): array
    {
        if (isset($GemaeldeID)) {
            foreach ($this->gemaelde as $g) {
                if ($g[0] == $GemaeldeID) {
                    return $g;
                }
            }
        }
        return [-1];
    }

    public function gemaelde_bewerten($AnbieterID, $Tokennummer, $GemaeldeID, $Bewertung): bool
    {
        // Gemälde bewerten wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }

    public function eigene_gemaelde_bewertung_erhalten($AnbieterID, $GemaeldeID): int
    {
        return 0; //Bewertung ist von allen Testnutzern auf 0
    }

    public function sammlung_anlegen($AnbieterID, $Tokennummer, $Auswahl, $Titel, $Beschreibung): int
    {
        // Sammlung wird hier nicht angelegt, da es eine schreibende Methode ist
        return -1;
    }

    public function sammlung_editieren($AnbieterID, $Tokennummer, $SammlungID, $Titel, $Beschreibung): bool
    {
        // Sammlung wird hier nicht editiert, da es eine schreibende Methode ist
        return true;
    }

    public function sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID): bool
    {
        // Sammlung wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function sammlung_erhalten($SammlungID): array
    {
        if (isset($SammlungID) && is_string($SammlungID)) {
            foreach ($this->sammlungen as $s) {
                if ($s[0] == htmlspecialchars($SammlungID)) {
                    return $s;
                }
            }
        }
        return [-1];
    }

    public function kommentar_anlegen($AnbieterID, $Tokennummer, $Textinhalt, $GemaeldeID): bool
    {
        // Kommentar wird hier nicht angelegt, da es eine schreibende Methode ist
        return true;
    }

    public function kommentar_entfernen($AnbieterID, $Tokennummer, $KommentarID): bool
    {
        // Kommentar wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function kommentar_liken($AnbieterID, $Tokennummer, $KommentarID): bool
    {
        // Kommentar wird hier nicht gelikt, da es eine schreibende Methode ist
        return true;
    }

    public function kommentare_erhalten($GemaeldeID, $AnbieterID, $Tokennummer): array
    {
        $result = array();
        if (isset($GemaeldeID) && is_string($GemaeldeID)) {
            foreach ($this->kommentare as $k) {
                if ($k[1] == $GemaeldeID) {
                    $k[] = $this->users_profil[$k[2] - 1][1]; //Name vom Verfasser
                    $k[] = 0; // Kommentar nicht geliked
                    $result[] = $k;
                }
            }
        }
        return $result;
    }

    public function profil_erhalten($AnbieterID): array
    {
        if (isset($AnbieterID) && is_string($AnbieterID)) {
            foreach ($this->users_profil as $profil) {
                if ($profil[0] == $AnbieterID) {
                    return $profil;
                }
            }
        }
        return [-1];
    }

    public function profil_editieren($AnbieterID, $Tokennummer, $Personenbeschreibung, $Geschlecht, $Vollstaendiger_Name, $Anschrift, $Sprache, $Geburtsdatum): bool
    {
        // Profil editieren wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }

    public function profil_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool
    {
        // Profil löschen wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }

    public function ausstellung_erhalten($Suche, $Filter): array
    {
        $Suchergebnis = array();
        if (isset($Suche) && is_string($Suche)) {
            foreach ($this->gemaelde as $g) {
                if (str_contains($g[2], $Suche)) {
                    $Suchergebnis[] = $g;
                }
            }
        } else {
            $Suchergebnis = $this->gemaelde;
        }
        if (isset($Filter) && $Filter === "beliebteste") { //Nach beliebtesten sortieren
            for ($i = 0; $i < sizeof($Suchergebnis); $i++) {
                for ($j = $i + 1; $j < sizeof($Suchergebnis); $j++) {
                    if ($Suchergebnis[$i][9] < $Suchergebnis[$j][9]) {
                        $temp = $Suchergebnis[$i];
                        $Suchergebnis[$i] = $Suchergebnis[$j];
                        $Suchergebnis[$j] = $temp;
                    }
                }
            }
        }

        $ergebnis = array(array(), array(), array(), array());
        $reihe = 0;
        foreach ($Suchergebnis as $gemaelde_result) {
            $ergebnis[$reihe][] = $gemaelde_result;
            $reihe = ($reihe + 1) % 4;
        }

        return $ergebnis;
    }

    public function sammlungen_erhalten($Suche, $Filter): array
    {
        $Suchergebnis = array();
        if (isset($Suche) && is_string($Suche)) {
            foreach ($this->sammlungen as $s) {
                if (str_contains($s[3], $Suche)) {
                    $Suchergebnis[] = $s;
                }
            }
        } else {
            $Suchergebnis = $this->sammlungen;
        }
        if (isset($Filter) && $Filter === "beliebteste") { //Nach beliebtesten sortieren
            for ($i = 0; $i < sizeof($Suchergebnis); $i++) {
                for ($j = $i + 1; $j < sizeof($Suchergebnis); $j++) {
                    if ($Suchergebnis[$i][7] < $Suchergebnis[$j][7]) {
                        $temp = $Suchergebnis[$i];
                        $Suchergebnis[$i] = $Suchergebnis[$j];
                        $Suchergebnis[$j] = $temp;
                    }
                }
            }
        }

        $ergebnis = array(array(), array(), array(), array());
        $reihe = 0;
        foreach ($Suchergebnis as $sammlungen_result) {
            $ergebnis[$reihe][] = $sammlungen_result;
            $reihe = ($reihe + 1) % 4;
        }

        return $ergebnis;
    }

    public function eigene_sammlung_bewertung_erhalten($AnbieterID, $SammlungID): int
    {
        return 0; //Bewertung ist von allen Testnutzern auf 0
    }

    public function sammlung_bewerten($AnbieterID, $Tokennummer, $SammlungID, $Bewertung): bool
    {
        // sammlung_bewerten wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }

    public function sammlungen_von_anbieter_erhalten($AnbieterID): array
    {
        $ergebnis = array();
        foreach ($this->sammlungen as $s) {
            if ($s[1] == $AnbieterID) {
                $ergebnis[] = $s;
            }
        }
        return $ergebnis;
    }

    public function gemaelde_von_anbieter_erhalten($AnbieterID): array
    {
        $ergebnis = array();
        foreach ($this->gemaelde as $g) {
            if ($g[1] == $AnbieterID) {
                $ergebnis[] = $g;
            }
        }
        return $ergebnis;
    }

    public function gemaelde_aus_sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID, $GemaeldeID): bool
    {
        // gemaelde_aus_sammlung_entfernen wird hier nicht implementiert, da es eine schreibende Methode ist
        return true;
    }
}
