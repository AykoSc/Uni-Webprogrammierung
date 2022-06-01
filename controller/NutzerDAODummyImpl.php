<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";

class NutzerDAODummyImpl implements NutzerDAO
{

    // [NutzerID, Email, Passwort, Nutzername, Token]
    private $users = [
        ["0", "test1@test.com", "kS5T6Kqt5cqAMNZu", "test1", "mA23zbjdkENShbk9ezqNp5nQMpyrVb7m"],
        ["1", "test2@test.com", "prHTJRZEjBW9KRZE", "test2", "YRSPGgPjnDSuy7b5GuNFBEz9e4AAwaj7"]
    ];

    // [GemaeldeID, href, Bilddatei, Titel]
    private $gemaelde = [
        ["gemaelde_id" => 0, "href" => "gemaelde.php", "bilddatei"  => "images/picture1.jpg", "titel" => "Stockbild1",
            "beschreibung" => "aliquyam.", "datum" => "02.02.2022", "ort" => "Am Main", "ersteller" => "Mark"],

        ["gemaelde_id" => 1, "href" => "gemaelde.php", "bilddatei"  => "images/picture2.jpg", "titel" => "Stockbild2",
            "beschreibung" => "Lorem", "datum" => "02.02.2022", "ort" => "Am Main", "ersteller" => "Mark"],
        ["gemaelde_id" => 2, "href" => "gemaelde.php", "bilddatei"  => "images/picture3.jpg", "titel" => "Stockbild3",
            "beschreibung" => "Lorem", "datum" => "02.02.2022", "ort" => "Am Main", "ersteller" => "Mark"],

        ["gemaelde_id" => 3, "href" => "gemaelde.php", "bilddatei"  => "images/start.jpg", "titel" => "Start",
            "beschreibung" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut 
            labore et dolore magna aliquyam.", "datum" => "02.02.2022", "ort" => "Am Main", "ersteller" => "Mark"]
    ];

    // [kommentarID, text, author, likes, userLikes]
    private array $comments= [
        ["gemaelde_id" => "3", "kommentar_id" => "0","text" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.", "author" => "0", "likes" => 0, "userLikes" => array(0)],
        ["gemaelde_id" => "3", "kommentar_id" => "1","text" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
        sed diam nonumy eirmod tempor invidunt ut.", "author" => "1", "likes" => 0, "userLikes" => array(1)]
    ];

    public function __construct()
    {
    }

    public function registrieren($nutzername, $email, $passwort): bool
    {
        //TODO Nutzer wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function anmelden($email, $passwort): array
    {
        if (isset($email) and is_string($email) and isset($passwort) and is_string($passwort)) {
            foreach ($this->users as $user) {
                if ($user[1] == htmlentities($email) and $user[2] == htmlentities($passwort)) {
                    return array($user[0], $user[4]); // Anmeldung erfolgreich
                }
            }
        }
        return array(-1, ""); // Anmeldung fehlgeschlagen
    }

    public function gemaelde_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool
    {
        //TODO Nutzer wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_editieren($gemaelde_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool
    {
        //TODO
        return true;
    }

    public function gemaelde_entfernen($gemaelde_id): bool
    {
        //TODO
        return true;
    }

    public function gemaelde_getByID($gemaelde_id): array
    {
        //funktioniert weil index = id, bei datenbank dann richtig
        return $this-> gemaelde[$gemaelde_id];
    }

    public function kommentar_anlegen($inhalt, $sammlung_id, $gemaelde_id): bool
    {
        //TODO
        return true;
    }

    public function kommentar_entfernen($user_id, $kommentar_id): bool
    {
        if(isset($user_id) and is_string($user_id) and isset($kommentar_id) and is_string($kommentar_id) and $this->comments[$kommentar_id]["author"] == $user_id){
                unset($this -> comments[$kommentar_id]);
                return true;
        }
        return false;
    }

    public function ausstellung_suche($input): bool
    {
        //TODO
        return true;
    }

    public function sammlungen_suche($input): bool
    {
        //TODO
        return true;
    }

    public function kommentar_schreiben($text, $gemaelde_id, $author_id): bool
    {
        if (isset($author_id) and is_string($author_id) and isset($text) and is_string($text) and isset($gemaelde_id) and is_string($gemaelde_id)) {
            $this -> comments[] = ["gemaelde_id" => $gemaelde_id, "kommentar_id" => count($this -> comments), "text" => $text, "author" => $author_id, "likes" => 0, "userLikes" => array($author_id)];
            return true;
        }
        return false;
    }

    public function kommentar_liken($userID, $kommentar_id): bool
    {
        //Nutzer kann nicht mehr liken, weil er den Kommentar erstellt, oder bereits geliked hat
        if(in_array($userID, $this->comments[$kommentar_id]["userLikes"] )){
            return false;
        }
        $this->comments[$kommentar_id]["likes"]++;
        $this->comments[$kommentar_id]["userLikes"][] = $userID;
        return true;
    }

    public function kommentar_getAll($gemaelde_id): array
    {
        $erg = array();
        foreach ($this->comments as $comment) {
            if ($comment["gemaelde_id"] == htmlentities($gemaelde_id)) {
                $erg[] = $comment;
            }
        }
        return $erg;
    }

    public function nutzer_getNameByID($id): String
    {
        //funktioniert weil index = id, bei datenbank dann richtig
        return $this-> users[$id][3];
    }

}
