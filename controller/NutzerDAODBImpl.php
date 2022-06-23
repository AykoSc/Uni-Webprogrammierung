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

    /**
     * @implNote Hilfsmethoden zur Überprüfung bestimmter Dinge, um Redundanzen zu vermeiden.
     *          Methoden nur innerhalb einer Transaktion benutzen!
     */
    private function anbieterCheck($AnbieterID, $token): bool
    {
        $checkAnbieterSQL = "SELECT * FROM Tokens WHERE AnbieterID = :AnbieterID AND Tokennummer = :token;";
        $checkAnbieterCMD = $this->db->prepare($checkAnbieterSQL);
        $checkAnbieterCMD->bindParam(":AnbieterID", $AnbieterID);
        $checkAnbieterCMD->bindParam(":token", $token);
        $checkAnbieterCMD->execute();
        $result = $checkAnbieterCMD->fetchObject();
        if (!isset($result->AnbieterID)) {
            $this->db->rollBack();
            return false; // Anbieter ist nicht eingeloggt oder existiert nicht
        }
        return true;
    }

    private function gemaeldeCheck($GemaeldeID): bool
    {
        $checkGemaeldeIDSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
        $checkGemaeldeIDCMD = $this->db->prepare($checkGemaeldeIDSQL);
        $checkGemaeldeIDCMD->bindParam(":GemaeldeID", $GemaeldeID);
        $checkGemaeldeIDCMD->execute();
        $result = $checkGemaeldeIDCMD->fetchObject();
        if (!isset($result->GemaeldeID)) {
            $this->db->rollBack();
            return false; //Gemaelde existiert nicht
        }
        return true;
    }

    private function kommentarCheck($KommentarID): bool
    {
        $checkKommentarIDSQL = "SELECT * FROM Kommentar WHERE KommentarID = :KommentarID;";
        $checkKommentarIDCMD = $this->db->prepare($checkKommentarIDSQL);
        $checkKommentarIDCMD->bindParam(":KommentarID", $KommentarID);
        $checkKommentarIDCMD->execute();
        $result = $checkKommentarIDCMD->fetchObject();
        if (!isset($result->KommentarID)) {
            $this->db->rollBack();
            return false; //Kommentar existiert nicht
        }
        return true;
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
                $this->db->rollBack();
                return false; //Email existiert bereits
            }

            $checkUsernameSQL = "SELECT * FROM Anbieter WHERE Nutzername = :nutzername;";
            $checkUsernameCMD = $this->db->prepare($checkUsernameSQL);
            $checkUsernameCMD->bindParam(":nutzername", $nutzername);
            $checkUsernameCMD->execute();
            $result = $checkUsernameCMD->fetchObject();
            if (isset($result->Nutzername)) {
                $this->db->rollBack();
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
                $this->db->rollBack();
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
                $this->db->rollBack();
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

    public function gemaelde_anlegen($AnbieterID, $token, $file, $titel, $beschreibung, $artist, $date, $location): int
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $token)) {
                return -1;
            }

            $insertGemaeldeSQL = "INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe, Dateityp)
                                    VALUES (:anbieter, :titel, :artist, :beschreibung, :date, :location, 0, :hochladedatum, 0, :filetype);";
            $insertGemaeldeCMD = $this->db->prepare($insertGemaeldeSQL);
            $creation_date = date("Y.m.d", strtotime($date));
            $upload_date = date("Y.m.d");
            $insertGemaeldeCMD->bindParam(":anbieter", $AnbieterID);
            $insertGemaeldeCMD->bindParam(":titel", $titel);
            $insertGemaeldeCMD->bindParam(":artist", $artist);
            $insertGemaeldeCMD->bindParam(":beschreibung", $beschreibung);
            $insertGemaeldeCMD->bindParam(":date", $creation_date);
            $insertGemaeldeCMD->bindParam(":location", $location);
            $insertGemaeldeCMD->bindParam(":hochladedatum", $upload_date);
            $insertGemaeldeCMD->bindParam(":filetype", $file);
            $insertGemaeldeCMD->execute();

            $id = $this->db->lastInsertId();

            $this->db->commit();
            return $id;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return -1;
        }
    }

    public function gemaelde_editieren($AnbieterID, $token, $gemaeldeID, $beschreibung, $erstellungsdatum, $ort): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $token)) {
                return false;
            }

            if (!$this->gemaeldeCheck($gemaeldeID)) {
                return false;
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

            if (!$this->gemaeldeCheck($gemaeldeID)) {
                return false;
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

            if (!$this->gemaeldeCheck($gemaeldeID)) {
                return array(-1);
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
            $Dateityp = $result->Dateityp;

            $this->db->commit();
            return array($GemaeldeID, $AnbieterID, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort, $Bewertung, $Hochladedatum, $Aufrufe, $Dateityp);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function sammlung_anlegen($AnbieterID, $token, $auswahl, $titel, $beschreibung): bool
    {
        //$auswahl ist kommaseparierte liste an gemaeldeIDs, z.B. 1,4,3,2
        $auswahlSplitted = explode(",", $auswahl);
        foreach ($auswahlSplitted as $split) {
            if (!strlen($split) == 1) {
                $this->db->rollBack();
                return false; //falsche eingabe
            }
        }

        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $token)) {
                return false;
            }

            $getHighestSammlungIDSQL = "SELECT MAX(SammlungID) AS name FROM Sammlung;";
            $getHighestSammlungIDCMD = $this->db->prepare($getHighestSammlungIDSQL);
            $getHighestSammlungIDCMD->execute();
            $result = $getHighestSammlungIDCMD->fetchObject();
            $NewSammlungID = 0;
            $result = $result->name;
            if (isset($result)) {
                $NewSammlungID = ((integer)$result) + 1;
            }

            $insertSammlungSQL = "INSERT INTO Sammlung (SammlungID, AnbieterID, Titel, Beschreibung, Erstellungsdatum) 
                                    VALUES (:SammlungID, :AnbieterID, :titel, :beschreibung, :hochladedatum);";
            $insertSammlungCMD = $this->db->prepare($insertSammlungSQL);
            $upload_date = date("Y.m.d");
            $insertSammlungCMD->bindParam(":SammlungID", $NewSammlungID);
            $insertSammlungCMD->bindParam(":AnbieterID", $AnbieterID);
            $insertSammlungCMD->bindParam(":titel", $titel);
            $insertSammlungCMD->bindParam(":beschreibung", $beschreibung);
            $insertSammlungCMD->bindParam(":hochladedatum", $upload_date);
            $insertSammlungCMD->execute();

            foreach ($auswahlSplitted as $GemaeldeID) {
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
                $this->db->rollBack();
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
                $this->db->rollBack();
                return array(-1); //SammlungID existiert nicht
            }

            $getSammlungSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $getSammlungCMD = $this->db->prepare($getSammlungSQL);
            $getSammlungCMD->bindParam(":SammlungID", $sammlungID);
            $getSammlungCMD->execute();
            $result = $getSammlungCMD->fetchObject();
            $SammlungID = $result->SammlungID;
            $AnbieterID = $result->AnbieterID;
            $Titel = $result->Titel;
            $Beschreibung = $result->Beschreibung;
            $Bewertung = $result->Bewertung;
            $Hochladedatum = $result->Erstellungsdatum;
            $Aufrufe = $result->Aufrufe;


            $this->db->commit();
            // [SammlungID, users_NutzerID, gemaelde_GemaeldeIDs, Titel, Beschreibung, Bewertung, Hochladedatum, Aufrufe]

            $getGehoertZuSQL = "SELECT * FROM gehoert_zu WHERE SammlungID = :SammlungID;";
            $getGehoertZuCMD = $this->db->prepare($getGehoertZuSQL);
            $getGehoertZuCMD->bindParam(":SammlungID", $sammlungID);
            $getGehoertZuCMD->execute();
            $GemaeldeIDs = array();
            while ($zeile = $getGehoertZuCMD->fetchObject()) {
                $GemaeldeIDs[] = $zeile->GemaeldeID;
            }

            return array($SammlungID, $AnbieterID, $GemaeldeIDs, $Titel, $Beschreibung, $Bewertung, $Hochladedatum, $Aufrufe);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function kommentar_anlegen($text, $gemaeldeID, $nutzerID): bool
    {
        try {
            $this->db->beginTransaction();

            $sql = 'INSERT INTO Kommentar  (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
                    VALUES (:gemaeldeID, :anbieterID, 0, :text, :date) ;';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam(':gemaeldeID', $gemaeldeID);
            $kommando->bindParam(':anbieterID', $nutzerID);
            $kommando->bindParam(':text', $text);
            $date = date("Y.m.d");
            $kommando->bindParam(':date', $date);
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_entfernen($AnbieterID, $token, $kommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $token)) {
                return false;
            }
            if (!$this->kommentarCheck($kommentarID)) {
                return false;
            }

            $sql = 'DELETE FROM Kommentar WHERE KommentarID = :id;';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam(':id', $kommentarID);
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_liken($AnbieterID, $token, $kommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $token)) {
                return false;
            }
            if (!$this->kommentarCheck($kommentarID)) {
                return false;
            }

            $checkGelikedSQL = "SELECT * FROM geliked_von WHERE KommentarID = :KommentarID;";
            $checkGelikedSQL = $this->db->prepare($checkGelikedSQL);
            $checkGelikedSQL->bindParam(":KommentarID", $kommentarID);
            $checkGelikedSQL->execute();
            $result = $checkGelikedSQL->fetchObject();
            if (isset($result->KommentarID)) {
                $this->db->rollBack();
                return false; //Kommentar bereits geliked
            }

            $sql = 'UPDATE Kommentar SET Likeanzahl = Likeanzahl + 1 WHERE KommentarID = :id;';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam( ':id', $kommentarID );
            $kommando->execute();

            $sql = 'Insert INTO geliked_von (KommentarID, AnbieterID) VALUES (:KommentarID, :AnbieterID);';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam( ':KommentarID', $kommentarID );
            $kommando->bindParam( ':AnbieterID', $nutzerID );
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
        try{
            $this->db->beginTransaction();

            $getKommentarSQL = "SELECT * FROM Kommentar WHERE GemaeldeID = :GemaeldeID;";
            $getKommentarCMD = $this->db->prepare($getKommentarSQL);
            $getKommentarCMD->bindParam(":GemaeldeID", $gemaeldeID);
            $getKommentarCMD->execute();

            $this->db->commit();

            $kommentare = array();
            while($zeile = $getKommentarCMD->fetchObject()) {
                $kommentare[] = array($zeile->KommentarID, $zeile->GemaeldeID, $zeile->AnbieterID, $zeile->Likeanzahl, $zeile->Textinhalt, $zeile->Erstellungsdatum);
            }
            return $kommentare;
        }catch(Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }

    }

    public function profil_erhalten($AnbieterID): array
    {
        try {
            $this->db->beginTransaction();

            $checkAnbieterIDSQL = "SELECT * FROM Anbieter WHERE AnbieterID = :AnbieterID;";
            $checkAnbieterIDCMD = $this->db->prepare($checkAnbieterIDSQL);
            $checkAnbieterIDCMD->bindParam(":AnbieterID", $AnbieterID);
            $checkAnbieterIDCMD->execute();
            $result = $checkAnbieterIDCMD->fetchObject();
            if (!isset($result->AnbieterID)) {
                $this->db->rollBack();
                return array(-1); //Nutzer existiert nicht
            }

            $getProfilSQL = "SELECT * FROM Anbieter WHERE AnbieterID = :AnbieterID;";
            $getProfilCMD = $this->db->prepare($getProfilSQL);
            $getProfilCMD->bindParam(":AnbieterID", $AnbieterID);
            $getProfilCMD->execute();
            $result = $getProfilCMD->fetchObject();

            // [NutzerID, Nutzername, beschreibung, geschlecht, VollständigerName, Adresse, Sprache, Geburtsdatum, Registrierungsdatum]
            $AnbieterID = $result->AnbieterID;
            $Nutzername = $result->Nutzername;
            $Personenbeschreibung = $result->Personenbeschreibung;
            $Geschlecht = $result->Geschlecht;
            $Vollstaendiger_Name = $result->Vollstaendiger_Name;
            $Anschrift = $result->Anschrift;
            $Sprache = $result->Sprache;
            $Geburtsdatum = $result->Geburtsdatum;
            $Registrierungsdatum = $result->Registrierungsdatum;

            $this->db->commit();
            return array($AnbieterID, $Nutzername, $Personenbeschreibung, $Geschlecht, $Vollstaendiger_Name, $Anschrift, $Sprache, $Geburtsdatum, $Registrierungsdatum);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function ausstellung_erhalten($suche, $filter): array
    {
        try {
            $this->db->beginTransaction();

            $suche_result = array();

            $getAusstellungSQL = "SELECT * FROM Gemaelde";

            if (!empty($suche)) {
                $getAusstellungSQL .= " WHERE Titel LIKE :suche";
            }

            if ($filter == "relevance") {
                $getAusstellungSQL .= " ORDER BY Bewertung";
            } else if ($filter == "date") {
                $getAusstellungSQL .= " ORDER BY Erstellungsdatum";
            }
            $getAusstellungSQL .= ";";

            $getAusstellungCMD = $this->db->prepare($getAusstellungSQL);
            if (!empty($suche)) {
                $getAusstellungCMD->bindValue(":suche", '%' . $suche . '%');
            }
            $getAusstellungCMD->execute();

            while ($zeile = $getAusstellungCMD->fetchObject()) {
                $suche_result[] = array($zeile->GemaeldeID, $zeile->AnbieterID, $zeile->Titel, $zeile->Kuenstler,
                    $zeile->Beschreibung, $zeile->Erstellungsdatum, $zeile->Ort, $zeile->Bewertung, $zeile->Hochladedatum,
                    $zeile->Aufrufe, $zeile->Dateityp);
            }
            $return_array = array(array(), array(), array(), array());
            $curr_reihe = 0;
            foreach ($suche_result as $gemaelde_result) {
                $return_array[$curr_reihe][] = $gemaelde_result;
                $curr_reihe = ($curr_reihe + 1) % 4;
            }
            $this->db->commit();
            return $return_array;
        }catch (Exception $ex){
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }

    }

    public function sammlungen_erhalten($suche, $filter): array
    {
        try {
            $this->db->beginTransaction();

            $getSammlungenSQL = "SELECT * FROM Sammlung";

            if (!empty($suche)) {
                $getSammlungenSQL .= " WHERE Titel = :suche";
            }

            if ($filter == "relevance") {
                $getSammlungenSQL .= " ORDER BY Bewertung";
            } else if ($filter == "date") {
                $getSammlungenSQL .= " ORDER BY Erstellungsdatum";
            }

            $getSammlungenSQL .= ";";

            $getSammlungenCMD = $this->db->prepare($getSammlungenSQL);
            if (!empty($suche)) {
                $getSammlungenCMD->bindParam(":suche", $suche);
            }
            $getSammlungenCMD->execute();

            $this->db->commit();

            $suche_result = array();
            while ($zeile = $getSammlungenCMD->fetchObject()) {
                $suche_result[] = $this->sammlung_erhalten($zeile->SammlungID);
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
    }

    public function profil_editieren($nutzerID, $token, $nutzername, $beschreibung, $geschlecht, $vollsaendigerName, $adresse, $geburtsdatum)
    {
        // TODO: Implement profil_editieren() method.
    }
}
