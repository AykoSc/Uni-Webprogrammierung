<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";
include $abs_path . "/controller/DBErstellung.php";

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
            $nutzername = "root";
            $passwort = null;
            $dsn = "sqlite:database.db";
            // Nur bei MySQL: $dsn = "mysql:dbname=website;host=localhost";
            $this->db = new PDO($dsn, $nutzername, $passwort);
            // Nur bei MySQL: $this->db->exec("SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE");
            try {
                $this->db->beginTransaction();

                $this->db->exec(DBErstellung::TABELLEN);

                $checkLeereDatenbankCMD = $this->db->query("SELECT * FROM Gemaelde;");
                $result = $checkLeereDatenbankCMD->fetchObject();
                if (!isset($result->GemaeldeID)) {
                    $this->db->exec(DBErstellung::DATEN); // Daten werden eingefügt, wenn Datenbank leer ist

                    // Passwort für Test-Accounts hashen
                    $Nutzername1 = "test1";
                    $Hash1 = password_hash("test1!", PASSWORD_DEFAULT);
                    $Nutzername2 = "test2";
                    $Hash2 = password_hash("test2!", PASSWORD_DEFAULT);
                    $setzePasswortSQL = "UPDATE Anbieter SET Passwort = :Passwort WHERE Nutzername = :Nutzername;";
                    $setzePasswortCMD = $this->db->prepare($setzePasswortSQL);
                    $setzePasswortCMD->bindParam(':Passwort', $Hash1);
                    $setzePasswortCMD->bindParam(':Nutzername', $Nutzername1);
                    $setzePasswortCMD->execute();
                    $setzePasswortCMD->bindParam(':Passwort', $Hash2);
                    $setzePasswortCMD->bindParam(':Nutzername', $Nutzername2);
                    $setzePasswortCMD->execute();
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
     *           Methoden nur innerhalb einer Transaktion benutzen!
     */
    private function anbieterCheck($AnbieterID, $Tokennummer): bool
    {
        $checkAnbieterSQL = "SELECT * FROM Tokens WHERE AnbieterID = :AnbieterID AND Tokennummer = :Tokennummer;";
        $checkAnbieterCMD = $this->db->prepare($checkAnbieterSQL);
        $checkAnbieterCMD->bindParam(":AnbieterID", $AnbieterID);
        $checkAnbieterCMD->bindParam(":Tokennummer", $Tokennummer);
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

    private function sammlungCheck($SammlungID): bool
    {
        $checkSammlungSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
        $checkSammlungCMD = $this->db->prepare($checkSammlungSQL);
        $checkSammlungCMD->bindParam(":SammlungID", $SammlungID);
        $checkSammlungCMD->execute();
        $result = $checkSammlungCMD->fetchObject();
        if (!isset($result->SammlungID)) {
            $this->db->rollBack();
            return false; //SammlungID existiert nicht
        }
        return true;
    }

    private function kommentarCheck($KommentarID): bool
    {
        $checkKommentarSQL = "SELECT * FROM Kommentar WHERE KommentarID = :KommentarID;";
        $checkKommentarCMD = $this->db->prepare($checkKommentarSQL);
        $checkKommentarCMD->bindParam(":KommentarID", $KommentarID);
        $checkKommentarCMD->execute();
        $result = $checkKommentarCMD->fetchObject();
        if (!isset($result->KommentarID)) {
            $this->db->rollBack();
            return false; //Kommentar existiert nicht
        }
        return true;
    }

    public function nutzername_unbenutzt($Nutzername): bool
    {
        try {
            $this->db->beginTransaction();

            $checkNutzernameSQL = "SELECT * FROM Anbieter WHERE Nutzername = :Nutzername;";
            $checkNutzernameCMD = $this->db->prepare($checkNutzernameSQL);
            $checkNutzernameCMD->bindParam(":Nutzername", $Nutzername);
            $checkNutzernameCMD->execute();
            $result = $checkNutzernameCMD->fetchObject();
            if (isset($result->Nutzername)) {
                $this->db->rollBack();
                return false; //Nutzername existiert bereits
            }

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function registrieren($Nutzername, $Email, $Passwort): bool
    {
        try {
            $this->db->beginTransaction();

            $checkEmailSQL = "SELECT * FROM Anbieter WHERE Email = :email;";
            $checkEmailCMD = $this->db->prepare($checkEmailSQL);
            $checkEmailCMD->bindParam(":email", $Email);
            $checkEmailCMD->execute();
            $result = $checkEmailCMD->fetchObject();
            if (isset($result->Email)) {
                $this->db->rollBack();
                return false; //Email existiert bereits
            }

            $checkNutzernameSQL = "SELECT * FROM Anbieter WHERE Nutzername = :Nutzername;";
            $checkNutzernameCMD = $this->db->prepare($checkNutzernameSQL);
            $checkNutzernameCMD->bindParam(":Nutzername", $Nutzername);
            $checkNutzernameCMD->execute();
            $result = $checkNutzernameCMD->fetchObject();
            if (isset($result->Nutzername)) {
                $this->db->rollBack();
                return false; //Nutzername existiert bereits
            }

            $Hash = password_hash($Passwort, PASSWORD_DEFAULT);

            $speichereAnbieterSQL = "INSERT INTO Anbieter (Nutzername, Email, Passwort) VALUES (:Nutzername, :Email, :Passwort);";
            $speichereAnbieterCMD = $this->db->prepare($speichereAnbieterSQL);
            $speichereAnbieterCMD->bindParam(":Nutzername", $Nutzername);
            $speichereAnbieterCMD->bindParam(":Email", $Email);
            $speichereAnbieterCMD->bindParam(":Passwort", $Hash);
            $speichereAnbieterCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function anmelden($Email, $Passwort): array
    {
        try {
            $this->db->beginTransaction();

            $existiertAnbieterSQL = "SELECT * FROM Anbieter WHERE Email = :Email;";
            $existiertAnbieterCMD = $this->db->prepare($existiertAnbieterSQL);
            $existiertAnbieterCMD->bindParam(":Email", $Email);
            $existiertAnbieterCMD->execute();
            $ergebnis = $existiertAnbieterCMD->fetchObject();
            if (!isset($ergebnis->AnbieterID) or !isset($ergebnis->Email) or !isset($ergebnis->Passwort)) {
                $this->db->rollBack();
                return array(-1, ""); // Anmeldung fehlgeschlagen (Anbieter existiert nicht)
            }

            $valid = password_verify($Passwort, $ergebnis->Passwort);
            if ($valid) {
                $AnbieterID = $ergebnis->AnbieterID;
                $Tokennummer = openssl_random_pseudo_bytes(16); //Generiere einen zufälligen Text.
                $Tokennummer = bin2hex($Tokennummer); //Konvertiere die Binäre-Daten zu Hexadezimal-Daten.

                $speichereTokenSQL = "INSERT INTO Tokens (AnbieterID, Tokennummer) VALUES (:AnbieterID, :Tokennummer);";
                $speichereTokenCMD = $this->db->prepare($speichereTokenSQL);
                $speichereTokenCMD->bindParam(":AnbieterID", $AnbieterID);
                $speichereTokenCMD->bindParam(":Tokennummer", $Tokennummer);
                $speichereTokenCMD->execute();

                $this->db->commit();
                return array($AnbieterID, $Tokennummer);
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

    public function abmelden($AnbieterID, $Tokennummer): bool
    {
        try {
            $this->db->beginTransaction();

            $entferneTokenSQL = "DELETE FROM Tokens WHERE AnbieterID = :AnbieterID AND Tokennummer = :Tokennummer;";
            $entferneTokenCMD = $this->db->prepare($entferneTokenSQL);
            $entferneTokenCMD->bindParam(":AnbieterID", $AnbieterID);
            $entferneTokenCMD->bindParam(":Tokennummer", $Tokennummer);
            $entferneTokenCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kontakt_aufnehmen($EMail, $Kommentar): bool
    {
        try {
            $this->db->beginTransaction();

            $speichereKontaktSQL = "INSERT INTO Kontakt (Kommentar, EMail) VALUES (:Kommentar, :EMail);";
            $speichereKontaktCMD = $this->db->prepare($speichereKontaktSQL);
            $speichereKontaktCMD->bindParam(":Kommentar", $Kommentar);
            $speichereKontaktCMD->bindParam(":EMail", $EMail);
            $speichereKontaktCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_anlegen($AnbieterID, $Tokennummer, $Dateityp, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort): int
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return -1;
            }

            $Erstellungsdatum = date("Y.m.d", strtotime($Erstellungsdatum));
            $Hochladedatum = date("Y.m.d");

            $speichereGemaeldeSQL = "INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe, Dateityp)
                                     VALUES (:AnbieterID, :Titel, :Kuenstler, :Beschreibung, :Erstellungsdatum, :Ort, 0, :Hochladedatum, 0, :Dateityp);";
            $speichereGemaeldeCMD = $this->db->prepare($speichereGemaeldeSQL);
            $speichereGemaeldeCMD->bindParam(":AnbieterID", $AnbieterID);
            $speichereGemaeldeCMD->bindParam(":Titel", $Titel);
            $speichereGemaeldeCMD->bindParam(":Kuenstler", $Kuenstler);
            $speichereGemaeldeCMD->bindParam(":Beschreibung", $Beschreibung);
            $speichereGemaeldeCMD->bindParam(":Erstellungsdatum", $Erstellungsdatum);
            $speichereGemaeldeCMD->bindParam(":Ort", $Ort);
            $speichereGemaeldeCMD->bindParam(":Hochladedatum", $Hochladedatum);
            $speichereGemaeldeCMD->bindParam(":Dateityp", $Dateityp);
            $speichereGemaeldeCMD->execute();

            $id = $this->db->lastInsertId();

            $this->db->commit();
            return $id;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return -1;
        }
    }

    public function gemaelde_editieren($AnbieterID, $Tokennummer, $GemaeldeID, $Beschreibung, $Erstellungsdatum, $Ort): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }

            if (!$this->gemaeldeCheck($GemaeldeID)) {
                return false;
            }

            $Erstellungsdatum = date("Y.m.d", strtotime($Erstellungsdatum));

            $editiereGemaeldeSQL = "UPDATE Gemaelde SET Beschreibung = :Beschreibung, Erstellungsdatum = :Erstellungsdatum, Ort = :Ort WHERE GemaeldeID = :GemaeldeID AND AnbieterID = :AnbieterID;";
            $editiereGemaeldeCMD = $this->db->prepare($editiereGemaeldeSQL);
            $editiereGemaeldeCMD->bindParam(':Beschreibung', $Beschreibung);
            $editiereGemaeldeCMD->bindParam(':Erstellungsdatum', $Erstellungsdatum);
            $editiereGemaeldeCMD->bindParam(':Ort', $Ort);
            $editiereGemaeldeCMD->bindParam(':GemaeldeID', $GemaeldeID);
            $editiereGemaeldeCMD->bindParam(':AnbieterID', $AnbieterID);
            $editiereGemaeldeCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }

            if (!$this->gemaeldeCheck($GemaeldeID)) {
                return false;
            }

            $erhalteGemaeldeSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID";
            $erhalteGemaeldeCMD = $this->db->prepare($erhalteGemaeldeSQL);
            $erhalteGemaeldeCMD->bindParam(":GemaeldeID", $GemaeldeID);
            $erhalteGemaeldeCMD->execute();

            $ergebnis = $erhalteGemaeldeCMD->fetchObject();
            if ($ergebnis->AnbieterID != $AnbieterID) {
                $this->db->rollBack();
                return false;
            }
            $Dateityp = $ergebnis->Dateityp;

            $entferneGemaeldeSQL = "DELETE FROM Gemaelde WHERE GemaeldeID = :GemaeldeID AND AnbieterID = :AnbieterID;";
            $entferneGemaeldeCMD = $this->db->prepare($entferneGemaeldeSQL);
            $entferneGemaeldeCMD->bindParam(":GemaeldeID", $GemaeldeID);
            $entferneGemaeldeCMD->bindParam(':AnbieterID', $AnbieterID);
            $entferneGemaeldeCMD->execute();

            $entferneAusSammlungenSQL = "DELETE FROM gehoert_zu WHERE GemaeldeID = :GemaeldeID;";
            $entferneAusSammlungenCMD = $this->db->prepare($entferneAusSammlungenSQL);
            $entferneAusSammlungenCMD->bindParam(":GemaeldeID", $GemaeldeID);
            $entferneAusSammlungenCMD->execute();

            unlink('images/' . $GemaeldeID . '.' . $Dateityp);

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function gemaelde_erhalten($GemaeldeID): array
    {
        try {
            $this->db->beginTransaction();

            if (!$this->gemaeldeCheck($GemaeldeID)) {
                return array(-1);
            }

            $erhalteGemaeldeSQL = "SELECT * FROM Gemaelde WHERE GemaeldeID = :GemaeldeID;";
            $erhalteGemaeldeCMD = $this->db->prepare($erhalteGemaeldeSQL);
            $erhalteGemaeldeCMD->bindParam(":GemaeldeID", $GemaeldeID);
            $erhalteGemaeldeCMD->execute();
            $ergebnis = $erhalteGemaeldeCMD->fetchObject();

            $datumFormatiert = explode(".", $ergebnis->Erstellungsdatum);
            $ergebnis->Erstellungsdatum = $datumFormatiert[0] . "-" . $datumFormatiert[1] . "-" . $datumFormatiert[2];

            $datumFormatiert = explode(".", $ergebnis->Hochladedatum);
            $ergebnis->Hochladedatum = $datumFormatiert[2] . "." . $datumFormatiert[1] . "." . $datumFormatiert[0];

            //Erhöhe Aufrufe um 1
            $aktualisiereAufrufeSQL = "UPDATE Gemaelde SET Aufrufe = :Aufrufe WHERE GemaeldeID = :GemaeldeID;";
            $aktualisiereAufrufeCMD = $this->db->prepare($aktualisiereAufrufeSQL);
            $aktualisiereAufrufeCMD->bindValue(':Aufrufe', $ergebnis->Aufrufe + 1);
            $aktualisiereAufrufeCMD->bindParam(':GemaeldeID', $GemaeldeID);
            $aktualisiereAufrufeCMD->execute();

            $this->db->commit();
            return array($ergebnis->GemaeldeID, $ergebnis->AnbieterID, $ergebnis->Titel, $ergebnis->Kuenstler,
                $ergebnis->Beschreibung, $ergebnis->Erstellungsdatum, $ergebnis->Ort, $ergebnis->Bewertung,
                $ergebnis->Hochladedatum, $ergebnis->Aufrufe, $ergebnis->Dateityp);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function sammlung_anlegen($AnbieterID, $Tokennummer, $Auswahl, $Titel, $Beschreibung): int
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return -1;
            }

            $Erstellungsdatum = date("Y.m.d");

            $speichereSammlungSQL = "INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe) 
                                     VALUES (:AnbieterID, :Titel, :Beschreibung, 0, :Erstellungsdatum, 0);";
            $speichereSammlungCMD = $this->db->prepare($speichereSammlungSQL);
            $speichereSammlungCMD->bindParam(":AnbieterID", $AnbieterID);
            $speichereSammlungCMD->bindParam(":Titel", $Titel);
            $speichereSammlungCMD->bindParam(":Beschreibung", $Beschreibung);
            $speichereSammlungCMD->bindParam(":Erstellungsdatum", $Erstellungsdatum);
            $speichereSammlungCMD->execute();

            $SammlungID = $this->db->lastInsertId();

            //$Auswahl ist kommaseparierte Liste von GemaeldeIDs, z.B. 1,4,3,2
            $Auswahl = explode(",", $Auswahl);

            foreach ($Auswahl as $GemaeldeID) {
                if (!$this->gemaeldeCheck($GemaeldeID)) {
                    return -1;
                }
                $speichereGehoertZuSQL = "INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
                                          VALUES (:GemaeldeID, :SammlungID);";
                $speichereGehoertZuCMD = $this->db->prepare($speichereGehoertZuSQL);
                $speichereGehoertZuCMD->bindParam(":GemaeldeID", $GemaeldeID);
                $speichereGehoertZuCMD->bindParam(":SammlungID", $SammlungID);
                $speichereGehoertZuCMD->execute();
            }

            $this->db->commit();
            return $SammlungID;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return -1;
        }
    }

    public function sammlung_editieren($AnbieterID, $Tokennummer, $SammlungID, $Titel, $Beschreibung): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }

            if (!$this->sammlungCheck($SammlungID)) {
                return false;
            }

            $editiereSammlungSQL = "UPDATE Sammlung SET Titel = :Titel, Beschreibung = :Beschreibung WHERE SammlungID = :SammlungID AND AnbieterID = :AnbieterID;";
            $editiereSammlungCMD = $this->db->prepare($editiereSammlungSQL);
            $editiereSammlungCMD->bindParam(":Titel", $Titel);
            $editiereSammlungCMD->bindParam(":Beschreibung", $Beschreibung);
            $editiereSammlungCMD->bindParam(":SammlungID", $SammlungID);
            $editiereSammlungCMD->bindParam(':AnbieterID', $AnbieterID);
            $editiereSammlungCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }

            if (!$this->sammlungCheck($SammlungID)) {
                return false;
            }

            $entferneSammlungSQL = "DELETE FROM Sammlung WHERE SammlungID = :SammlungID AND AnbieterID = :AnbieterID;";
            $entferneSammlungCMD = $this->db->prepare($entferneSammlungSQL);
            $entferneSammlungCMD->bindParam(":SammlungID", $SammlungID);
            $entferneSammlungCMD->bindParam(':AnbieterID', $AnbieterID);
            $entferneSammlungCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function sammlung_erhalten($SammlungID): array
    {
        try {
            $this->db->beginTransaction();

            if (!$this->sammlungCheck($SammlungID)) {
                return array(-1);
            }

            $erhalteSammlungSQL = "SELECT * FROM Sammlung WHERE SammlungID = :SammlungID;";
            $erhalteSammlungCMD = $this->db->prepare($erhalteSammlungSQL);
            $erhalteSammlungCMD->bindParam(":SammlungID", $SammlungID);
            $erhalteSammlungCMD->execute();
            $ergebnis = $erhalteSammlungCMD->fetchObject();

            $datumFormatiert = explode(".", $ergebnis->Erstellungsdatum);
            $ergebnis->Erstellungsdatum = $datumFormatiert[2] . "." . $datumFormatiert[1] . "." . $datumFormatiert[0];

            $erhalteSammlungsinhalteSQL = "SELECT * FROM gehoert_zu WHERE SammlungID = :SammlungID;";
            $erhalteSammlungsinhalteCMD = $this->db->prepare($erhalteSammlungsinhalteSQL);
            $erhalteSammlungsinhalteCMD->bindParam(":SammlungID", $ergebnis->SammlungID);
            $erhalteSammlungsinhalteCMD->execute();
            $GemaeldeIDs = array();
            while ($zeile = $erhalteSammlungsinhalteCMD->fetchObject()) {
                $GemaeldeIDs[] = $zeile->GemaeldeID;
            }

            //Erhöhe Aufrufe um 1
            $aktualisiereAufrufeSQL = "UPDATE Sammlung SET Aufrufe = :Aufrufe WHERE SammlungID = :SammlungID;";
            $aktualisiereAufrufeCMD = $this->db->prepare($aktualisiereAufrufeSQL);
            $aktualisiereAufrufeCMD->bindValue(':Aufrufe', $ergebnis->Aufrufe + 1);
            $aktualisiereAufrufeCMD->bindParam(':SammlungID', $SammlungID);
            $aktualisiereAufrufeCMD->execute();

            $this->db->commit();
            return array($SammlungID, $ergebnis->AnbieterID, $GemaeldeIDs, $ergebnis->Titel, $ergebnis->Beschreibung, $ergebnis->Bewertung, $ergebnis->Erstellungsdatum, $ergebnis->Aufrufe);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function kommentar_anlegen($AnbieterID, $Tokennummer, $Textinhalt, $GemaeldeID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }
            if (!$this->gemaeldeCheck($GemaeldeID)) {
                return false;
            }

            $sql = 'INSERT INTO Kommentar  (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
                    VALUES (:GemaeldeID, :AnbieterID, 0, :Textinhalt, :Erstellungsdatum) ;';
            $kommando = $this->db->prepare($sql);
            $kommando->bindParam(':GemaeldeID', $GemaeldeID);
            $kommando->bindParam(':AnbieterID', $AnbieterID);
            $kommando->bindParam(':Textinhalt', $Textinhalt);
            $Erstellungsdatum = date("Y.m.d");
            $kommando->bindParam(':Erstellungsdatum', $Erstellungsdatum);
            $kommando->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_entfernen($AnbieterID, $Tokennummer, $KommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }
            if (!$this->kommentarCheck($KommentarID)) {
                return false;
            }

            $entferneKommentarSQL = 'DELETE FROM Kommentar WHERE KommentarID = :KommentarID AND AnbieterID = :AnbieterID;';
            $entferneKommentarCMD = $this->db->prepare($entferneKommentarSQL);
            $entferneKommentarCMD->bindParam(':KommentarID', $KommentarID);
            $entferneKommentarCMD->bindParam(':AnbieterID', $AnbieterID);
            $entferneKommentarCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentar_liken($AnbieterID, $Tokennummer, $KommentarID): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }
            if (!$this->kommentarCheck($KommentarID)) {
                return false;
            }

            $bereitsGelikedSQL = "SELECT * FROM geliked_von WHERE KommentarID = :KommentarID AND AnbieterID = :AnbieterID;";
            $bereitsGelikedCMD = $this->db->prepare($bereitsGelikedSQL);
            $bereitsGelikedCMD->bindParam(":KommentarID", $KommentarID);
            $bereitsGelikedCMD->bindParam(':AnbieterID', $AnbieterID);
            $bereitsGelikedCMD->execute();
            $ergebnis = $bereitsGelikedCMD->fetchObject();
            if (isset($ergebnis->KommentarID)) {
                $entlikenSQL = 'DELETE FROM geliked_von WHERE AnbieterID = :AnbieterID AND KommentarID = :KommentarID;';
                $entlikenCMD = $this->db->prepare($entlikenSQL);
                $entlikenCMD->bindParam(":KommentarID", $KommentarID);
                $entlikenCMD->bindParam(':AnbieterID', $AnbieterID);
                $entlikenCMD->execute();

                $likeEntfernenSQL = 'Update Kommentar SET Likeanzahl = Likeanzahl -1 WHERE KommentarID = :KommentarID;';
                $likeEntfernenCMD = $this->db->prepare($likeEntfernenSQL);
                $likeEntfernenCMD->bindParam(":KommentarID", $KommentarID);
                $likeEntfernenCMD->execute();

                $this->db->commit();
                return true; //Kommentar bereits geliked
            }

            $aktualisiereLikeanzahlSQL = 'UPDATE Kommentar SET Likeanzahl = Likeanzahl + 1 WHERE KommentarID = :KommentarID;';
            $aktualisiereLikeanzahlCMD = $this->db->prepare($aktualisiereLikeanzahlSQL);
            $aktualisiereLikeanzahlCMD->bindParam(':KommentarID', $KommentarID);
            $aktualisiereLikeanzahlCMD->execute();

            $speichereLikeSQL = 'Insert INTO geliked_von (KommentarID, AnbieterID) VALUES (:KommentarID, :AnbieterID);';
            $speichereLikeCMD = $this->db->prepare($speichereLikeSQL);
            $speichereLikeCMD->bindParam(':KommentarID', $KommentarID);
            $speichereLikeCMD->bindParam(':AnbieterID', $AnbieterID);
            $speichereLikeCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function kommentare_erhalten($GemaeldeID, $AnbieterID, $Tokennummer ): array
    {
        try {
            $this->db->beginTransaction();

            if (!$this->gemaeldeCheck($GemaeldeID)) {
                return array(-1);
            }

            $erhalteKommentareSQL = "SELECT * FROM Kommentar WHERE GemaeldeID = :GemaeldeID;";
            $erhalteKommentareCMD = $this->db->prepare($erhalteKommentareSQL);
            $erhalteKommentareCMD->bindParam(":GemaeldeID", $GemaeldeID);
            $erhalteKommentareCMD->execute();

            $ergebnis = array();
            while ($zeile = $erhalteKommentareCMD->fetchObject()) {
                $datumSplitted = explode(".", $zeile->Erstellungsdatum);
                $zeile->Erstellungsdatum = $datumSplitted[2] . "." . $datumSplitted[1] . "." . $datumSplitted[0];

                $ergebnis[] = array($zeile->KommentarID, $zeile->GemaeldeID, $zeile->AnbieterID,
                    $zeile->Likeanzahl, $zeile->Textinhalt, $zeile->Erstellungsdatum);
            }

            $checkAnbieterSQL = "SELECT * FROM Tokens WHERE AnbieterID = :AnbieterID AND Tokennummer = :Tokennummer;";
            $checkAnbieterCMD = $this->db->prepare($checkAnbieterSQL);
            $checkAnbieterCMD->bindParam(":AnbieterID", $AnbieterID);
            $checkAnbieterCMD->bindParam(":Tokennummer", $Tokennummer);
            $checkAnbieterCMD->execute();
            $result = $checkAnbieterCMD->fetchObject();

            $erhalteLikesSQL = "SELECT KommentarID FROM geliked_von WHERE AnbieterID = :AnbieterID";
            $erhalteLikesCMD = $this->db->prepare($erhalteLikesSQL);
            $erhalteLikesCMD->bindParam(":AnbieterID", $AnbieterID);
            $erhalteLikesCMD->execute();

            $likes = array();
            while($geliked = $erhalteLikesCMD->fetchObject()){
                $likes[] = $geliked->KommentarID;
            }

            if (!isset($result->AnbieterID) || empty($likes)) {
                for($i = 0; $i<count($ergebnis); $i++){
                    $ergebnis[$i][] = 0;
                }
            }else {
                for($i = 0; $i<count($ergebnis); $i++){
                    if(in_array($ergebnis[$i][0] ,$likes)){
                        $ergebnis[$i][] = 1;
                    }else {
                        $ergebnis[$i][] = 0;
                    }
                }
            }

            $this->db->commit();
            return $ergebnis;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }

    }

    public function profil_erhalten($AnbieterID): array
    {
        try {
            $this->db->beginTransaction();

            $existiertAnbieterSQL = "SELECT * FROM Anbieter WHERE AnbieterID = :AnbieterID;";
            $existiertAnbieterCMD = $this->db->prepare($existiertAnbieterSQL);
            $existiertAnbieterCMD->bindParam(":AnbieterID", $AnbieterID);
            $existiertAnbieterCMD->execute();
            $ergebnis = $existiertAnbieterCMD->fetchObject();

            if (!isset($ergebnis->AnbieterID)) {
                $this->db->rollBack();
                return array(-1); //Nutzer existiert nicht
            }

            $datumSplitted = explode(".", $ergebnis->Geburtsdatum);
            if (sizeof($datumSplitted) == 3) { //split funktioniert nur wenn geburtsdatum bereits angegeben
                $ergebnis->Geburtsdatum = $datumSplitted[0] . "-" . $datumSplitted[1] . "-" . $datumSplitted[2];
            }

            $datumSplitted = explode(".", $ergebnis->Registrierungsdatum);
            $ergebnis->Registrierungsdatum = $datumSplitted[2] . "." . $datumSplitted[1] . "." . $datumSplitted[0];

            $this->db->commit();
            return array($ergebnis->AnbieterID, $ergebnis->Nutzername, $ergebnis->Personenbeschreibung, $ergebnis->Geschlecht, $ergebnis->Vollstaendiger_Name, $ergebnis->Anschrift, $ergebnis->Sprache, $ergebnis->Geburtsdatum, $ergebnis->Registrierungsdatum);
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

    public function profil_editieren($AnbieterID, $Tokennummer, $Personenbeschreibung, $Geschlecht, $Vollstaendiger_Name, $Anschrift, $Sprache, $Geburtsdatum): bool
    {
        try {
            $this->db->beginTransaction();

            if (!$this->anbieterCheck($AnbieterID, $Tokennummer)) {
                return false;
            }

            if (!($Geschlecht === "m" || $Geschlecht === "w")) {
                return false;
            }
            $datumSplitted = explode("-", $Geburtsdatum);
            if (sizeof($datumSplitted) == 3) { //split funktioniert nur wenn geburtsdatum bereits angegeben
                $Geburtsdatum = $datumSplitted[0] . "." . $datumSplitted[1] . "." . $datumSplitted[2];
            } else {
                return false; //Datum nicht richtig übertragen (nicht im Format yyyy-mm-dd)
            }

            $editiereAnbieterSQL = "UPDATE Anbieter 
                                SET Personenbeschreibung = :Personenbeschreibung, Geschlecht = :Geschlecht, Vollstaendiger_Name = :Vollstaendiger_Name, Anschrift = :Anschrift, Sprache = :Sprache, Geburtsdatum = :Geburtsdatum
                                WHERE AnbieterID = :AnbieterID";
            $editiereAnbieterCMD = $this->db->prepare($editiereAnbieterSQL);
            $editiereAnbieterCMD->bindParam(":AnbieterID", $AnbieterID);
            $editiereAnbieterCMD->bindParam(":Personenbeschreibung", $Personenbeschreibung);
            $editiereAnbieterCMD->bindParam(":Geschlecht", $Geschlecht);
            $editiereAnbieterCMD->bindParam(":Vollstaendiger_Name", $Vollstaendiger_Name);
            $editiereAnbieterCMD->bindParam(":Anschrift", $Anschrift);
            $editiereAnbieterCMD->bindParam(":Sprache", $Sprache);
            $editiereAnbieterCMD->bindParam(":Geburtsdatum", $Geburtsdatum);
            $editiereAnbieterCMD->execute();

            $this->db->commit();
            return true;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return false;
        }
    }

    public function ausstellung_erhalten($Suche, $Filter): array
    {
        try {
            $this->db->beginTransaction();

            $erhalteAusstellungSQL = "SELECT * FROM Gemaelde WHERE Titel LIKE :suche";
            if ($Filter == "beliebteste") {
                $erhalteAusstellungSQL .= " ORDER BY Bewertung DESC";
            } else if ($Filter == "datum") {
                $erhalteAusstellungSQL .= " ORDER BY Erstellungsdatum DESC";
            }
            $erhalteAusstellungSQL .= ";";

            $erhalteAusstellungCMD = $this->db->prepare($erhalteAusstellungSQL);
            $erhalteAusstellungCMD->bindValue(":suche", '%' . $Suche . '%');
            $erhalteAusstellungCMD->execute();
            $this->db->commit();

            $suchergebnis = array();
            while ($zeile = $erhalteAusstellungCMD->fetchObject()) {
                $suchergebnis[] = $this->gemaelde_erhalten($zeile->GemaeldeID);
            }
            $ergebnis = array(array(), array(), array(), array());
            $reihe = 0;
            foreach ($suchergebnis as $g) {
                $ergebnis[$reihe][] = $g;
                $reihe = ($reihe + 1) % 4;
            }
            return $ergebnis;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }

    }

    public function sammlungen_erhalten($Suche, $Filter): array
    {
        try {
            $this->db->beginTransaction();

            $erhalteSammlungenSQL = "SELECT * FROM Sammlung WHERE Titel LIKE :suche";
            if ($Filter == "beliebteste") {
                $erhalteSammlungenSQL .= " ORDER BY Bewertung DESC";
            } else if ($Filter == "datum") {
                $erhalteSammlungenSQL .= " ORDER BY Erstellungsdatum DESC";
            }
            $erhalteSammlungenSQL .= ";";

            $erhalteSammlungenCMD = $this->db->prepare($erhalteSammlungenSQL);
            $erhalteSammlungenCMD->bindValue(":suche", '%' . $Suche . '%');
            $erhalteSammlungenCMD->execute();
            $this->db->commit();

            $suchergebnis = array();
            while ($zeile = $erhalteSammlungenCMD->fetchObject()) {
                $suchergebnis[] = $this->sammlung_erhalten($zeile->SammlungID);
            }
            $ergebnis = array(array(), array(), array(), array());
            $reihe = 0;
            foreach ($suchergebnis as $s) {
                $ergebnis[$reihe][] = $s;
                $reihe = ($reihe + 1) % 4;
            }
            return $ergebnis;
        } catch (Exception $ex) {
            print_r($ex);
            $this->db->rollBack();
            return array(-1);
        }
    }

}
