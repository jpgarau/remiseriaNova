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

include_once($dir . 'modelo/propietario.php');

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
if (isset($_POST['idPropietario'])) {
    $idPropietario = $_POST['idPropietario'];
}
if (isset($_POST['idPropietarioActual'])) {
    $idPropietarioActual = $_POST['idPropietarioActual'];
}
if (isset($_POST['observa'])){
    $observa = $_POST['observa'];
}
if (isset($_POST['telid'])){
    $telid = $_POST['telid'];
}

switch ($tarea) {
    case 1:
        $opropietario = new Propietario;
        $retorno = $opropietario->listarPropietarios();
        break;
    case 2:
        $opropietario = new Propietario;
        $opropietario->setAyN($ayn);
        $opropietario->setDomicilio($domicilio);
        $opropietario->setIdLocalidad($idlocalidad);
        $opropietario->setTelefono($telefono);
        $opropietario->setTipoDoc($tipodoc);
        $opropietario->setNroDoc($nrodoc);
        $opropietario->setEmail($email);
        $opropietario->setNacido($nacido);
        $opropietario->setIva($iva);
        $opropietario->setCuit($cuit);
        $opropietario->setObserva($observa);
        $opropietario->setTelId(($telid));
        $retorno = $opropietario->agregarPropietario();
        break;
    case 3:
        $opropietario = new Propietario;
        $opropietario->setAyN($ayn);
        $opropietario->setDomicilio($domicilio);
        $opropietario->setIdLocalidad($idlocalidad);
        $opropietario->setTelefono($telefono);
        $opropietario->setTipoDoc($tipodoc);
        $opropietario->setNroDoc($nrodoc);
        $opropietario->setEmail($email);
        $opropietario->setNacido($nacido);
        $opropietario->setIva($iva);
        $opropietario->setCuit($cuit);
        $opropietario->setIdPropietario($idPropietario);
        $opropietario->setIdPersonas($idPropietario);
        $opropietario->setObserva($observa);
        $opropietario->setTelId(($telid));
        $retorno = $opropietario->modificarPropietario();
        break;
    case 4:
        $opropietario = new Propietario;
        $opropietario->setIdPropietario($idPropietario);
        $retorno = $opropietario->eliminarPropietario();
        break;
    case 5:
        $opropietario = new Propietario;
        $retorno = $opropietario->buscarPropietario($idPropietario);
        break;
    case 6:
        $opropietario = new Propietario;
        $retorno = $opropietario->listarCuit();
        break;
    case 7:
        $opropietario = new Propietario;
        $retorno = $opropietario->listarDniPropietarios();
        break;
    case 8:
        $opropietario = new Propietario;
        $retorno = $opropietario->listarDniPersonas();
        break;
    case 9:
        $opropietario = new Propietario;
        $opropietario->setIdPropietario($idPropietario);
        $retorno = $opropietario->vincularPropietario();
        break;
    case 10:
        $opropietario = new Propietario;
        $opropietario->setIdPropietario($idPropietario);
        $retorno = $opropietario->vincularPropietarioExistente($idPropietarioActual);
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
