<?php

interface NutzerDAO
{
    public function registrieren($Nutzername, $Email, $Passwort): bool;

    public function anmelden($Email, $Passwort): array;

    public function abmelden($AnbieterID, $Tokennummer): bool;

    public function kontakt_aufnehmen($EMail, $Kommentar): bool;

    public function gemaelde_anlegen($AnbieterID, $Tokennummer, $Dateityp, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort): int;

    public function gemaelde_editieren($AnbieterID, $Tokennummer, $GemaeldeID, $Beschreibung, $Erstellungsdatum, $Ort): bool;

    public function gemaelde_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool;

    public function gemaelde_erhalten($GemaeldeID): array;

    public function sammlung_anlegen($AnbieterID, $Tokennummer, $Auswahl, $Titel, $Beschreibung): int;

    public function sammlung_editieren($AnbieterID, $Tokennummer, $SammlungID, $Titel, $Beschreibung): bool;

    public function sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID): bool;

    public function sammlung_erhalten($SammlungID): array;

    public function kommentar_anlegen($text, $gemaeldeID, $nutzerID): bool;

    public function kommentar_entfernen($AnbieterID, $token, $kommentarID): bool;

    public function kommentar_liken($AnbieterID, $token, $kommentarID): bool;

    public function kommentare_erhalten($gemaeldeID): array;

    public function profil_erhalten($nutzerID): array;

    public function profil_editieren($AnbieterID, $Token, $Personenbeschreibung, $Geschlecht, $Vollstaendiger_Name, $Anschrift, $Sprache, $Geburtsdatum): bool;

    public function ausstellung_erhalten($Suche, $Filter): array;

    public function sammlungen_erhalten($Suche, $Filter): array;
}