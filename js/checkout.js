let last = document.getElementById('form');
let sp2 = last.lastElementChild;

let parentDiv = sp2.parentElement;
let totale = document.createElement("p");
totale.innerHTML="Prezzo Totale: 0.00€";
parentDiv.insertBefore(totale, sp2);

document.getElementById("costo1").lastChild.nodeValue;
document.getElementById("tipoposto").value;

document.getElementById('nbiglietti').value = 1;

document.getElementById('nbiglietti').onchange = function(){
    tot = (parseFloat(document.getElementById("costo" + document.getElementById("tipoposto").value).lastChild.nodeValue.substring(0,5))*document.getElementById('nbiglietti').value).toFixed(2);
    totale.innerHTML = "Prezzo Totale: " + tot + "€";
}


document.getElementById('tipoposto').onchange = function(){
    document.getElementById('nbiglietti').onchange();
}

document.getElementById('nbiglietti').onchange();

// TODO: #7 separazione struttura comportamento per checkout