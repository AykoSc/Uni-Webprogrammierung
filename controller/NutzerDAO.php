<?php

interface NutzerDAO
{
    public function registrieren($nutzername, $email, $passwort): bool;

    /**
     * Funktion zum Anmelden auf unserer Webseite.
     *
     * @param $email String E-Mail des Nutzers
     * @param $passwort String Passwort des Nutzers
     * @return array<int, String> Nutzer-ID und Token
     */
    public function anmelden($email, $passwort): array;

    public function gemaelde_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function gemaelde_editieren($beitrag_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function gemaelde_entfernen($beitrag_id): bool;

    public function ausstellung_suche($input): bool;

    public function sammlungen_suche($input): bool;
}