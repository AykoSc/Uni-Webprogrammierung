# Team DI-E - Finale Abgabe

### Namen aller beteiligter Studierenden

- Brüggemann, Jonas
- Schwedler, Ayko
- Pollak, Jan Niklas

### Hinweise

- **Voraussetzungen und andere zum Betrieb der Website notwendigen Informationen**
  - Die php.ini benötigt: Die Extensions sqlite3 und pdo_sqlite
  - Login-Daten
    - Email: test1@test.com, Passwort: test1!
    - Email: test2@test.com, Passwort: test2!

- **Sitemap**
  - ![](images\sitemap.png)
  - Erklärung der Sitemap (Alle Seitennamen enden auf .php):
    - index: Dies ist die Startseite, hier wird ein bald zu versteigerndes Gemälde gezeigt, mit einem live Countdown bis
      zur Versteigerung. Zudem wird der Startpreis in Euro und umgerechnet in Dollar angezeigt.
    - ausstellung: Hier werden die Gemälde ausgestellt. Hier gibt es die Möglichkeit, die Ergebnisse nach bestimmten
      Kriterien zu sortieren. Zudem gibt es hier auch das Suchfeld, in dem Namen von Gemälden als Suchbegriff eingegeben
      werden können.
    - sammlungen: Hier werden die Sammlungen angezeigt. Hier gibt es die Möglichkeit, die Ergebnisse nach bestimmten
      Kriterien zu sortieren. Zudem gibt es hier auch das Suchfeld, in dem Namen von Sammlungen als Suchbegriff
      eingegeben werden können.
    - neuereintrag: Auf dieser Seite wird ein Formular angezeigt, in welchem ein neues Gemälde oder eine neue Sammlung
      vom Aussteller erstellt werden kann. Wenn Gemälde ausgewählt wird, muss dort die Bilddatei hochgeladen, sowie der
      Titel des Gemäldes und der Künstler angegeben werden. Wenn Sammlung ausgewählt wird, muss der Titel der Sammlung
      und mindestens ein Gemälde, welches der Sammlung hinzugefügt werden soll, ausgewählt werden (auch via Suchfunktion
      möglich zur einfacheren Handhabung). Bei beiden Optionen können weitere optionale Felder ausgefüllt werden (z.B.
      Beschreibung).
    - gemaelde: Hier wird ein Gemälde und deren zugehörigen Informationen angezeigt, die vom Eigentümer bearbeitet
      werden können. Zudem gibt es noch eine Kommentarsektion.
    - sammlung: Hier wird eine Sammlung und deren zugehörigen Informationen angezeigt, die vom Eigentümer bearbeitet
      werden können.

    - profil: Hier werden Informationen zu dem entsprechenden Aussteller angezeigt und seine Gemälde und Sammlungen
      dargestellt. Wenn der Aussteller sein eigenes Profil betrachtet, kann er dieses bearbeiten und weitere optionale
      Felder ausfüllen. Diese optionalen Felder sind u.a. „Beschreibung”, „Geschlecht“, „Vollständiger Name“, „Adresse“,
      „Sprache“ und „Geburtsdatum“.
    - anmeldung: Hier gibt es die Pflichtfelder „E-Mail-Adresse“ und „Passwort“. Anschließend wird der angemeldete
      Aussteller zu index weitergeleitet.
    - abmeldung (keine Seite an sich): Hier kann ein angemeldeter Nutzer sich abmelden.
    - registrierung: Hier kann ein Gast sich registrieren. Es müssen Nutzername, E-Mail-Adresse und Passwort angegeben
      werden. Die Registrierung muss per E-Mail bestätigt werden. Danach wird der Nutzer zum Aussteller.

    - ueberuns: Hier wird ein einleitender Text angezeigt, welcher ausdrückt, wer wir sind und was der Zweck /
      Entstehungsgrund dieser Website ist. Zudem ist auf einer interaktiven Karte einsehbar, wo wir uns befinden.
    - nutzungsbedingungen: Hier wird ein Text angezeigt, welcher die Nutzungsbedingungen ausdrückt.
    - impressum: Hier wird angezeigt, wer diese Website erstellt hat und für welche Organisation diese Personen
      arbeiten.
    - kontakt: Hier existiert ein Formular, mit welchem wir erreicht werden können.
    - datenschutz: Hier wird aufgezählt, wie die Daten der Nutzer geschützt werden.

- **Stichwortartige Auflistung aller Funktionalitäten**
  - Registrieren: Nutzer sollen sich registrieren können (Nutzername, E-Mail-Adresse und Password). Das Datum der
    Registrierung wird dabei gespeichert.
  - Anmelden: Registrierte Nutzer sollen sich anmelden können (E-Mail-Adresse und Password).
  - Abmelden: Angemeldete Nutzer sollen sich wieder abmelden können.

  - Gemälde erstellen: Angemeldete Nutzer sollen neue Gemälde erstellen können (Eingabe von dem Namen des Gemäldes,
    Künstler, Bilddatei, Beschreibung, Erstellungsdatum und Ort). Das Datum des Hochladens wird dabei gespeichert.
  - Gemälde löschen: Angemeldete Nutzer können ihre eigenen Gemälde löschen. Insbesondere werden diese daraufhin auch
    aus allen anderen Sammlungen gelöscht.
  - Gemälde bearbeiten: Angemeldete Nutzer sollen ihre hochgeladenen Gemälde bearbeiten können, um so z.B. die
    Beschreibung des Bildes zu ändern.
  - Gemälde sortieren: Die Liste der Gemälde soll nach Anfertigungsdatum und Beliebtheit sortierbar sein.
  - Gemälde suchen: Alle Nutzer sollen eine Suchfunktion nutzen können (benötigt textliche Eingabe), welche direkte
    Ergebnisse liefert (Gemäldename) oder nach Bestätigung ausführlichere Ergebnisse.
  - Gemälde bewerten: Angemeldete Nutzer sollen Gemälde anhand von 5 Sternen bewerten können.

  - Sammlung erstellen: Angemeldete Nutzer sollen neue Sammlungen erstellen können (Angabe der Gemälde, Titel und der
    Beschreibung). Das Datum der Erstellung wird dabei gespeichert.
  - Sammlung löschen: Angemeldete Nutzer können ihre Sammlungen löschen.
  - Sammlung anzeigen: Alle Nutzer sollen sich eine Auflistung aller Sammlungen anzeigen lassen können (Anzeige des
    Vorschaubildes, Titel, Bewertung, Aufrufe, Beschreibung, Hochladedatum).
  - Sammlung bearbeiten: Angemeldete Nutzer sollen ihre eigenen Sammlungen bearbeiten können, um so z. B. die
    Beschreibung der Sammlung anzupassen.
  - Sammlungen sortieren: Die Liste der Sammlungen soll nach Anfertigungsdatum und Beliebtheit sortierbar sein.
  - Sammlungen suchen: Alle Nutzer sollen eine Suchfunktion nutzen können (benötigt textliche Eingabe), welche direkte
    Ergebnisse liefert (Sammlungsname) oder nach Bestätigung ausführlichere Ergebnisse.
  - Sammlung bewerten: Angemeldete Nutzer sollen Sammlungen anhand von 5 Sternen bewerten können.

  - Kommentar erstellen: Angemeldete Nutzer sollen unter Gemälden oder Sammlungen Kommentare erstellen können (
    Textlicher Inhalt). Das Datum der Erstellung wird dabei gespeichert.
  - Kommentar löschen: Angemeldete Nutzer können ihre eigenen Kommentare löschen.
  - Kommentare anzeigen: Alle Nutzer sollen Kommentare unter Gemälden einsehen können.
  - Kommentar liken: Angemeldete Nutzer sollen Kommentare liken können.

  - Profil anzeigen: Nutzer können Profile einsehen (Beschreibung, Geschlecht, vollständiger Name, Adresse, Sprache,
    Geburtsdatum). Zudem werden alle Sammlungen und Gemälde des Anbieters angezeigt.
  - Profil bearbeiten: Angemeldete Nutzer sollen ihr Profil bearbeiten und dabei weitere Daten angeben können (
    Beschreibung, Geschlecht, vollständiger Name, Adresse, Sprache, Geburtsdatum).
  - Profil löschen: Angemeldete Nutzer sollen ihr Profil löschen können. Daraufhin werden auch alle weiteren Daten des
    Nutzers (u.a. erstellte Gemälde, Sammlungen und Kommentare) gelöscht.

  - Kontakt aufnehmen: Nutzer sollen Kontakt aufnehmen können über ein Kontaktformular (E-Mail, Kommentar).

  - Alle Nutzer sollen sich das Impressum, Informationen über uns, Nutzungsbedingungen und Datenschutz anzeigen lassen
    können.

- **Nicht umgesetzte Teilaufgaben**
  - keine

- **Bekannte Fehler oder Mängel**
  - keine

- **Besonderheiten, die über die eigentlichen Aufgaben hinaus berücksichtigt bzw. integriert wurden**
  - Gemälde und Sammlungen können mit Sternen bewertet werden, nicht nur anhand einer eingegebenen Zahl

### JavaScript

- Filter bei Suchanfragen wird direkt angewandt nach Auswahl.
- Countdown Uhr für Auktion auf index.php implementiert.
- Bei einer Suchanfrage werden nach einzelnen Tastatureingaben Teilergebnisse geliefert.
- Bei der Registrierung wird unmittelbar überprüft, ob es einen geforderten Benutzernamen schon gibt.
- Veränderung in der Kommentarsektion werden über Ajax an den Server gesendet.
- Veränderung an der eigenen Bewertung zu einem Gemälde oder einer Sammlung werden über Ajax an den Server gesendet.

### Erklärung Gemälde

- Mit Gemälde meinen wir Bilder, keine Textdokumente für ASCII-art.

### Anmeldung

- Genauere Informationen über Fehler bei z.B. Anmeldung werden nicht an den Nutzer weitergeleitet aufgrund von
  Datenschutz.

### Cookies

- Es wird nicht über Cookies informiert (kein Disclaimer), da wir nur technisch notwendigen Cookies verwenden (
  Speicherung der ID des
  Nutzers).

### Webservices und APIs

- Die API für Währungskurswechsel wird auf index.php verwendet, um ein Euro-Preis in einen USD-Preis umzurechnen.
  Diese API ist auf 5 Aufrufe pro Minute begrenzt. Bei Inbetriebnahme würde man eine Kreditkarte hinterlegen, um dies zu
  erhöhen.
  Falls die API grade nicht verfügbar ist, wird einfach nur der Euro-Preis angezeigt.
  Quelle/Dokumentation: https://www.alphavantage.co/documentation/#currency-exchange
- Zudem wurde in der ueberuns.php eine Karte per OpenStreetMap eingebunden.