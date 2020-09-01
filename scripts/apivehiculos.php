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

include_once($dir . 'modelo/vehiculos.php');

$tarea = $_POST['param'];
if(isset($_POST['marca'])){
    $marca = $_POST['marca'];
}
if(isset($_POST['anio'])){
    $anio = $_POST['anio'];
}
if(isset($_POST['patente'])){
    $patente = $_POST['patente'];
}
if(isset($_POST['falta'])){
    $falta = $_POST['falta'];
}
if(isset($_POST['habilitado'])){
    $habilitado = $_POST['habilitado'];
}
if(isset($_POST['vtoseguro'])){
    $vtoseguro = $_POST['vtoseguro'];
}
if(isset($_POST['titular'])){
    $titular = $_POST['titular'];
}
if(isset($_POST['observa'])){
    $observa = $_POST['observa'];
}
if(isset($_POST['idVehiculos'])){
    $idVehiculos = $_POST['idVehiculos'];
}

switch ($tarea) {
    case 1:
        $ovehiculo = new Vehiculo;
        $retorno = $ovehiculo->listarVehiculos();
        break;
    case 2:
        $ovehiculo = new Vehiculo;
        $ovehiculo->setMarca($marca);
        $ovehiculo->setAnio($anio);
        $ovehiculo->setPatente($patente);
        $ovehiculo->setFAlta($falta);
        $ovehiculo->setHablita($habilitado);
        $ovehiculo->setVtoSeguro($vtoseguro);
        $ovehiculo->setTitular($titular);
        $ovehiculo->setObserva($observa);
        $retorno = $ovehiculo->agregarVehiculo();
        break;
    case 3:
        $ovehiculo = new Vehiculo;
        $ovehiculo->setMarca($marca);
        $ovehiculo->setAnio($anio);
        $ovehiculo->setPatente($patente);
        $ovehiculo->setFAlta($falta);
        $ovehiculo->setHablita($habilitado);
        $ovehiculo->setVtoSeguro($vtoseguro);
        $ovehiculo->setTitular($titular);
        $ovehiculo->setObserva($observa);
        $ovehiculo->setIdVehiculo($idVehiculos);
        $retorno = $ovehiculo->modificarVehiculo();
        break;
    case 4:
        $ovehiculo = new Vehiculo;
        $ovehiculo->setIdVehiculo($idVehiculos);
        $retorno = $ovehiculo->eliminarVehiculo();
        break;
    case 5:
        $ovehiculo=new Vehiculo;
        $ovehiculo->setIdVehiculo($idVehiculos);
        $retorno = $ovehiculo->buscarVehiculo();
        break;
    case 6:
        $ovehiculo = new Vehiculo;
        $retorno = $ovehiculo->listarVehiculosHabilitados();
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
