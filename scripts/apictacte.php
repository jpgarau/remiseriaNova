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

include_once($dir . 'modelo/ctacte.php');

$tarea = $_POST['param'];

if (isset($_POST['idCliente'])) {
    $idCliente = $_POST['idCliente'];
}
if (isset($_POST['fechaDesde'])) {
    $fechaDesde = $_POST['fechaDesde'];
}
if (isset($_POST['fechaHasta'])) {
    $fechaHasta = $_POST['fechaHasta'];
}
if (isset($_POST['fecha'])) {
    $fecha = $_POST['fecha'];
}
if (isset($_POST['hora'])) {
    $hora = $_POST['hora'];
}
if (isset($_POST['sigla'])) {
    $sigla = $_POST['sigla'];
}
if (isset($_POST['importe'])) {
    $importe = $_POST['importe'];
}
if (isset($_POST['observa'])) {
    $observa = $_POST['observa'];
}
if (isset($_POST['idCtaCte'])) {
    $idCtaCte = $_POST['idCtaCte'];
}

switch ($tarea){
    case 1:
        $octaCte = new Ctacte();
        $octaCte->setIdCliente($idCliente);
        $octaCte->setFecha($fechaDesde);
        $retorno = $octaCte->listarCtaCte($fechaHasta);
        break;
    case 2:
        $octaCte = new Ctacte();
        $octaCte->setIdCtaCte($idCtaCte);
        $retorno = $octaCte->eliminarMovimiento();
        break;
    case 3:
        $octaCte = new Ctacte();
        $octaCte->setSigla($sigla);
        $octaCte->setFecha($fecha);
        $octaCte->setHora($hora);
        $octaCte->setIdCliente($idCliente);
        $octaCte->setObserva($observa);
        $octaCte->setImporte($importe);
        $retorno = $octaCte->agregarMovimiento();
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
