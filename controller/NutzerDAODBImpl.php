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

                    $name1 = "test1";
                    $hash1 = password_hash("test1!", PASSWORD_DEFAULT);
                    $name2 = "test2";
                    $hash2 = password_hash("test2!", PASSWORD_DEFAULT);
                    $setPasswordSQL = "UPDATE Anbieter SET Passwort = :passwort WHERE Nutzername = :nutzername;";
                    $setPasswordCMD = $this->db->prepare($setPasswordSQL);
                    $setPasswordCMD->bindParam(':passwort', $hash1);
                    $setPasswordCMD->bindParam(':nutzername', $name1);
                    $setPasswordCMD->execute();
                    $setPasswordCMD->bindParam(':passwort', $hash2);
                    $setPasswordCMD->bindParam(':nutzername', $name2);
                    $setPasswordCMD->execute();
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

            $deleteTokenSQL = "DELETE FROM Tokens WHERE AnbieterID = :id AND Tokennummer = :token;";
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

            $insertGemaeldeSQL = "INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe)
                                    VALUES (:anbieter, :titel, :artist, :beschreibung, :date, :location, 0, :hochladedatum, 0);";
            $insertGemaeldeCMD = $this->db->prepare($insertGemaeldeSQL);
            $creation_date = date("d.m.Y", strtotime($date));
            $upload_date = date("d.m.Y");
            $insertGemaeldeCMD->bindParam(":anbieter", $AnbieterID);
            $insertGemaeldeCMD->bindParam(":titel", $titel);
            $insertGemaeldeCMD->bindParam(":artist", $artist);
            $insertGemaeldeCMD->bindParam(":beschreibung", $beschreibung);
            $insertGemaeldeCMD->bindParam(":date", $creation_date);
            $insertGemaeldeCMD->bindParam(":location", $location);
            $insertGemaeldeCMD->bindParam(":hochladedatum", $upload_date);

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

            $checkGemaeldeIDSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $checkGemaeldeIDCMD = $this->db->prepare($checkGemaeldeIDSQL);
            $checkGemaeldeIDCMD->bindParam(":GemaeldeID", $gemaeldeID);
            $checkGemaeldeIDCMD->execute();
            $result = $checkGemaeldeIDCMD->fetchObject();
            if (!isset($result->GemaeldeID)) {
                return false; //GemaeldeID existiert nicht
            }

            $editGemaeldeSQL = "UPDATE Gemaelde SET Beschreibung = :beschreibung, Erstellungsdatum = :erstellungsdatum, Ort = :ort WHERE GemaeldeID = :id;";
            $editGemaeldeCMD = $this->db->prepare($editGemaeldeSQL);
            $editGemaeldeCMD->bindParam(':id', $gemaeldeID);
            $editGemaeldeCMD->bindParam(':beschreibung', $beschreibung);
            $editGemaeldeCMD->bindParam(':erstellungsdatum', $erstellungsdatum);
            $editGemaeldeCMD->bindParam(':ort', $ort);

            $editGemaeldeCMD->execute();

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
        try {
            $this->db->beginTransaction();

            $checkGemaeldeIDSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $checkGemaeldeIDCMD = $this->db->prepare($checkGemaeldeIDSQL);
            $checkGemaeldeIDCMD->bindParam(":GemaeldeID", $gemaeldeID);
            $checkGemaeldeIDCMD->execute();
            $result = $checkGemaeldeIDCMD->fetchObject();
            if (!isset($result->GemaeldeID)) {
                return false; //GemaeldeID existiert nicht
            }

            $deleteGemaeldeSQL = "DELETE FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $deleteGemaeldeCMD = $this->db->prepare($deleteGemaeldeSQL);
            $deleteGemaeldeCMD->bindParam(":GemaeldeID", $gemaeldeID);

            $deleteGemaeldeCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_erhalten($gemaeldeID): array
    {
        try {
            $this->db->beginTransaction();

            $checkGemaeldeIDSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $checkGemaeldeIDCMD = $this->db->prepare($checkGemaeldeIDSQL);
            $checkGemaeldeIDCMD->bindParam(":GemaeldeID", $gemaeldeID);
            $checkGemaeldeIDCMD->execute();
            $result = $checkGemaeldeIDCMD->fetchObject();
            if (!isset($result->GemaeldeID)) {
                return array(-1); //GemaeldeID existiert nicht
            }

            $getGemaeldeSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $getGemaeldeCMD = $this->db->prepare($getGemaeldeSQL);
            $getGemaeldeCMD->bindParam(":GemaeldeID", $gemaeldeID);
            $getGemaeldeCMD->execute();
            $result = $getGemaeldeCMD->fetchObject();
            $GemaeldeID = $result->GemaeldeID;
            $AnbieterID = $result->AnbieterID;
            $Titel = $result->Titel;
            $Kuenstler = $result->Kuenstler;
            $Beschreibung = $result->Beschreibung;
            $Erstellungsdatum = $result->Erstellungsdatum;
            $Ort = $result->Ort;
            $Bewertung = $result->Bewertung;
            $Hochladedatum = $result->Hochladedatum;
            $Aufrufe = $result->Aufrufe;

            $this->db->commit();
            return array($GemaeldeID, $AnbieterID, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort, $Bewertung, $Hochladedatum, $Aufrufe);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function sammlung_anlegen($id, $auswahl, $titel, $beschreibung): bool
    {
        // $auswahl ist ein array an GemaeldeID's
        if (sizeof($auswahl) < 1) {
            return false;
        }
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

            $getHighestSammlungIDSQL = "SELECT MAX(SammlungID) FROM Sammlung;";
            $getHighestSammlungIDCMD = $this->db->prepare($checkAnbieterIDSQL);
            $getHighestSammlungIDCMD->execute();
            $result = $getHighestSammlungIDCMD->fetchObject();
            $NewSammlungID = 0;
            if (isset($result->SammlungID)) {
                $NewSammlungID = ($result->SammlungID) + 1;
            }

            $insertSammlungSQL = "INSERT INTO Sammlung (SammlungID, AnbieterID, Titel, Beschreibung, Erstellungsdatum) 
                                    VALUES (:SammlungID, :AnbieterID, :titel, :beschreibung, :hochladedatum);";
            $insertSammlungCMD = $this->db->prepare($insertSammlungSQL);
            $insertSammlungCMD->bindParam(":SammlungID", $NewSammlungID);
            $insertSammlungCMD->bindParam(":AnbieterID", $AnbieterID);
            $insertSammlungCMD->bindParam(":titel", $titel);
            $insertSammlungCMD->bindParam(":beschreibung", $beschreibung);
            $hochladedatum = date("d.m.Y");
            $insertSammlungCMD->bindParam(":hochladedatum", $hochladedatum);
            $insertSammlungCMD->execute();

            foreach ($auswahl as $GemaeldeID) {
                $insertGehoertZuSQL = "INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
                                    VALUES (:GemaeldeID, :SammlungID);";
                $insertGehoertZuCMD = $this->db->prepare($insertGehoertZuSQL);
                $insertGehoertZuCMD->bindParam(":GemaeldeID", $GemaeldeID);
                $insertGehoertZuCMD->bindParam(":SammlungID", $NewSammlungID);
                $insertSammlungCMD->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function sammlung_editieren($sammlungID, $titel, $beschreibung): bool
    {
        try {
            $this->db->beginTransaction();

            $checkSammlungIDSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $checkSammlungIDCMD = $this->db->prepare($checkSammlungIDSQL);
            $checkSammlungIDCMD->bindParam(":SammlungID", $sammlungID);
            $checkSammlungIDCMD->execute();
            $result = $checkSammlungIDCMD->fetchObject();
            if (!isset($result->SammlungID)) {
                return false; //SammlungID existiert nicht
            }

            $editSammlungSQL = "UPDATE Sammlung SET Titel = :titel, Beschreibung = :beschreibung WHERE SammlungID = :id";
            $editSammlungCMD = $this->db->prepare($editSammlungSQL);
            $editSammlungCMD->bindParam(":titel", $titel);
            $editSammlungCMD->bindParam(":beschreibung", $beschreibung);
            $editSammlungCMD->bindParam(":id", $sammlungID);
            $editSammlungCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function sammlung_entfernen($sammlungID): bool
    {
        try {
            $this->db->beginTransaction();

            $checkSammlungIDSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $checkSammlungIDCMD = $this->db->prepare($checkSammlungIDSQL);
            $checkSammlungIDCMD->bindParam(":SammlungID", $sammlungID);
            $checkSammlungIDCMD->execute();
            $result = $checkSammlungIDCMD->fetchObject();
            if (!isset($result->SammlungID)) {
                return false; //SammlungID existiert nicht
            }

            $deleteSammlungSQL = "DELETE FROM Sammlung WHERE SammlungID = :SammlungID;";
            $deleteSammlungCMD = $this->db->prepare($deleteSammlungSQL);
            $deleteSammlungCMD->bindParam(":SammlungID", $sammlungID);

            $deleteSammlungCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function sammlung_erhalten($sammlungID): array
    {
        try {
            $this->db->beginTransaction();

            $checkSammlungIDSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $checkSammlungIDCMD = $this->db->prepare($checkSammlungIDSQL);
            $checkSammlungIDCMD->bindParam(":SammlungID", $sammlungID);
            $checkSammlungIDCMD->execute();
            $result = $checkSammlungIDCMD->fetchObject();
            if (!isset($result->SammlungID)) {
                return array(-1); //SammlungID existiert nicht
            }

            $getSammlungSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $getSammlungCMD = $this->db->prepare($getSammlungSQL);
            $getSammlungCMD->bindParam(":SammlungID", $sammlungID);
            $getSammlungCMD->execute();
            $result = $getSammlungCMD->fetchObject();
            $SammlungID = $result->SammlungID;

            $this->db->commit();


            // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]
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
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }

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

            $sql = 'DELETE FROM Kommentar WHERE KommentarID = :id;';
            $kommando = $this->db->prepare($sql);
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
            $sql = 'UPDATE Kommentar SET Likeanzahl = Likeanzahl + 1 WHERE KommentarID = :id;';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam( ':id', $kommentarID );
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch ( Exception $ex ) {
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentare_erhalten($gemaeldeID): array
    {
        /*TODO prepare*/
        $sql = "SELECT * FROM Kommentar WHERE GemaeldeID = $gemaeldeID;";
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
                $sql = "SELECT * FROM Gemaelde WHERE Titel LIKE '%$suche%' ORDER BY Bewertung;";
            }else {
                $sql = "SELECT * FROM Gemaelde WHERE Titel LIKE '%$suche%'";
            }
        } else if (isset($filter) and is_string($filter)) {
            if ($filter == "relevance") { //Nach beliebtesten sortieren
                $sql = "SELECT * FROM Gemaelde ORDER BY Bewertung;";
            }
        }else {
            $sql = "SELECT * FROM Gemaelde;";
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
