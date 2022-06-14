<?php

class DatabaseTools
{
    /**
     * @implNote In SQLite gibt es keine Datumsspeicherung, daher muss hier TEXT
     * benutzt werden im Format 'YYYY-MM-DD'
     * @implNote Wir verwenden nicht AUTOINCREMENT, sondern inkrementieren in den
     * aufrufenden Methoden
     */

    const CREATE_DATABASE = "
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
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
    );

    CREATE TABLE IF NOT EXISTS Sammlung (
        SammlungID INTEGER PRIMARY KEY AUTOINCREMENT,
        AnbieterID INTEGER,
        Titel TEXT,
        Beschreibung TEXT,
        Bewertung INTEGER,
        Erstellungsdatum TEXT,
        Aufrufe INTEGER,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS Kommentar (
        KommentarID INTEGER PRIMARY KEY AUTOINCREMENT,
        GemaeldeID INTEGER,
        AnbieterID INTEGER,
        Likeanzahl INTEGER,
        Textinhalt TEXT,
        Erstellungsdatum TEXT,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE SET NULL ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS Kontakt (
        KontaktID INTEGER PRIMARY KEY AUTOINCREMENT,
        Kommentar TEXT,
        EMail TEXT
    );
    
    CREATE TABLE IF NOT EXISTS Tokens (
        AnbieterID INTEGER PRIMARY KEY,
        Tokennummer TEXT PRIMARY KEY,
        FOREIGN KEY (AnbieterID) REFERENCES Anbieter (AnbieterID) ON DELETE NO ACTION ON UPDATE CASCADE
    );
    
    CREATE TABLE IF NOT EXISTS gehoert_zu (
        GemaeldeID INTEGER PRIMARY KEY,
        SammlungID INTEGER PRIMARY KEY,
        FOREIGN KEY (GemaeldeID) REFERENCES Gemaelde (GemaeldeID) ON DELETE CASCADE ON UPDATE CASCADE,
        FOREIGN KEY (SammlungID) REFERENCES Sammlung (SammlungID) ON DELETE CASCADE ON UPDATE CASCADE                    
    );


    INSERT INTO Anbieter (Nutzername, Email, Passwort, Personenbeschreibung, Geschlecht, Vollstaendiger_Name, Anschrift, Sprache, Geburtsdatum, Registrierungsdatum)
    VALUES ('test1', 'test1@test.com', 'test1!', 'Ich bin Test 1 !', 'm', 'Max Mustermann', 'Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg', 'deutsch', '04.10.2000', '01.06.2022');
    INSERT INTO Anbieter (Nutzername, Email, Passwort, Personenbeschreibung, Geschlecht, Vollstaendiger_Name, Anschrift, Sprache, Geburtsdatum, Registrierungsdatum)
    VALUES ('test2', 'test2@test.com', 'test2!', 'Ich bin der User Test 2 !', 'w', 'Maxine Musterfrau', 'Carl von Ossietzky Universität Oldenburg, Ammerländer Heerstraße 114-118, 26129 Oldenburg', 'deutsch', '01.11.2000', '28.05.2022');

    INSERT INTO Tokens (AnbieterID, Tokennummer)
    VALUES (0, 'mA23zbjdkENShbk9ezqNp5nQMpyrVb7m');
    INSERT INTO Tokens (AnbieterID, Tokennummer)
    VALUES (1, 'YRSPGgPjnDSuy7b5GuNFBEz9e4AAwaj7');
     
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe)
    VALUES (0, 'Stockbild0', 'Stockkünstler0', 'Beschreibung von Bild 0', '04.09.1900', 'München, Deutschland', 8, '07.10.2021', 56);
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe)
    VALUES (1, 'Stockbild1', 'Stockkünstler1', 'Beschreibung von Bild 1', '05.10.1234', 'Oldenburg, Deutschland', 9, '01.06.2022', 4);
    INSERT INTO Gemaelde (AnbieterID, Titel, Kuenstler, Beschreibung, Erstellungsdatum, Ort, Bewertung, Hochladedatum, Aufrufe)
    VALUES (1, 'Stockbild2', 'Stockkünstler2', 'Beschreibung von Bild 2', '06.11.1432', 'Berlin, Deutschland', 4, '06.09.2022', 8);

    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (0, 0, 274, 'Dies ist ein Kommentar!', '05.10.2021');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (0, 1, 346, 'Dies ist auch ein Kommentar!!', '07.06.2022');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (1, 1, 56, 'Mein erster Kommentar.', '02.03.2022');
    INSERT INTO Kommentar (GemaeldeID, AnbieterID, Likeanzahl, Textinhalt, Erstellungsdatum)
    VALUES (2, 0, 23, 'Mein toller Kommentar.', '01.06.2022');

    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (1, 'Sammlung0', 'Beschreibung von Bild 0', 3, '03.01.2021', 2234);
    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (1, 'Sammlung1', 'Beschreibung von Bild 1', 7, '06.04.2022', 34);
    INSERT INTO Sammlung (AnbieterID, Titel, Beschreibung, Bewertung, Erstellungsdatum, Aufrufe)
    VALUES (0, 'Sammlung2', 'Beschreibung von Bild 2', 5, '02.03.2022', 8673);
       
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (0, 0);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (0, 2);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (0, 1);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (1, 1);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (1, 0);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (2, 2);
    INSERT INTO gehoert_zu (GemaeldeID, SammlungID)
    VALUES (2, 0);
    ";
}