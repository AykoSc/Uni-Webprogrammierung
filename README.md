Team DI-E
Namen der Studierenden:

- Brüggemann, Jonas
- Schwedler, Ayko
- Pollak, Jan Niklas

Abgabe zu Aufgabenblatt 6

Hinweise:

Login-Daten:

- Email: test1@test.com, Passwort: test1!
- Email: test2@test.com, Passwort: test2!

JavaScript:

- Filter bei Suchanfragen wird direkt angewandt nach Auswahl ✅
- Countdown Uhr für Auktion auf index.php implementiert ✅
- Bei einer Suchanfrage werden nach einzelnen Tastatureingaben Teilergebnisse geliefert. ✅
- Bei der Registrierung wird unmittelbar überprüft, ob es einen geforderten Benutzernamen schon gibt. ✅
- Veränderung in der Kommentarsektion werden, falls möglich über Ajax an den Server gesendet.
  Fehler behoben: Es wird nicht die ganze Seite neu geladen, sondern nur die Kommentar-Sektion ✅

Erklärung Gemälde:

- Mit Gemälde meinen wir Bilder, keine Textdokumente für ASCII-art.

Anmeldung:

- Genauere Informationen über Fehler bei z.B. Anmeldung werden nicht an den Nutzer weiterleiten aufgrund von
  Datenschutz.

Cookies:

- Es wird nicht über Cookies informiert, da wir nur technisch notwendigen Cookies verwenden (Speicherung der ID des
  Nutzers).

Webservices und APIs

- Die API für Währungskurswechsel wird auf index.php verwendet, um ein Euro-Preis in einen USD-Preis umzurechnen.
  Diese API ist auf 5 Aufrufe pro Minute begrenzt. Bei Inbetriebnahme würde man eine Kreditkarte hinterlegen, um dies zu erhöhen.
  Falls die API grade nicht verfügbar ist, wird einfach nur der Euro-Preis angezeigt.
  Quelle/Dokumentation: https://www.alphavantage.co/documentation/#currency-exchange