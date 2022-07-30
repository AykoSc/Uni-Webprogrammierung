# Team DI-E - Finale Abgabe

### Namen aller beteiligter Studierenden

- Brüggemann, Jonas
- Schwedler, Ayko
- Pollak, Jan Niklas

### Hinweise

- Voraussetzungen (php.ini, …) und andere zum Betrieb der Website notwendigen Informationen (Admin, Kennwörter, …)
  - Die php.ini benötigt: sqlite3, sqlite und dao
- Stichwortartige Auflistung aller Funktionalitäten bzw. eine Sitemap
  - Sitemap: ![](images\Sitemap.png)
  - Erklärung der Sitemap (Alle Seitennamen enden auf .php):
    - Die Hauptseite ist _index_, hier wird ein bald zu versteigerndes Gemälde gezeigt
    - Bei ausstellung werden alle Gemälde nach Such- oder Filterkriterien gezeigt
      - Nach Klick auf ein Gemälde landet man auf _gemaelde_, hier werden das Gemälde in groß sowie alle Informationen
        zu diesem gezeigt. Wenn einem selber das Gemälde gehört, kann man gewisse Informationen verändern.
    - Bei sammlungen werden alle Sammlungen nach Such- oder Filterkriterien gezeigt
      - Nach Klick auf eine Sammlung landet man auf _sammlung_, hier werden alle Gemälde der Sammlung in richtiger
        Reihenfolge sowie Informationen zu der Sammlung gezeigt. Wenn einem selber die Sammlung gehört, kann man gewisse
        Informationen verändern.
    - _neuereintrag_ ermöglicht es einem, ein neues Gemälde oder eine neue Sammlung anzulegen (wenn man angemeldet ist)
- Wurden Teilaufgaben nicht umgesetzt, diese bitte angeben
  - Bei Filter keine Auswahl möglich zum aufsteigend oder absteigend filtern
  - Keine Profilbilder möglich
  - Keine Gemälde von einer Sammlung nach Erstellung entfernbar oder hinzufügbar
- Sind Fehler oder Mängel bekannt, diese bitte angeben
-
- Besonderheiten, die über die eigentlichen Aufgaben hinaus berücksichtigt bzw. integriert wurden, bitte angeben
  - Gemälde und Sammlungen können mit Sternen bewertet werden

### Login-Daten

- Email: test1@test.com, Passwort: test1!
- Email: test2@test.com, Passwort: test2!

### JavaScript

- Filter bei Suchanfragen wird direkt angewandt nach Auswahl.
- Countdown Uhr für Auktion auf index.php implementiert.
- Bei einer Suchanfrage werden nach einzelnen Tastatureingaben Teilergebnisse geliefert.
- Bei der Registrierung wird unmittelbar überprüft, ob es einen geforderten Benutzernamen schon gibt.
- Veränderung in der Kommentarsektion werden über Ajax an den Server gesendet.

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