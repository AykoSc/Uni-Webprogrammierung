<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";
include $abs_path . "/controller/DatabaseTools.php";

class NutzerDAODbImpl implements NutzerDAO
{
    private static ?NutzerDAODbImpl $instance = null;
    private PDO $db;

    private function __construct()
    {
        /*TODO Datenbank weitermachen und Fehlerbehandlung*/
        //Datenbankverbindung
        try {
            $user = "root";
            $pw = null;
            $dsn = "sqlite:database.db";
            $this->db = new PDO($dsn, $user, $pw);
            $this->db->exec(DatabaseTools::CREATE_DATABASE);
        } catch (PDOException $e) {
            echo "Fehler: " . $e->getMessage();
        }
    }

    public static function getInstance(): NutzerDAODbImpl
    {
        if (self::$instance == null) {
            self::$instance = new NutzerDAODbImpl();
        }

        return self::$instance;
    }

    public function registrieren($nutzername, $email, $passwort): bool
    {
        // TODO: Transaktion erstellen
        $checkIfExists = $this->db->prepare("SELECT COUNT(:Email) FROM Anbieter WHERE Email = :Email");
        $checkIfExists->bindValue("Email", $email);
        if ($checkIfExists->execute() > 0) {
            return false;
        }

        $this->db->exec("BEGIN TRANSACTION;");
        $this->db->exec("ROLLBACK;");
        $this->db->exec("COMMIT;");
        return true;
    }

    public function anmelden($email, $passwort): array
    {
        return array();
    }

    public function abmelden($nutzerID, $nutzerToken): bool
    {
        // TODO: Token wird erst aus der Tabelle für valide Token gelöscht, wenn Datenbank vorhanden ist.
        return true;
    }

    public function kontakt_aufnehmen($email, $kommentar): bool
    {
        //TODO: Kontaktaufnahme wird erst gespeichert, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_anlegen($id, $file, $titel, $beschreibung, $artist, $date, $location): bool
    {
        //TODO: Gemälde wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    //TODO gegebenenfalls möglichkeit hinzufügen titel zu editieren
    public function gemaelde_editieren($gemaeldeID, $beschreibung, $erstellungsdatum, $ort): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = 'UPDATE Gemaelde
                    SET Beschreibung = :beschreibung, 
                        Erstellungsdatum = :erstellungsdatum, 
                        Ort = :ort 
                    WHERE GemaeldeID = :id';
            $kommando = $this->db->prepare( $sql );
            $kommando->bindParam( ':id', $gemaeldeID );
            $kommando->bindParam( ':beschreibung', $beschreibung );
            $kommando->bindParam( ':erstellungsdatum', $erstellungsdatum );
            $kommando->bindParam( ':ort', $ort );
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch ( Exception $ex ) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_entfernen($gemaeldeID): bool
    {
        //TODO: Gemälde wird erst entfernt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function gemaelde_erhalten($gemaeldeID): array
    {
        $sql = "SELECT * FROM Gemaelde
                  WHERE GemaeldeID = :id;";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(":id" => $gemaeldeID));

        $stmt->bindColumn(1, $GemaeldeID);
        //$stmt->bindColumn(2, $data, PDO::PARAM_LOB); bis 2021
        $stmt->bindColumn(2, $AnbieterID);
        $stmt->bindColumn(3, $Titel);
        $stmt->bindColumn(4, $Kuenstler);
        $stmt->bindColumn(5, $Beschreibung);
        $stmt->bindColumn(6, $Erstellungsdatum);
        $stmt->bindColumn(7, $Ort);
        $stmt->bindColumn(8, $Bewertung);
        $stmt->bindColumn(9, $Hochladedatum);
        $stmt->bindColumn(10, $Aufrufe);

        $stmt->fetch(PDO::FETCH_BOUND);

        return array($GemaeldeID, $AnbieterID, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort, $Bewertung, $Hochladedatum, $Aufrufe);
    }

    public function sammlung_anlegen($id, $auswahl, $titel, $beschreibung): bool
    {
        //TODO: Sammlung wird erst angelegt, wenn Datenbank vorhanden ist.
        return true;
    }

    public function sammlung_editieren($sammlungID, $gemaelde, $titel, $beschreibung): bool
    {
        //TODO: Sammlung wird erst editiert, wenn Datenbank vorhanden ist.
        return true;
    }

    public function sammlung_entfernen($sammlungID): bool
    {
        //TODO: Sammlung wird erst entfernt, wenn Datenbank vorhanden ist.
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
        try {
        $this->db->beginTransaction();

        $sql = 'INSERT INTO Kommentar  (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
                VALUES (:gemaeldeID, :anbieterID, 0, :text, current_date) ;';
        $kommando = $this->db->prepare( $sql );
        $kommando->bindParam( ':gemaeldeID', $gemaeldeID );
        $kommando->bindParam( ':anbieterID', $nutzerID );
        $kommando->bindParam( ':text', $text );
        $kommando->execute();

        $this->db->commit();
        return true;
        } catch ( Exception $ex ) {
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_entfernen($nutzerID, $kommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = 'DELETE FROM Kommentar WHERE KommentarID = :id';
            $kommando = $this->db->prepare( $sql );
            $kommando->bindParam( ':id', $kommentarID );
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch ( Exception $ex ) {
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_liken($nutzerID, $kommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            /*TODO Merken welcher User welches Bild geliked hat, so noch mehrfaches Liken möglich*/
            $sql = 'UPDATE Kommentar SET Likeanzahl = Likeanzahl + 1 WHERE KommentarID = :id';
            $kommando = $this->db->prepare( $sql );
            $kommando->bindParam( ':id', $kommentarID );
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch ( Exception $ex ) {
            $this->db->rollBack();
            return false;
        }
        return true;
    }

    public function kommentare_erhalten($gemaeldeID): array
    {
        /*TODO prepare*/
        $sql = "SELECT * FROM Kommentar WHERE GemaeldeID = $gemaeldeID";
        $ergebnis = $this->db->query($sql);
        $kommentare = array();
        foreach ($ergebnis as $zeile) {
            $kommentare[] = array($zeile['KommentarID'], $zeile['GemaeldeID'], $zeile['AnbieterID'], $zeile['Likeanzahl'], $zeile['Textinhalt'], $zeile['Erstellungsdatum']);
        }
        return $kommentare;
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
        $sql = null;
        if (isset($suche) and is_string($suche) and isset($filter) and is_string($filter)) {
            if($filter == "relevance"){
                $sql = "SELECT * FROM Gemaelde WHERE Titel LIKE '%$suche%' ORDER BY Bewertung";
            }else {
                $sql = "SELECT * FROM Gemaelde WHERE Titel LIKE '%$suche%'";
            }
        } else if (isset($filter) and is_string($filter)) {
            if ($filter == "relevance") { //Nach beliebtesten sortieren
                $sql = "SELECT * FROM Gemaelde ORDER BY Bewertung";
            }
        }else {
            $sql = "SELECT * FROM Gemaelde";
        }
        $ergebnis = $this->db->query($sql);
        foreach ($ergebnis as $zeile) {
            $suche_result[] = array($zeile['GemaeldeID'], $zeile['AnbieterID'], $zeile['Titel'], $zeile['Kuenstler'], $zeile['Beschreibung'], $zeile['Erstellungsdatum'], $zeile['Ort'], $zeile['Bewertung'], $zeile['Hochladedatum'], $zeile['Aufrufe']);
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
        //TODO ausstellung_erhalten neu copy pasten mit anderen index-werten
        return array(-1);
    }

}
