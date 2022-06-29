    $(document).ready(function() {
    console.log("drin");

        $("#comment_section form").submit(function (event) {
            console.log("drin1");

            event.preventDefault();

            console.log("drin2");

            var ajaxRequest;

            var values = $(this).serialize();

            /* Send the data using post and put the results in a div.*/
            /* I am not aborting the previous request, because it's an
               asynchronous request, meaning once it's sent it's out
               there. But in case you want to abort it you can do it
               by abort(). jQuery Ajax methods return an XMLHttpRequest
               object, so you can just use abort().*/
            ajaxRequest = $.ajax({
                url: "",//aktueller Pfad
                type: "post",
                data: values
            });

            /*  Request can be aborted by ajaxRequest.abort()*/

            ajaxRequest.done(function () {
                //Das hat so nicht den gewünschten Effekt, da so auch die gesamte Seite neu geladen wird.
                //Das Laden von nur den veränderten Inhalten, hat aber nach einer Aktion nicht mehr funktioniert,
                //bzw wurde nicht mehr in die on submit Methode gegangen, sodass der POST ganz normal ohne Javascript bearbeitet wurde.
                //$("#comment_section").load(location.href + " #comment_section");
                location.reload();

                console.log("wunderbar");
            });

            /* On failure of request this function will be called*/
            ajaxRequest.fail(function () {
            console.log("nicht wunderbar");
            });
        });
    });
