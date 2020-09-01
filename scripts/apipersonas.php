<?php
if (!peticion_ajax()) {
    header('HTTP/1.1 401');
    die(json_encode(array('exito' => false, 'msg' => 'No Autorizado')));
}
if (!isset($_POST['param'])) {
    header('HTTP/1.1 401');
    die(json_encode(array('exito' => false, 'msg' => 'No Autorizado')));
}

$dir = is_dir('modelo') ? '' : '../';
include_once($dir . 'modelo/persona.php');

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
if (isset($_POST['observa'])) {
    $observa = $_POST['observa'];
}
if (isset($_POST['idPersonas'])) {
    $idPersonas = $_POST['idPersonas'];
}

switch ($tarea) {
    case 1:
        $opersona = new Persona();
        $retorno = $ochofer->listarPersonas();
        break;
    case 2:
        $opersona = new Persona();
        $opersona->setAyN($ayn);
        $opersona->setDomicilio($domicilio);
        $opersona->setIdLocalidad($idlocalidad);
        $opersona->setTelefono($telefono);
        $opersona->setTipoDoc($tipodoc);
        $opersona->setNroDoc($nrodoc);
        $opersona->setEmail($email);
        $opersona->setNacido($nacido);
        $opersona->setIva($iva);
        $opersona->setCuit($cuit);
        $opersona->setObserva($observa);
        $retorno = $opersona->agregarPersona();
        break;
    case 3:
        $opersona = new Persona();
        $opersona->setAyN($ayn);
        $opersona->setDomicilio($domicilio);
        $opersona->setIdLocalidad($idlocalidad);
        $opersona->setTelefono($telefono);
        $opersona->setTipoDoc($tipodoc);
        $opersona->setNroDoc($nrodoc);
        $opersona->setEmail($email);
        $opersona->setNacido($nacido);
        $opersona->setIva($iva);
        $opersona->setCuit($cuit);
        $opersona->setObserva($observa);
        $opersona->setIdPersonas($idPersonas);
        $retorno = $opersona->modificarPersona();
        break;
    case 4:
        $opersona = new Persona();
        $opersona->setIdPersonas($idPersonas);
        $retonor = $opersona->eliminarPersona();
    case 5:
        $opersona = new Persona();
        $retorno = $opersona->buscarPersona($idPersonas);
        break;
    case 6:
        $opersona = new Persona();
        $opersona->setNroDoc($nrodoc);
        $retorno = $opersona->buscarPersonaDni();
        break;
    case 7:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarDniChoferes();
        break;
    case 8:
        $ochofer = new Chofer();
        $retorno = $ochofer->listarDniPersonas();
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
