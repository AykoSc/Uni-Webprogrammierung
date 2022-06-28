const zielDatum = new Date("Dec 24, 2022 23:59:59").getTime();
const sekundenZaehler = setInterval(function () {
    const differenzZuJetzt = zielDatum - new Date().getTime();
    const tage = Math.floor(differenzZuJetzt / (1000 * 60 * 60 * 24));
    const stunden = Math.floor((differenzZuJetzt % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minuten = Math.floor((differenzZuJetzt % (1000 * 60 * 60)) / (1000 * 60));
    const sekunden = Math.floor((differenzZuJetzt % (1000 * 60)) / 1000);
    if (differenzZuJetzt < 0) {
        document.getElementById("aktionscountdown").innerHTML = "Die Auktion für das Gemälde wurde bereits beendet.";
        clearInterval(sekundenZaehler);
    } else {
        document.getElementById("aktionscountdown").innerHTML = "Die Auktion für das Gemälde startet in " + tage + " Tagen, " + stunden + " Stunden, " + minuten + " Minuten, " + sekunden + " Sekunden.";
    }
}, 1000);