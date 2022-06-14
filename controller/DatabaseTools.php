<?php

class DatabaseTools
{

    public static $createDatabase =
        "CREATE TABLE Anbieter (
                    AnbieterID TEXT,
                    Nutzername TEXT,
                    Email TEXT,
                    Passwort TEXT,
                    Personenbeschreibung TEXT,
                    Geschlecht TEXT,
                    Vollstaendiger_Name TEXT,
                    Anschrift TEXT,
                    Sprache TEXT,
                    Geburtsdatum TEXT,
                    Registrierungsdatum TEXT,
                    PRIMARY KEY (AnbieterID)
                );
                
                CREATE TABLE Gemaelde (
                    GemaeldeID TEXT,
                    Bilddatei TEXT,
                    Titel TEXT,
                    Kuenstler TEXT,
                    Beschreibung TEXT,
                    Erstellungsdatum TEXT,
                    Ort TEXT,
                    Bewertung TEXT,
                    Hochladedatum TEXT,
                    AnbieterID TEXT,
                    PRIMARY KEY (GemaeldeID),
                    FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
                );
                
                CREATE TABLE Sammlung (
                    SammlungID TEXT,
                    Titel TEXT,
                    Vorschaubild TEXT,
                    Beschreibung TEXT,
                    Bewertung TEXT,
                    Erstellungsdatum TEXT,
                    AnbieterID TEXT,
                    PRIMARY KEY (SammlungID),
                    FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
                );
                
                CREATE TABLE Kommentare (
                    KommentarID TEXT,
                    Likeanzahl TEXT,
                    Textinhalt TEXT,
                    Erstellungsdatum TEXT,
                    AnbieterID TEXT,
                    GemaeldeID TEXT,
                    PRIMARY KEY (KommentarID),
                    FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
                );
                
                CREATE TABLE Kontakt (
                    KontaktID TEXT,
                    Kommentar TEXT,
                    EMail TEXT,
                    PRIMARY KEY (KontaktID)
                );
                
                CREATE TABLE Tokens (
                    AnbieterID TEXT NOT NULL,
                    Tokennummer TEXT NOT NULL,                    
                    PRIMARY KEY (AnbieterID, Tokennummer),
                    FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE NO ACTION ON UPDATE CASCADE
                );
                
                CREATE TABLE gehoert_zu (
                    GemaeldeID TEXT NOT NULL,
                    SammlungID TEXT NOT NULL,
                    PRIMARY KEY (GemaeldeID, SammlungID),
                    FOREIGN KEY (GemaeldeID) REFERENCES Gemaelde (GemaeldeID) ON DELETE CASCADE ON UPDATE CASCADE,
                    FOREIGN KEY (SammlungID) REFERENCES Sammlung (SammlungID) ON DELETE CASCADE ON UPDATE CASCADE                    
                );";
    private SQLite3 $db;
}