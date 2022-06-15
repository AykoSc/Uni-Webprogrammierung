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

    public function registrieren($nutzername, $email, $passwort): bool
    {
        // Registrierung wird in der DBImpl gemacht, da es eine schreibende Methode ist
        return true;
    }

    public function anmelden($email, $passwort): array
    {
        if (isset($email) and is_string($email) and isset($passwort) and is_string($passwort)) {
            foreach ($this->users as $user) {
                if ($user[1] === htmlspecialchars($email) and $user[2] === htmlspecialchars($passwort)) {
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

    public function abmelden($nutzerID, $nutzerToken): bool
    {
        // Token wird hier nicht aus der Tabelle für valide Token gelöscht, da es eine schreibende Methode ist
        return true;
    }

    public function kontakt_aufnehmen($email, $kommentar): bool
    {
        // Kontaktaufnahme wird hier nicht gespeichert, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_anlegen($AnbieterID, $file, $titel, $beschreibung, $artist, $date, $location): bool
    {
        // Gemälde wird hier nicht angelegt, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_editieren($gemaeldeID, $beschreibung, $erstellungsdatum, $ort): bool
    {
        // Gemälde wird hier nicht editiert, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_entfernen($gemaeldeID): bool
    {
        // Gemälde wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function gemaelde_erhalten($gemaeldeID): array
    {
        if (isset($gemaeldeID) and is_string($gemaeldeID)) {
            foreach ($this->gemaelde as $g) {
                if ($g[0] == htmlspecialchars($gemaeldeID)) {
                    return $g;
                }
            }
        }
        return [-1];
    }

    public function sammlung_anlegen($id, $auswahl, $titel, $beschreibung): bool
    {
        // Sammlung wird hier nicht angelegt, da es eine schreibende Methode ist
        return true;
    }

    public function sammlung_editieren($sammlungID, $titel, $beschreibung): bool
    {
        // Sammlung wird hier nicht editiert, da es eine schreibende Methode ist
        return true;
    }

    public function sammlung_entfernen($sammlungID): bool
    {
        // Sammlung wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function sammlung_erhalten($sammlungID): array
    {
        if (isset($sammlungID) and is_string($sammlungID)) {
            foreach ($this->sammlungen as $s) {
                if ($s[0] == htmlspecialchars($sammlungID)) {
                    return $s;
                }
            }
        }
        return [-1];
    }

    public function kommentar_anlegen($text, $gemaeldeID, $nutzerID): bool
    {
        // Kommentar wird hier nicht angelegt, da es eine schreibende Methode ist
        return true;
    }

    public function kommentar_entfernen($nutzerID, $kommentarID): bool
    {
        // Kommentar wird hier nicht entfernt, da es eine schreibende Methode ist
        return true;
    }

    public function kommentar_liken($nutzerID, $kommentarID): bool
    {
        // Kommentar wird hier nicht geliked, da es eine schreibende Methode ist
        return true;
    }

    public function kommentare_erhalten($gemaeldeID): array
    {
        $result = array();
        if (isset($gemaeldeID) and is_string($gemaeldeID)) {
            foreach ($this->kommentare as $k) {
                if ($k[1] == htmlspecialchars($gemaeldeID)) {
                    $result[] = $k;
                }
            }
        }
        return $result;
    }

    public function profil_erhalten($nutzerID): array
    {
        if (isset($nutzerID) and is_string($nutzerID)) {
            foreach ($this->users_profil as $profil) {
                if ($profil[0] == htmlspecialchars($nutzerID)) {
                    return $profil;
                }
            }
        }
        return [-1];
    }

    public function ausstellung_erhalten($suche, $filter): array
    {
        $suche_result = array();
        if (isset($suche) and is_string($suche)) {
            foreach ($this->gemaelde as $g) {
                if (str_contains($g[2], $suche)) {
                    $suche_result[] = $g;
                }
            }
        } else {
            $suche_result = $this->gemaelde;
        }
        if (isset($filter) and is_string($filter)) {
            if ($filter === "relevance") { //Nach beliebtesten sortieren
                for ($i = 0; $i < sizeof($suche_result); $i++) {
                    for ($j = $i + 1; $j < sizeof($suche_result); $j++) {
                        if ($suche_result[$i][9] < $suche_result[$j][9]) {
                            $temp = $suche_result[$i];
                            $suche_result[$i] = $suche_result[$j];
                            $suche_result[$j] = $temp;
                        }
                    }
                }
            }
        }

        $return_array = array(array(), array(), array(), array());
        $curr_reihe = 0;
        foreach ($suche_result as $gemaelde_result) {
            $return_array[$curr_reihe][] = $gemaelde_result;
            $curr_reihe = ($curr_reihe + 1) % 4;
        }

        return $return_array;
    }

    public function sammlungen_erhalten($suche, $filter): array
    {
        $suche_result = array();
        if (isset($suche) and is_string($suche)) {
            foreach ($this->sammlungen as $s) {
                if (str_contains($s[3], $suche)) {
                    $suche_result[] = $s;
                }
            }
        } else {
            $suche_result = $this->sammlungen;
        }
        if (isset($filter) and is_string($filter)) {
            if ($filter === "relevance") { //Nach beliebtesten sortieren
                for ($i = 0; $i < sizeof($suche_result); $i++) {
                    for ($j = $i + 1; $j < sizeof($suche_result); $j++) {
                        if ($suche_result[$i][7] < $suche_result[$j][7]) {
                            $temp = $suche_result[$i];
                            $suche_result[$i] = $suche_result[$j];
                            $suche_result[$j] = $temp;
                        }
                    }
                }
            }
        }

        $return_array = array(array(), array(), array(), array());
        $curr_reihe = 0;
        foreach ($suche_result as $sammlungen_result) {
            $return_array[$curr_reihe][] = $sammlungen_result;
            $curr_reihe = ($curr_reihe + 1) % 4;
        }

        return $return_array;
    }

}
