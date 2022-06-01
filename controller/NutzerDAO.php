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
    public function registrieren($nutzername, $email, $passwort): bool;

    /**
     * Funktion zum Anmelden.
     *
     * @param $email String E-Mail des Nutzers
     * @param $passwort String Passwort des Nutzers
     * @return array<int, String> Nutzer-ID und Token
     */
    public function anmelden($email, $passwort): array;

    /**
     * Funktion zum Abmelden.
     *
     * @param $nutzerID int ID des Nutzers
     * @param $nutzerToken String Token der Session des Nutzers
     * @return bool Abmeldung erfolgreich
     */
    public function abmelden($nutzerID, $nutzerToken): bool;

    public function ausstellung_suche($input): bool;

    public function sammlungen_suche($input): bool;

    public function gemaelde_anlegen($id, $file, $beschreibung, $titel, $artist, $date, $location): bool;

    public function gemaelde_editieren($gemaeldeID, $datei, $beschreibung, $titel, $kuenstler, $erstellungsdatum, $ort): bool;

    public function gemaelde_entfernen($gemaeldeID): bool;

    public function gemaelde_erhalten($gemaeldeID): array;

    public function sammlung_anlegen($gemaelde, $titel, $beschreibung): int;

    public function sammlung_editieren($sammlungID, $gemaelde, $titel, $beschreibung): bool;

    public function sammlung_entfernen($sammlungID): bool;

    public function sammlung_erhalten($sammlungID): array;

    public function kommentar_anlegen($text, $gemaeldeID, $nutzerID): bool;

    public function kommentar_entfernen($nutzerID, $kommentarID): bool;

    public function kommentar_liken($nutzerID, $kommentarID): bool;

    public function kommentar_erhalten($gemaeldeID): array;

    public function profil_erhalten($nutzerID): array;

    public function ausstellung_erhalten($suche, $filter): array;

    public function sammlungen_erhalten($suche, $filter): array;
}