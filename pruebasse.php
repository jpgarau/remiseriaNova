<?php
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
// include_once 'modelo/validar.php';
include_once 'modelo/viaje.php';

$oviaje = new Viaje;
$idChofer = $_GET['id'];

$mysqli = Conexion::abrir();
$mysqli->set_charset('utf8');
$sql = 'SELECT idViaje, origen FROM viajes WHERE idViaje = (SELECT max(idViaje) FROM viajes) AND estado=1';
$stmt = $mysqli->prepare($sql);
if($stmt!==FALSE){
    $stmt->execute();
    $res = $stmt->get_result();
    $id = $res->fetch_assoc();
    $last = $id['idViaje'];
    $stmt->close();
}
// $counter = rand(1, 10);
while (1) {
  // Every second, sent a "ping" event.
    $stmt = $mysqli->prepare($sql);

    if($stmt!==FALSE){
        $stmt->execute();
        $res = $stmt->get_result();
        $id = $res->fetch_assoc();
        $actual = $id['idViaje'];
        $origen = $id['origen'];
        $stmt->close();
    }
    if($actual !== $last){
        echo "event: ping\n";
        echo 'data: {"origen": "' . $origen . '"}';
        echo "\n\n";
        $last = $actual;
    }
  
  // Send a simple message at random intervals.
  
//   $counter--;
  
//   if (!$counter) {
//     echo 'data: This is a message at time ' . $curDate . "\n\n";
//     $counter = rand(1, 10);
//   }
  
  ob_flush();
  flush();
  sleep(1);
}
?>
