let idChofer = 1;
var source = new EventSource("pruebasse.php?id="+idChofer);
source.onmessage = function(event) {
  document.getElementById("result").innerHTML += event.data + "<br>";
}; 

source.addEventListener("ping", function(e) {
  var obj = JSON.parse(e.data);
  document.getElementById("result").innerHTML += "Proximo Destino " + obj.origen + "<br>";
}, false);