<?php

class DBErstellung
{
    const TABELLEN = "
    CREATE TABLE IF NOT EXISTS Anbieter (
        AnbieterID INTEGER PRIMARY KEY AUTOINCREMENT,
        Nutzername TEXT,
        Email TEXT,
        Passwort TEXT,
        Personenbeschreibung TEXT,
        Geschlecht TEXT,
        Vollstaendiger_Name TEXT,
        Anschrift TEXT,
        Sprache TEXT,
        Geburtsdatum TEXT,
        Registrierungsdatum TEXT
    );
    
    CREATE TABLE IF NOT EXISTS Gemaelde (
        GemaeldeID INTEGER PRIMARY KEY AUTOINCREMENT,
        AnbieterID INTEGER,
        Titel TEXT,
        Kuenstler TEXT,
        Beschreibung TEXT,
        Erstellungsdatum TEXT,
        Ort TEXT,
        Bewertung INTEGER,
        Hochladedatum TEXT,
        Aufrufe INTEGER,
        Dateityp TEXT,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE CASCADE ON UPDATE CASCADE
    );

    CREATE TABLE IF NOT EXISTS Sammlung (
        SammlungID INTEGER PRIMARY KEY AUTOINCREMENT,
        AnbieterID INTEGER,
        Titel TEXT,
        Beschreibung TEXT,
        Bewertung INTEGER,
        Erstellungsdatum TEXT,
        Aufrufe INTEGER,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE CASCADE ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS Kommentar (
        KommentarID INTEGER PRIMARY KEY AUTOINCREMENT,
        GemaeldeID INTEGER,
        AnbieterID INTEGER,
        Likeanzahl INTEGER,
        Textinhalt TEXT,
        Erstellungsdatum TEXT,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE CASCADE ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS Kontakt (
        KontaktID INTEGER PRIMARY KEY AUTOINCREMENT,
        Kommentar TEXT,
        EMail TEXT,
        Erstellungsdatum TEXT
    );
    
    CREATE TABLE IF NOT EXISTS Tokens (
        AnbieterID INTEGER,
        Tokennummer TEXT,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE CASCADE ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS gehoert_zu (
        GemaeldeID INTEGER,
        SammlungID INTEGER,
        FOREIGN KEY (GemaeldeID) REFERENCES Gemaelde (GemaeldeID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (SammlungID) REFERENCES Sammlung (SammlungID) ON DELETE CASCADE ON UPDATE CASCADE                    
    );


  CREATE TABLE IF NOT EXISTS geliked_von (
        KommentarID INTEGER,
        AnbieterID INTEGER,
        FOREIGN KEY (KommentarID) REFERENCES Kommentar (KommentarID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE CASCADE ON UPDATE CASCADE
    );

    ";

    const DATEN = "
    INSERT INTO Anbieter (Nutzername, Email, Passwort, Personenbeschreibung, Geschlecht, Vollstaendiger_Name, Anschrift, Sprache, Geburtsdatum, Registrierungsdatum)
    VALUES ('test1', 'test1@test.com', '', 'Ich bin Test 1 !', 'm', 'Max Mustermann', 'Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg', 'deutsch', '2000.10.04', '2022.06.02');
    INSERT INTO Anbieter (Nutzername, Email, Passwort, Personenbeschreibung, Geschlecht, Vollstaendiger_Name, Anschrift, Sprache, Geburtsdatum, Registrierungsdatum)
    VALUES ('test2', 'test2@test.com', '', 'Ich bin der User Test 2 !', 'w', 'Maxine Musterfrau', 'Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg', 'deutsch', '2000.11.01', '2022.05.28');
  
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe, Dateityp)
    VALUES (2, 'Stockbild0', 'Stockkünstler0', 'Beschreibung von Bild 0', '1900.09.04', 'München, Deutschland', 8, '2021.10.07', 56, 'jpg');
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe, Dateityp)
    VALUES (1, 'Stockbild1', 'Stockkünstler1', 'Beschreibung von Bild 1', '1234.10.05', 'Oldenburg, Deutschland', 9, '2022.06.01', 4, 'jpg');
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe, Dateityp)
    VALUES (1, 'Stockbild2', 'Stockkünstler2', 'Beschreibung von Bild 2', '1432.11.06', 'Berlin, Deutschland', 4, '2022.09.06', 8, 'jpg');

    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (1, 1, 274, 'Dies ist ein Kommentar!', '2021.10.05');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (1, 2, 346, 'Dies ist auch ein Kommentar!!', '2022.06.07');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (2, 1, 56, 'Mein erster Kommentar.', '2022.03.02');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (3, 1, 23, 'Mein toller Kommentar.', '2022.06.01');

    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (1, 'Sammlung1', 'Beschreibung von Sammlung 0', 3, '2021.01.03', 2234);
    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (1, 'Sammlung2', 'Beschreibung von Sammlung 1', 7, '2022.04.06', 34);
    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (2, 'Sammlung3', 'Beschreibung von Sammlung 2', 5, '2022.03.02', 8673);

    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (1, 1);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (1, 3);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (1, 2);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (2, 2);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (2, 1);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (3, 3);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (3, 1);
    ";
}