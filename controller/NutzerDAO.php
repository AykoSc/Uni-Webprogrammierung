<?php

interface NutzerDAO
{
    public function nutzername_unbenutzt($Nutzername): bool;

    public function registrieren($Nutzername, $Email, $Passwort): bool;

    public function registrieren_bestaetigen($Email, $Verifizierungscode): bool;

    public function anmelden($Email, $Passwort): array;

    public function abmelden($AnbieterID, $Tokennummer): bool;

    public function kontakt_aufnehmen($EMail, $Kommentar): bool;

    public function gemaelde_anlegen($AnbieterID, $Tokennummer, $Dateityp, $Titel, $Kuenstler, $Beschreibung, $Erstellungsdatum, $Ort): int;

    public function gemaelde_editieren($AnbieterID, $Tokennummer, $GemaeldeID, $Beschreibung, $Erstellungsdatum, $Ort): bool;

    public function gemaelde_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool;

    public function gemaelde_erhalten($GemaeldeID): array;

    public function gemaelde_bewerten($AnbieterID, $Tokennummer, $GemaeldeID, $Bewertung): bool;

    public function eigene_gemaelde_bewertung_erhalten($AnbieterID, $GemaeldeID): int;

    public function sammlung_anlegen($AnbieterID, $Tokennummer, $Auswahl, $Titel, $Beschreibung): int;

    public function sammlung_editieren($AnbieterID, $Tokennummer, $SammlungID, $Titel, $Beschreibung): bool;

    public function sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID): bool;

    public function sammlung_erhalten($SammlungID): array;

    public function eigene_sammlung_bewertung_erhalten($AnbieterID, $SammlungID): int;

    public function sammlung_bewerten($AnbieterID, $Tokennummer, $SammlungID, $Bewertung): bool;

    public function kommentar_anlegen($AnbieterID, $Tokennummer, $Textinhalt, $GemaeldeID): bool;

    public function kommentar_entfernen($AnbieterID, $Tokennummer, $KommentarID): bool;

    public function kommentar_liken($AnbieterID, $Tokennummer, $KommentarID): bool;

    public function kommentare_erhalten($GemaeldeID, $AnbieterID, $Tokennummer): array;

    public function profil_erhalten($AnbieterID): array;

    public function profil_editieren($AnbieterID, $Tokennummer, $Personenbeschreibung, $Geschlecht, $Vollstaendiger_Name, $Anschrift, $Sprache, $Geburtsdatum): bool;

    public function profil_entfernen($AnbieterID, $Tokennummer, $GemaeldeID): bool;

    public function sammlungen_von_anbieter_erhalten($AnbieterID): array;

    public function gemaelde_von_anbieter_erhalten($AnbieterID): array;

    public function ausstellung_erhalten($Suche, $Filter): array;

    public function sammlungen_erhalten($Suche, $Filter): array;

    public function gemaelde_aus_sammlung_entfernen($AnbieterID, $Tokennummer, $SammlungID, $GemaeldeID): bool;
}