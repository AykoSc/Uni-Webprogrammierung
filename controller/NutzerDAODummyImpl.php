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
        ["0", "gemaelde.php", "images/picture1.jpg", "Stockbild1"],
        ["1", "gemaelde.php", "images/picture2.jpg", "Stockbild2"],
        ["2", "gemaelde.php", "images/picture3.jpg", "Stockbild3"],
        ["3", "gemaelde.php", "images/start.jpg", "Startbild"]
    ];

    // [kommentarID, text, author, likes, userLikes]
    private array $comments= [
        ["gemaelde_id" => "3", "kommentar_id" => "0","text" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
        sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, 
        sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.", "author" => "test1", "likes" => 0, "userLikes" => array(0)],
        ["gemaelde_id" => "3", "kommentar_id" => "1","text" => "Lorem ipsum dolor sit amet, consetetur sadipscing elitr, 
        sed diam nonumy eirmod tempor invidunt ut.", "author" => "test2", "likes" => 0, "userLikes" => array(1)]
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

    public function kommentar_anlegen($inhalt, $sammlung_id, $gemaelde_id): bool
    {
        //TODO
        return true;
    }

    public function kommentar_entfernen($gemaelde_id): bool
    {
        //TODO
        return true;
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
            $this -> comments[] = ["gemaelde_id" => $gemaelde_id, "kommentar_id" => count($this -> comments), "text" => $text, "author" => $this->users[$author_id][3], "likes" => 0, "userLikes" => array($author_id)];
            return true;
        }
        return false;
    }

    public function kommentar_liken($userID, $gemaelde_id): bool
    {
        //Nutzer hat bereits geliked
        if(in_array($this->comments[$gemaelde_id]["userLikes"], [$userID] )){
            return false;
        }
        $this->comments[$gemaelde_id]["likes"]++;
        $this->comments[$gemaelde_id]["userLikes"][] = $userID;
        return true;
    }

    public function kommentar_getAll(): array
    {
        return $this->comments;
    }

}
