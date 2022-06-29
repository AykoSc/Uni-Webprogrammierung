<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";

class NutzerDAODummyImpl implements NutzerDAO
{
    /**
     * @implNote Datenspeicherung (ALT), soll nicht gelöscht werden
     */

    // [NutzerID, Email, Passwort]
    private array $users = [
        [0, "test1@test.com", "test1!"],
        [1, "test2@test.com", "test2!"]
    ];

    // [NutzerID, Nutzername, beschreibung, geschlecht, VollständigerName, Adresse, Sprache, Geburtsdatum, Registrierungsdatum]
    private array $users_profil = [
        [0, "test1", "Ich bin Test 1 !", "m", "Max Mustermann", "Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg", "deutsch", "04.10.2000", "01.06.2022"],
        [1, "test2", "Ich bin der User Test 2 !", "w", "Maxine Musterfrau", "Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg", "deutsch", "01.11.2000", "28.05.2022"]
    ];

    // [users_NutzerID, Validierungstoken]
    private array $users_tokens = [
        [0, "mA23zbjdkENShbk9ezqNp5nQMpyrVb7m"],
        [1, "YRSPGgPjnDSuy7b5GuNFBEz9e4AAwaj7"]
    ];

    // [GemaeldeID, users_NutzerID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung (*/10), Hochladedatum, Aufrufe]
    // href: gemaelde.php?id=[GemaeldeID]
    // Datei: images/[GemaeldeID].jpg
    private array $gemaelde = [
        [0, 0, "Stockbild0", "Stockkünstler0", "Beschreibung von Bild 0", "04.09.1900", "München, Deutschland", 8, "07.10.2021", 56],
        [1, 1, "Stockbild1", "Stockkünstler1", "Beschreibung von Bild 1", "05.10.1234", "Oldenburg, Deutschland", 9, "01.06.2022", 4],
        [2, 1, "Stockbild2", "Stockkünstler2", "Beschreibung von Bild 2", "06.11.1432", "Berlin, Deutschland", 4, "06.09.2022", 8]
    ];

    // [KommentarID, gemaelde_GemaeldeID, users_NutzerID, Likeanzahl, Text, Erstellungsdatum]
    private array $kommentare = [
        [0, 0, 0, 274, "Dies ist ein Kommentar!", "05.10.2021"],
        [1, 0, 1, 346, "Dies ist auch ein Kommentar!!", "07.06.2022"],
        [2, 1, 2, 56, "Mein erster Kommentar.", "02.03.2022"],
        [3, 2, 0, 23, "Mein toller Kommentar.", "01.06.2022"]
    ];

    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    private array $sammlungen = [
        [0, 1, [0, 2, 1], "Sammlung0", "Beschreibung von Bild 0", 3, "03.01.2021", 2234],
        [1, 1, [1, 0], "Sammlung1", "Beschreibung von Bild 1", 7, "06.04.2022", 34],
        [2, 0, [2, 0], "Sammlung2", "Beschreibung von Bild 2", 5, "02.03.2022", 8673]
    ];

    private static $instance = null;

    private function __construct()
    {

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
        // Ob ein Nutzername unbenutzt ist, wird in der DBImpl erst benötigt.
        return true;
    }

    public function registrieren($Nutzername, $Email, $Passwort): bool
    {
        // Registrierung wird in der DBImpl gemacht, da es eine schreibende Methode ist
        return true;
    }

    public function anmelden($Email, $Passwort): array
    {
        if (isset($Email) and is_string($Email) and isset($Passwort) and is_string($Passwort)) {
            foreach ($this->users as $user) {
                if ($user[1] === htmlspecialchars($Email) and $user[2] === htmlspecialchars($Passwort)) {
                    // Token wird hier nicht in Datenbank gespeichert und an User gesendet, da es eine schreibende Methode ist
                    foreach ($this->users_tokens as $token) {
                        if ($user[0] === $token[0]) {
                            return array($token[0], $token[1]); // Anmeldung erfolgreich
                        }
                    }
                }
            }
        }
        return array(-1, ""); // Anmeldung fehlgeschlagen
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
        if (isset($GemaeldeID) and is_string($GemaeldeID)) {
            foreach ($this->gemaelde as $g) {
                if ($g[0] == htmlspecialchars($GemaeldeID)) {
                    return $g;
                }
            }
        }
        return [-1];
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
        if (isset($SammlungID) and is_string($SammlungID)) {
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
        // Kommentar wird hier nicht geliked, da es eine schreibende Methode ist
        return true;
    }

    public function kommentare_erhalten($GemaeldeID, $AnbieterID, $Tokennummer): array
    {
        $result = array();
        if (isset($GemaeldeID) and is_string($GemaeldeID)) {
            foreach ($this->kommentare as $k) {
                if ($k[1] == htmlspecialchars($GemaeldeID)) {
                    $result[] = $k;
                }
            }
        }
        return $result;
    }

    public function profil_erhalten($AnbieterID): array
    {
        if (isset($AnbieterID) and is_string($AnbieterID)) {
            foreach ($this->users_profil as $profil) {
                if ($profil[0] == htmlspecialchars($AnbieterID)) {
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

    public function ausstellung_erhalten($Suche, $Filter): array
    {
        $Suchergebnis = array();
        if (isset($Suche) and is_string($Suche)) {
            foreach ($this->gemaelde as $g) {
                if (str_contains($g[2], $Suche)) {
                    $Suchergebnis[] = $g;
                }
            }
        } else {
            $Suchergebnis = $this->gemaelde;
        }
        if (isset($Filter) and is_string($Filter)) {
            if ($Filter === "beliebteste") { //Nach beliebtesten sortieren
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
        if (isset($Suche) and is_string($Suche)) {
            foreach ($this->sammlungen as $s) {
                if (str_contains($s[3], $Suche)) {
                    $Suchergebnis[] = $s;
                }
            }
        } else {
            $Suchergebnis = $this->sammlungen;
        }
        if (isset($Filter) and is_string($Filter)) {
            if ($Filter === "beliebteste") { //Nach beliebtesten sortieren
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
        }

        $ergebnis = array(array(), array(), array(), array());
        $reihe = 0;
        foreach ($Suchergebnis as $sammlungen_result) {
            $ergebnis[$reihe][] = $sammlungen_result;
            $reihe = ($reihe + 1) % 4;
        }

        return $ergebnis;
    }


}
