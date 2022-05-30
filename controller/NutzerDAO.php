<?php

interface NutzerDAO
{
    public function registrieren($benutzername, $email, $passwort): bool;

    public function anmelden($email, $passwort): bool;

    public function beitrag_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function beitrag_editieren($beitrag_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function beitrag_entfernen($beitrag_id): bool;

    public function ausstellung_suche($input): bool;

    public function sammlungen_suche($input): bool;
}

?>