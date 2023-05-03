console.log("Script loaded");

// luisteren of er op de knop is wordt gedrukt
document.getElementById('btnSelect').addEventListener('click', getData);

// hide results
window.addEventListener('DOMContentLoaded', () => {
    document.getElementById('resultaat').style.display = "none";
});

// async await functie die gestart wordt als op de knop is gedrukt
async function getData() {

    // ophalen start en eind datum
    var startDatum = document.getElementById("startdatum").value;
    var eindDatum = document.getElementById("einddatum").value;
    var apikey = document.getElementById("apikey").value;
    console.log(apikey);
    console.log(startDatum, eindDatum);

    // Een variabele maken die verwijst naar de tabelbody in je DOM
    let tbody = document.getElementById("tabelBody");

    // Vorige info verwijderen
    while (tbody.hasChildNodes()) {
        tbody.removeChild(tbody.firstChild);
    }

    // Geldige input ?
    if (startDatum < eindDatum) {
        // Resultaat div visueel maken
        document.getElementById('resultaat').style.display = "block";
        
        // In docker we cannot access the API via localhost as this refers to the
        // docker container itself.
        // However when we use docker-compose we get "free" name resolution based on the container name.
        let url = "http://server:8000/webapi.php?start="+startDatum+"&end="+eindDatum+"&apikey="+apikey;
        console.log(url); 
        let response = await fetch(url);
        console.log(response);      

        let json = await response.json();
        console.log(json);

        // de keys en values uit de array halen
        var inputKeys = Object.keys(json);
        var inputValues = Object.values(json);

        

        // Voor elk element aanwezig in het object een rij toevoegen aan je tabel
        for (let i in inputKeys) {
            // toevoegen van een rij aan de tabel
            let row = tbody.insertRow(-1);
            // toevoegen van een cell met de datum 
            let cell = row.insertCell(-1);
            let datum = document.createTextNode(inputValues[i]['date']);
            cell.appendChild(datum);
            // toevoegen van een cell met de koers
            cell = row.insertCell(-1);
            // Hier gebruik ik Math.round om de waarde visueel af te ronden in je tabel
            let co2 = document.createTextNode(Math.round(inputValues[i]['co2']));
            cell.appendChild(co2);
            // toevoegen van een cell met de koers
            cell = row.insertCell(-1);
            // Hier gebruik ik Math.round om de waarde visueel af te ronden in je tabel
            let temp = document.createTextNode(Math.round(inputValues[i]['temp']));
            cell.appendChild(temp);
            // toevoegen van een cell met de koers
            cell = row.insertCell(-1);
            // Hier gebruik ik Math.round om de waarde visueel af te ronden in je tabel
            let humi = document.createTextNode(Math.round(inputValues[i]['humi']));
            cell.appendChild(humi);
        };
    }
        else {
        // foutmelding
    }
};



