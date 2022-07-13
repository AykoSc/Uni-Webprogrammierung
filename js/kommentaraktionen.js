$(document).ready(function() {

    $( "#comment_section" ).on( "submit", "form",  function (event) {

        event.preventDefault();

        var ajaxRequest;

        var values = $(this).serialize();

        /* Sendet die Daten mit post und packt die Ergebnisse in ein div.
           Ich breche die vorherige Anfrage nicht ab, da es sich um eine
           asynchrone Anfrage handelt, d.h. sobald sie gesendet wurde, ist sie
           raus. Aber falls Sie die Anfrage abbrechen wollen, können Sie das tun
           mit abort(). jQuery Ajax-Methoden geben ein XMLHttpRequest
           Objekt zurück, sodass Sie einfach abort() verwenden können. */
        ajaxRequest = $.ajax({
            url: "", //aktueller Pfad
            type: "post",
            data: values
        });

        ajaxRequest.done(function () {
            // Es wird nur die Kommentar-Sektion neu geladen
            $("#comment_section").load(location.href + " #comment_section");

            console.log("Erfolg");
        });

        /* On failure of request this function will be called*/
        ajaxRequest.fail(function () {
            console.warn("Fehler");
        });
    });
});
