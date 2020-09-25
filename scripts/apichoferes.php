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

include_once($dir . 'modelo/chofer.php');

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
if (isset($_POST['comision'])) {
    $comision = $_POST['comision'];
}
if (isset($_POST['nrolicencia'])) {
    $nrolicencia = $_POST['nrolicencia'];
}
if (isset($_POST['observa'])) {
    $observa = $_POST['observa'];
}
if (isset($_POST['telid'])) {
    $telid = $_POST['telid'];
}
if (isset($_POST['idchofer'])) {
    $idchofer = $_POST['idchofer'];
}
if (isset($_POST['idChoferActual'])) {
    $idChoferActual = $_POST['idChoferActual'];
}

switch ($tarea) {
    case 1:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarChofer();
        break;
    case 2:
        $ochofer = new Chofer();
        $ochofer->setAyN($ayn);
        $ochofer->setDomicilio($domicilio);
        $ochofer->setIdLocalidad($idlocalidad);
        $ochofer->setTelefono($telefono);
        $ochofer->setTipoDoc($tipodoc);
        $ochofer->setNroDoc($nrodoc);
        $ochofer->setEmail($email);
        $ochofer->setNacido($nacido);
        $ochofer->setIva($iva);
        $ochofer->setCuit($cuit);
        $ochofer->setComision($comision);
        $ochofer->setNroLicencia($nrolicencia);
        $ochofer->setObserva($observa);
        $ochofer->setTelId($telid);
        $retorno = $ochofer->agregarChofer();
        break;
    case 3:
        $ochofer = new Chofer();
        $ochofer->setAyN($ayn);
        $ochofer->setDomicilio($domicilio);
        $ochofer->setIdLocalidad($idlocalidad);
        $ochofer->setTelefono($telefono);
        $ochofer->setTipoDoc($tipodoc);
        $ochofer->setNroDoc($nrodoc);
        $ochofer->setEmail($email);
        $ochofer->setNacido($nacido);
        $ochofer->setIva($iva);
        $ochofer->setCuit($cuit);
        $ochofer->setComision($comision);
        $ochofer->setNroLicencia($nrolicencia);
        $ochofer->setObserva($observa);
        $ochofer->setTelId($telid);
        $ochofer->setIdChofer($idchofer);
        $ochofer->setIdPersonas($idchofer);
        $retorno = $ochofer->modificarChofer();
        break;
    case 4:
        $ochofer = new Chofer();
        $ochofer->setIdChofer($idchofer);
        $retorno = $ochofer->eliminarChofer();
        break;
    case 5:
        $ochofer = new Chofer();
        $retorno = $ochofer->buscarChofer($idchofer);
        $retorno = array('exito'=>false);
        break;
    case 6:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarCuit();
        break;
    case 7:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarDniChoferes();
        break;
    case 8:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarDniPersonas();
        break;
    case 9:
        $ochofer = new Chofer();
        $ochofer->setIdChofer($idchofer);
        $retorno = $ochofer->vincularChofer();
        break;
    case 10:
        $ochofer = new Chofer();
        $ochofer->setIdChofer($idchofer);
        $retorno = $ochofer->vincularChoferExistente($idChoferActual);
        break;
    default:
        break;
}

if ($retorno['exito'] == true) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($retorno);
} else {
    header('HTTP/1.1 500');
    die(json_encode($retorno));
}

function peticion_ajax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}
