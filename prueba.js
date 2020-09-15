var source = new EventSource("pruebasse.php");
source.onmessage = function(event) {
  document.getElementById("result").innerHTML += event.data + "<br>";
}; 