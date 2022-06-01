<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";

class NutzerDAODummyImpl implements NutzerDAO
{

    // [NutzerID, Email, Passwort, Nutzername]
    private $users = [
        [0, "test1@test.com", "test1!", "test1"],
        [1, "test2@test.com", "test2!", "test2"]
    ];

    // [users_NutzerID, Validierungstoken]
    private $valid_tokens = [
        [0, "mA23zbjdkENShbk9ezqNp5nQMpyrVb7m"],
        [1, "YRSPGgPjnDSuy7b5GuNFBEz9e4AAwaj7"]
    ];

    // [GemaeldeID, users_NutzerID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung (*/10), Hochladedatum, Aufrufe]
    // href: gemaelde.php?id=[GemaeldeID]
    // Datei: images/[GemaeldeID].jpg
    private $gemaelde = [
        [0, 0, "Stockbild0", "Stockkünstler0", "Beschreibung von Bild 0", "04.09.1900", "München, Deutschland", 8, "07.10.2021", 56],
        [1, 1, "Stockbild1", "Stockkünstler1", "Beschreibung von Bild 1", "05.10.1234", "Oldenburg, Deutschland", 9, "01.06.2022", 4],
        [2, 1, "Stockbild2", "Stockkünstler2", "Beschreibung von Bild 2", "06.11.1432", "Berlin, Deutschland", 4, "06.09.2022", 8]
    ];

    // [KommentarID, gemaelde_GemaeldeID, users_NutzerID, Likeanzahl, Text, Erstellungsdatum]
    private $kommentare = [
        [0, 0, 0, 274, "Dies ist ein Kommentar!", "05.10.2021"],
        [1, 0, 1, 346, "Dies ist auch ein Kommentar!!", "07.06.2022"],
        [2, 1, 2, 56, "Mein erster Kommentar.", "02.03.2022"]
    ];

    // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
    private $sammlungen = [
        [0, 1, [0, 2, 1], "Sammlung0", "Beschreibung von Bild 0", 3, "03.01.2021", 2234],
        [1, 1, [1, 0], "Sammlung1", "Beschreibung von Bild 1", 7, "06.04.2022", 34],
        [2, 0, [2, 0], "Sammlung2", "Beschreibung von Bild 2", 5, "02.03.2022", 8673]
    ];

    public function __construct()
    {
    }

    public function registrieren($nutzername, $email, $passwort): bool
    {
        // TODO: Nutzer wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function anmelden($email, $passwort): array
    {
        if (isset($email) and is_string($email) and isset($passwort) and is_string($passwort)) {
            foreach ($this->users as $user) {
                if ($user[1] === htmlentities($email) and $user[2] === htmlentities($passwort)) {
                    /* TODO: Token in Datenbank speichern und an User senden, wenn Datenbank vorhanden ist.
                    $gentoken = openssl_random_pseudo_bytes(16); //Generiere einen zufälligen Text.
                    $gentoken = bin2hex($token); //Konvertiere die Binäre-Daten zu Hexadezimal-Daten.
                    */
                    foreach ($this->valid_tokens as $token) {
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
        // TODO: Token wird erst aus der Tabelle für valide Token gelöscht, wenn Datenbank vorhanden ist.
        return true;
    }

    public function ausstellung_suche($input): bool
    {

        return true;
    }

    public function sammlungen_suche($input): bool
    {

        return true;
    }

    public function gemaelde_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): int
    {
        //TODO: Gemälde wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_editieren($gemaelde_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool
    {
        //TODO: Gemälde wird erst editiert, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_entfernen($gemaelde_id): bool
    {
        //TODO: Gemälde wird erst entfernt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_erhalten($id): array
    {
        if (isset($id) and is_string($id)) {
            foreach ($this->gemaelde as $g) {
                if ($g[0] == htmlentities($id)) {
                    return $g;
                }
            }
        }
        return array();
    }

    public function sammlung_anlegen($gemaelde, $titel, $beschreibung): int
    {
        //TODO: Sammlung wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function sammlung_editieren($sammlung_id, $gemaelde, $titel, $beschreibung): bool
    {
        //TODO: Sammlung wird erst editiert, wenn Datenbank vorhanden ist.
        return true;
    }

    public function sammlung_entfernen($sammlung_id): bool
    {
        //TODO: Sammlung wird erst entfernt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function sammlung_erhalten($id): array
    {
        if (isset($id) and is_string($id)) {
            foreach ($this->sammlungen as $s) {
                if ($s[0] == htmlentities($id)) {
                    return $s;
                }
            }
        }
        return array();
    }

    public function kommentar_anlegen($text, $gemaelde_id, $author_id): bool
    {
        /*TODO: Kommentar wird erst angelegt, wenn Datenbank vorhanden ist.
        if (isset($author_id) and is_string($author_id) and isset($text) and is_string($text) and isset($gemaelde_id) and is_string($gemaelde_id)) {
            $this->comments[] = ["gemaelde_id" => $gemaelde_id, "kommentar_id" => count($this->comments), "text" => $text, "author" => $author_id, "likes" => 0, "userLikes" => array($author_id)];
        }*/
        return true;
    }

    public function kommentar_entfernen($user_id, $kommentar_id): bool
    {
        /*TODO: Kommentar wird erst editiert, wenn Datenbank vorhanden ist.
        if (isset($user_id) and is_string($user_id) and isset($kommentar_id) and is_string($kommentar_id) and $this->comments[$kommentar_id]["author"] == $user_id) {
            unset($this->comments[$kommentar_id]);
        }*/
        return true;
    }

    public function kommentar_liken($userID, $kommentar_id): bool
    {
        /*TODO: Kommentar wird erst geliked, wenn Datenbank vorhanden ist.
        //Nutzer kann nicht mehr liken, weil er den Kommentar erstellt, oder bereits geliked hat
        if (in_array($userID, $this->comments[$kommentar_id]["userLikes"])) {
            return false;
        }
        $this->comments[$kommentar_id]["likes"]++;
        $this->comments[$kommentar_id]["userLikes"][] = $userID;*/
        return true;
    }

    public function kommentar_erhalten($id): array
    {
        $result = array();
        if (isset($id) and is_string($id)) {
            foreach ($this->kommentare as $k) {
                if ($k[1] == htmlentities($id)) {
                    $result[] = $k;
                }
            }
        }
        return $result;
    }
}
