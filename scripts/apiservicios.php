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

include_once($dir . 'modelo/servicio.php');

$tarea = $_POST['param'];
if (isset($_POST['idVehiculo'])) {
    $idVehiculo = $_POST['idVehiculo'];
}
if (isset($_POST['idServicio'])) {
    $idServicio = $_POST['idServicio'];
}
if (isset($_POST['idChofer'])) {
    $idChofer = $_POST['idChofer'];
}
if (isset($_POST['fechaEnt'])) {
    $fechaEnt = $_POST['fechaEnt'];
}
if (isset($_POST['horaEnt'])) {
    $horaEnt = $_POST['horaEnt'];
}
if (isset($_POST['fechaSal'])) {
    $fechaSal = $_POST['fechaSal'];
}
if (isset($_POST['horaSal'])) {
    $horaSal = $_POST['horaSal'];
}


switch ($tarea) {
    case 1:
        $oservicio = new Servicio;
        $retorno = $oservicio->listarServiciosActivos();
        break;
    case 2:
        $oservicio = new Servicio;
        $oservicio->setIdChofer($idChofer);
        $oservicio->setIdVehiculo($idVehiculo);
        $oservicio->setFechaEnt($fechaEnt);
        $oservicio->setHoraEnt($horaEnt);
        $retorno = $oservicio->iniciarServicio();
        break;
    case 3:
        $oservicio = new Servicio;
        $oservicio->setFechaSal($fechaSal);
        $oservicio->setHoraSal($horaSal);
        $oservicio->setIdServicio($idServicio);
        $retorno = $oservicio->terminarServicio();
        break;
    case 4:
        $oservicio = new Servicio;
        $oservicio->setIdChofer($idChofer);
        $retorno = $oservicio->buscarServicioPorChofer();
        break;
    case 5:
        $oservicio = new Servicio;
        $oservicio->setIdServicio($idServicio);
        $retorno = $oservicio->buscarServicio();
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
