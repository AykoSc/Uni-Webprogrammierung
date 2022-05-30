<?php if (!isset($abs_path)) include_once "../path.php";

include $abs_path . "/controller/NutzerDAO.php";

class NutzerDAODummyImpl implements NutzerDAO
{

    public function __construct()
    {
        //TODO
    }

    public function registrieren($benutzername, $email, $passwort): bool
    {
        //TODO
    }

    public function anmelden($email, $passwort): bool
    {
        //TODO
    }

    public function beitrag_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool
    {
        //TODO
    }

    public function beitrag_editieren($beitrag_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool
    {
        //TODO
    }

    public function beitrag_entfernen($beitrag_id): bool
    {
        //TODO
    }

    public function ausstellung_suche($input): bool
    {
        //TODO
    }

    public function sammlungen_suche($input): bool
    {
        //TODO
    }
}

?>
