<?php

interface NutzerDAO
{
    /**
     * Funktion zum Registrieren.
     *
     * @param $nutzername String Nutzername des Nutzers
     * @param $email String E-Mail des Nutzers
     * @param $passwort String Passwort des Nutzers
     * @return bool Registrierung erfolgreich
     */
    public function registrieren(string $nutzername, string $email, string $passwort): bool;

    /**
     * Funktion zum Anmelden.
     *
     * @param $email String E-Mail des Nutzers
     * @param $passwort String Passwort des Nutzers
     * @return array<int, String> Nutzer-ID und Token
     */
    public function anmelden(string $email, string $passwort): array;

    /**
     * Funktion zum Abmelden.
     *
     * @param $nutzerID int ID des Nutzers
     * @param $nutzerToken String Token der Session des Nutzers
     * @return bool Abmeldung erfolgreich
     */
    function abmelden(int $nutzerID, string $nutzerToken): bool;

    public function ausstellung_suche($input): bool;

    public function sammlungen_suche($input): bool;

    public function gemaelde_anlegen($datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): int;

    public function gemaelde_editieren($gemaelde_id, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function gemaelde_entfernen($gemaelde_id): bool;

    public function sammlung_anlegen($gemaelde, $titel, $beschreibung): int;

    public function sammlung_editieren($sammlung_id, $gemaelde, $titel, $beschreibung): bool;

    public function sammlung_entfernen($sammlung_id): bool;

    public function kommentar_anlegen($inhalt, $sammlung_id, $gemaelde_id): bool;

    public function kommentar_entfernen($gemaelde_id): bool;
}