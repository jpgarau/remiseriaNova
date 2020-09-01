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

include_once($dir . 'modelo/reserva.php');

$tarea = $_POST['param'];
if (isset($_POST['idReserva'])) {
    $idReserva = $_POST['idReserva'];
}
if (isset($_POST['fecha'])) {
    $fecha = $_POST['fecha'];
}
if (isset($_POST['hora'])) {
    $hora = $_POST['hora'];
}
if (isset($_POST['fechah'])) {
    $fechah = $_POST['fechah'];
}
if (isset($_POST['finSemana'])) {
    $finSemana = $_POST['finSemana'];
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
if (isset($_POST['estado'])) {
    $estado = $_POST['estado'];
}

switch ($tarea) {
    case 1:
        $oreserva = new Reserva;
        $oreserva->setFecha($fecha);
        $retorno = $oreserva->listarfecha();
        break;
    case 2:
        $oreserva = new Reserva;
        $fecha = date_create($fecha);
        $diferencia = date_diff(date_create($fechah),$fecha);
        $dias = ($diferencia->days)+1;
        for ($i=0; $i < $dias; $i++) { 
            if($finSemana=='false'){
                if($fecha->format('w') === "0" || $fecha->format('w') === "6"){
                    $fecha->modify('1 day');
                    continue;
                }
            }
            $fechaG = $fecha->format('Y-m-d');
            $oreserva->setFecha($fechaG);
            $oreserva->setHora($hora);
            $oreserva->setIdCliente($idCliente);
            $oreserva->setOrigen($origen);
            $oreserva->setDestino($destino);
            $oreserva->setObserva($observa);
            $retorno = $oreserva->agregarReserva();
            if(!$retorno['exito']){break;}
            $fecha->modify('1 day');
        }
        break;
    case 3:
        $oreserva = new Reserva;
        $oreserva->setIdReserva($idReserva);
        $retorno = $oreserva->cancelarReserva();
        break;
    case 4:
        $oreserva = new Reserva;
        $oreserva->setIdReserva($idReserva);
        $retorno = $oreserva->buscarReserva();
        break;
    case 5:
        $oreserva = new Reserva;
        $oreserva->setIdReserva($idReserva);
        $oreserva->setIdCliente($idCliente);
        $oreserva->setFecha($fecha);
        $oreserva->setHora($hora);
        $oreserva->setOrigen($origen);
        $oreserva->setDestino($destino);
        $oreserva->setObserva($observa);
        $retorno = $oreserva->actualizarReserva();
        break;
    case 6:
        $oreserva = new Reserva;
        $oreserva->setIdReserva($idReserva);
        $retorno = $oreserva->asignarReserva();
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
