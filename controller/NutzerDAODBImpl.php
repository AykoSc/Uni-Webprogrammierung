<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";
include $abs_path . "/controller/DBTools.php";

class NutzerDAODBImpl implements NutzerDAO
{
    private static ?NutzerDAODBImpl $instance = null;
    private PDO $db;

    public static function getInstance(): NutzerDAODBImpl
    {
        if (self::$instance == null) {
            self::$instance = new NutzerDAODBImpl();
        }

        return self::$instance;
    }

    private function __construct()
    {
        //Datenbankverbindung
        try {
            $user = "root";
            $pw = null;
            $dsn = "sqlite:database.db";
            // Nur bei MySQL: $dsn = "mysql:dbname=website;host=localhost";
            $this->db = new PDO($dsn, $user, $pw);
            // Nur bei MySQL: $this->db->exec("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
            try {
                $this->db->beginTransaction();

                $this->db->exec(DBTools::CREATE_TABLES);

                $checkEmptySQL = $this->db->query("SELECT * FROM Gemaelde;");
                $result = $checkEmptySQL->fetchObject();
                if (!isset($result->GemaeldeID)) {
                    $this->db->exec(DBTools::INSERT_DATA); // Daten werden eingefügt, wenn Datenbank leer ist
                }

                $this->db->commit();
            } catch (Exception $ex) {
                print_r($ex);
                $db->rollBack();
            }
        } catch (Exception $ex) {
            print_r($ex);
        }
    }

    public function registrieren($nutzername, $email, $passwort): bool
    {
        try {
            $this->db->beginTransaction();

            $checkEmailSQL = "SELECT * FROM Anbieter WHERE Email = :email;";
            $checkEmailCMD = $this->db->prepare($checkEmailSQL);
            $checkEmailCMD->bindParam(":email", $email);
            $checkEmailCMD->execute();
            $result = $checkEmailCMD->fetchObject();
            if (isset($result->Email)) {
                return false; //Email existiert bereits
            }

            $checkUsernameSQL = "SELECT * FROM Anbieter WHERE Nutzername = :nutzername;";
            $checkUsernameCMD = $this->db->prepare($checkUsernameSQL);
            $checkUsernameCMD->bindParam(":nutzername", $nutzername);
            $checkUsernameCMD->execute();
            $result = $checkUsernameCMD->fetchObject();
            if (isset($result->Nutzername)) {
                return false; //Nutzername existiert bereits
            }

            $hash = password_hash($passwort, PASSWORD_DEFAULT);

            $insertAnbieterSQL = "INSERT INTO Anbieter (Nutzername, Email, Passwort) VALUES (:nutzername, :email, :passwort);";
            $insertAnbieterCMD = $this->db->prepare($insertAnbieterSQL);
            $insertAnbieterCMD->bindParam(":nutzername", $nutzername);
            $insertAnbieterCMD->bindParam(":email", $email);
            $insertAnbieterCMD->bindParam(":passwort", $hash);
            $insertAnbieterCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function anmelden($email, $passwort): array
    {
        try {
            $this->db->beginTransaction();

            $findAnbieterSQL = "SELECT * FROM Anbieter WHERE Email = :email;";
            $findAnbieterCMD = $this->db->prepare($findAnbieterSQL);
            $findAnbieterCMD->bindParam(":email", $email);
            $findAnbieterCMD->execute();
            $result = $findAnbieterCMD->fetchObject();
            if (!isset($result->AnbieterID) or !isset($result->Email) or !isset($result->Passwort)) {
                return array(-1, ""); // Anmeldung fehlgeschlagen (Anbieter existiert nicht)
            }

            $valid = password_verify($passwort, $result->Passwort);
            if ($valid) {
                $id = $result->AnbieterID;
                $token = openssl_random_pseudo_bytes(16); //Generiere einen zufälligen Text.
                $token = bin2hex($token); //Konvertiere die Binäre-Daten zu Hexadezimal-Daten.

                $insertTokenSQL = "INSERT INTO Tokens (AnbieterID, Tokennummer) VALUES (:id, :token);";
                $insertTokenCMD = $this->db->prepare($insertTokenSQL);
                $insertTokenCMD->bindParam(":id", $id);
                $insertTokenCMD->bindParam(":token", $token);
                $insertTokenCMD->execute();

                $this->db->commit();
                return array($id, $token);
            } else {
                $this->db->commit();
                return array(-1, ""); // Anmeldung fehlgeschlagen (Passwort falsch)
            }
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1, "");
        }

    }

    public function abmelden($nutzerID, $nutzerToken): bool
    {
        try {
            $this->db->beginTransaction();

            $deleteTokenSQL = "DELETE FROM Tokens WHERE AnbieterID = :id AND Tokennummer = :token);";
            $deleteTokenCMD = $this->db->prepare($deleteTokenSQL);
            $deleteTokenCMD->bindParam(":id", $nutzerID);
            $deleteTokenCMD->bindParam(":token", $nutzerToken);
            $deleteTokenCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kontakt_aufnehmen($email, $kommentar): bool
    {
        try {
            $this->db->beginTransaction();

            $insertKontaktSQL = "INSERT INTO Kontakt (Kommentar, Email) VALUES (:kommentar, :email);";
            $insertKontaktCMD = $this->db->prepare($insertKontaktSQL);
            $insertKontaktCMD->bindParam(":kommentar", $kommentar);
            $insertKontaktCMD->bindParam(":email", $email);
            $insertKontaktCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_anlegen($AnbieterID, $file, $titel, $beschreibung, $artist, $date, $location): bool
    {
        try {
            $this->db->beginTransaction();

            $checkAnbieterIDSQL = "SELECT * FROM Anbieter WHERE AnbieterID = :AnbieterID;";
            $checkAnbieterIDCMD = $this->db->prepare($checkAnbieterIDSQL);
            $checkAnbieterIDCMD->bindParam(":AnbieterID", $AnbieterID);
            $checkAnbieterIDCMD->execute();
            $result = $checkAnbieterIDCMD->fetchObject();
            if (!isset($result->AnbieterID)) {
                return false; //AnbieterID existiert nicht
            }

            $insertGemaeldeSQL = "INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Hochladedatum)
                                    VALUES (:AnbieterID, :titel, :artist, :beschreibung, :date, :location, :hochladedatum);";
            $insertGemaeldeCMD = $this->db->prepare($insertGemaeldeSQL);
            $insertGemaeldeCMD->bindParam(":AnbieterID", $AnbieterID);
            $insertGemaeldeCMD->bindParam(":titel", $titel);
            $insertGemaeldeCMD->bindParam(":artist", $artist);
            $insertGemaeldeCMD->bindParam(":beschreibung", $beschreibung);
            $insertGemaeldeCMD->bindParam(":date", $date);
            $insertGemaeldeCMD->bindParam(":location", $location);
            $hochladedatum = date("d.m.Y");
            $insertGemaeldeCMD->bindParam(":hochladedatum", $hochladedatum);

            $insertGemaeldeCMD->execute();

            //TODO file unter images-Ordner abspeichern

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
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
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam(':id', $gemaeldeID);
            $kommando->bindParam(':beschreibung', $beschreibung);
            $kommando->bindParam(':erstellungsdatum', $erstellungsdatum);
            $kommando->bindParam(':ort', $ort);
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
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
