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

include_once($dir . 'modelo/cliente.php');

$tarea = $_POST['param'];

if (isset($_POST['ayn'])) {
    $ayn = $_POST['ayn'];
}
if (isset($_POST['domicilio'])) {
    $domicilio = $_POST['domicilio'];
}
if (isset($_POST['idlocalidad'])) {
    $idlocalidad = $_POST['idlocalidad'];
}
if (isset($_POST['telefono'])) {
    $telefono = $_POST['telefono'];
}
if (isset($_POST['tipodoc'])) {
    $tipodoc = $_POST['tipodoc'];
}
if (isset($_POST['nrodoc'])) {
    $nrodoc = $_POST['nrodoc'];
}
if (isset($_POST['email'])) {
    $email = $_POST['email'];
}
if (isset($_POST['nacido'])) {
    $nacido = $_POST['nacido'];
}
if (isset($_POST['iva'])) {
    $iva = $_POST['iva'];
}
if (isset($_POST['cuit'])) {
    $cuit = $_POST['cuit'];
}
if (isset($_POST['idClientes'])) {
    $idClientes = $_POST['idClientes'];
}
if (isset($_POST['idClientesActual'])) {
    $idClientesActual = $_POST['idClientesActual'];
}
if (isset($_POST['observa'])){
    $observa = $_POST['observa'];
}

switch ($tarea) {
    case 1:
        $ocliente = new Cliente();
        $retorno = $ocliente->listarCliente();
        break;
    case 2:
        $ocliente = new Cliente();
        $ocliente->setAyN($ayn);
        $ocliente->setDomicilio($domicilio);
        $ocliente->setIdLocalidad($idlocalidad);
        $ocliente->setTelefono($telefono);
        $ocliente->setTipoDoc($tipodoc);
        $ocliente->setNroDoc($nrodoc);
        $ocliente->setEmail($email);
        $ocliente->setNacido($nacido);
        $ocliente->setIva($iva);
        $ocliente->setCuit($cuit);
        $ocliente->setObserva(($observa));
        $retorno = $ocliente->agregarCliente();
        break;
    case 3:
        $ocliente = new Cliente();
        $ocliente->setAyN($ayn);
        $ocliente->setDomicilio($domicilio);
        $ocliente->setIdLocalidad($idlocalidad);
        $ocliente->setTelefono($telefono);
        $ocliente->setTipoDoc($tipodoc);
        $ocliente->setNroDoc($nrodoc);
        $ocliente->setEmail($email);
        $ocliente->setNacido($nacido);
        $ocliente->setIva($iva);
        $ocliente->setCuit($cuit);
        $ocliente->setIdClientes($idClientes);
        $ocliente->setIdPersonas($idClientes);
        $ocliente->setObserva($observa);
        $retorno = $ocliente->modificarCliente();
        break;
    case 4:
        $ocliente = new Cliente();
        $ocliente->setIdClientes($idClientes);
        $retorno = $ocliente->eliminarCliente();
        break;
    case 5:
        $ocliente = new Cliente();
        $retorno = $ocliente->buscarCliente($idClientes);
        break;
    case 6:
        $ocliente = new Cliente();
        $retorno = $ocliente->listarCuit();
        break;
    case 7:
        $ocliente = new Cliente();
        $retorno = $ocliente->listarDniClientes();
        break;
    case 8:
        $ocliente = new Cliente();
        $retorno = $ocliente->listarDniPersonas();
        break;
    case 9:
        $ocliente = new Cliente();
        $ocliente->setIdClientes($idClientes);
        $retorno = $ocliente->vincularCliente();
        break;
    case 10:
        $ocliente = new Cliente();
        $ocliente->setIdClientes($idClientes);
        $retorno = $ocliente->vincularClienteExistente($idClientesActual);
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
