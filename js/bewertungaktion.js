$(document).ready(function() {

    $( "#bewertung" ).on( "click",  "button",  function (event) {

        event.preventDefault();

        var ajaxRequest;

        var btnName = $(this).attr('name');
        var btnVal = $(this).val();
        var submitData = btnName + '=' + btnVal;

        /* Sendet die Daten mit post und packt die Ergebnisse in ein div.
           Ich breche die vorherige Anfrage nicht ab, da es sich um eine
           asynchrone Anfrage handelt, d.h. sobald sie gesendet wurde, ist sie
           raus. Aber falls Sie die Anfrage abbrechen wollen, können Sie das tun
           mit abort(). jQuery Ajax-Methoden geben ein XMLHttpRequest
           Objekt zurück, sodass Sie einfach abort() verwenden können. */
        ajaxRequest = $.ajax({
            url: "", //aktueller Pfad
            type: "post",
            data: submitData
        });

        ajaxRequest.done(function () {
            // Es wird nur die Bewertung neu geladen
            $("#bewertung").load(location.href + " #bewertung");

            console.log("Erfolg");
        });

        /* On failure of request this function will be called*/
        ajaxRequest.fail(function () {
            console.warn("Fehler");
        });
    });
});
