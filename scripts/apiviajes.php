<?php
$dir = is_dir('modelo') ? '' : '../';
include_once($dir . 'modelo/validar.php');
if(!isset($_SESSION['usuario'])) {
    header('HTTP/1.1 401');
    die('No Autorizado');
}
if (!peticion_ajax()) {
    header('HTTP/1.1 401');
    die(json_encode(array('exito' => false, 'msg' => 'No Autorizado')));
}
if (!isset($_POST['param'])) {
    header('HTTP/1.1 401');
    die(json_encode(array('exito' => false, 'msg' => 'No Autorizado')));
}

include_once($dir . 'modelo/viaje.php');

$tarea = $_POST['param'];

if (isset($_POST['idViaje'])) {
    $idViaje = $_POST['idViaje'];
}
if (isset($_POST['fecha'])) {
    $fecha = $_POST['fecha'];
}
if (isset($_POST['hora'])) {
    $hora = $_POST['hora'];
}
if (isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];
}
if (isset($_POST['origen'])) {
    $origen = $_POST['origen'];
}
if (isset($_POST['destino'])) {
    $destino = $_POST['destino'];
}
if (isset($_POST['observa'])) {
    $observa = $_POST['observa'];
}
if (isset($_POST['idReserva'])) {
    $idReserva = $_POST['idReserva'];
}
if (isset($_POST['idServicio'])) {
    $idServicio = $_POST['idServicio'];
}
if (isset($_POST['horaLibre'])) {
    $horaLibre = $_POST['horaLibre'];
}
if (isset($_POST['importe'])) {
    $importe = $_POST['importe'];
}
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];
}
if (isset($_POST['idVehiculos'])) {
    $idVehiculos = $_POST['idVehiculos'];
}
if (isset($_POST['fechaHasta'])) {
    $fechaHasta = $_POST['fechaHasta'];
}
if (isset($_POST['idChofer'])) {
    $idChofer = $_POST['idChofer'];
}

switch ($tarea) {
    case 1:
        $oviaje = new Viaje;
        $oviaje->setIdServicio($idServicio);
        $retorno = $oviaje->listarViajes();
        break;
    case 2:
        $oviaje = new Viaje;
        $oviaje->setFecha($fecha);
        $oviaje->setHora($hora);
        $oviaje->setIdCliente($idCliente);
        $oviaje->setOrigen($origen);
        $oviaje->setDestino($destino);
        $oviaje->setObserva($observa);
        $oviaje->setIdReserva($idReserva);
        $oviaje->setIdServicio($idServicio);
        $retorno = $oviaje->asignarViaje();
        break;
    case 3:
        $oviaje = new Viaje;
        $oviaje->setFecha($fecha);
        $oviaje->setHora($hora);
        $oviaje->setIdCliente($idCliente);
        $oviaje->setOrigen($origen);
        $oviaje->setDestino($destino);
        $oviaje->setObserva($observa);
        $oviaje->setIdServicio($idServicio);
        $oviaje->setHoraLibre($horaLibre);
        $oviaje->setImporte($importe);
        $retorno = $oviaje->agregarViaje();
        break;
    case 4:
        $oviaje = new Viaje;
        $oviaje->setIdViaje($idViaje);
        $retorno = $oviaje->cancelarViaje();
        break;
    case 5:
        $oviaje = new Viaje;
        $oviaje->setIdViaje($idViaje);
        $retorno = $oviaje->buscarViaje();
        break;
    case 6:
        $oviaje = new Viaje;
        $oviaje->setIdViaje($idViaje);
        $oviaje->setFecha($fecha);
        $oviaje->setHora($hora);
        $oviaje->setIdCliente($idCliente);
        $oviaje->setOrigen($origen);
        $oviaje->setDestino($destino);
        $oviaje->setObserva($observa);
        $oviaje->setHoraLibre($horaLibre);
        $oviaje->setImporte($importe);
        $retorno = $oviaje->actualizarViaje();
        break;
    case 7:
        $oviaje = new Viaje;
        $retorno = $oviaje->informeViajes($idVehiculos, $fecha, $fechaHasta, $idChofer);
        break;
    case 8:
        $oviaje = new Viaje;
        $retorno = $oviaje->buscarDestino($idChofer);
        break;
    default:
        break;
}

if ($retorno['exito'] == true) {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($retorno);
} else {
    header('HTTP/1.1 500');
    die(json_encode($retorno));
}

function peticion_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}
