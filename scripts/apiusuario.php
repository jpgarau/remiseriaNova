<?php
$dir = is_dir('modelo') ? '' : '../';
include_once($dir . 'modelo/validar.php');
if(!isset($_SESSION['usuario'])) {
    header('HTTP/1.1 401');
    die('No Autorizado3');
}
if(!peticion_ajax()){
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false, 'msg'=>'No Autorizado1')));
}
if(!isset($_POST['param'])){
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false, 'msg'=>'No Autorizado2')));
}

include_once($dir.'modelo/usuario.php');

$tarea = $_POST['param'];
if(isset($_POST['apellido'])){$apellido = $_POST['apellido'];}
if(isset($_POST['nombre'])){$nombre = $_POST['nombre'];}
if(isset($_POST['usuario'])){$usuario = $_POST['usuario'];}
if(isset($_POST['perfilid'])){$perfilid = $_POST['perfilid'];}
if(isset($_POST['usuarioid'])){$usuarioid = $_POST['usuarioid'];}
if(isset($_POST['idChofer'])){$idChofer = $_POST['idChofer'];}
if(isset($_POST['idPropietario'])){$idPropietario = $_POST['idPropietario'];}

switch ($tarea) {
    case 1:
        $ousuario = new Usuario();
        $retorno = $ousuario->listarUsuario();
        break;
    case 2:
        $ousuario = new Usuario();
        $ousuario->setApellido($apellido);
        $ousuario->setNombre($nombre);
        $ousuario->setUsuario($usuario);
        $ousuario->setPerfilId($perfilid);
        $ousuario->setIdChofer($idChofer);
        $ousuario->setIdPropietario($idPropietario);
        $retorno = $ousuario->agregarUsuario();
        break;
    case 3:
        $ousuario = new Usuario();
        $ousuario->setApellido($apellido);
        $ousuario->setNombre($nombre);
        $ousuario->setUsuario($usuario);
        $ousuario->setPerfilId($perfilid);
        $ousuario->setIdChofer($idChofer);
        $ousuario->setIdPropietario($idPropietario);
        $ousuario->setUsuarioId($usuarioid);
        $retorno = $ousuario->modificarUsuario();
        break;
    case 4:
        $ousuario = new Usuario();
        $ousuario->setUsuarioId($usuarioid);
        $retorno = $ousuario->eliminarUsuario();
        break;
    case 5:
        $ousuario = new Usuario();
        $ousuario->setUsuarioId($_SESSION['userProfile']['usuarioid']);
        $retorno = $ousuario->solicitarCambioClave();
        if($retorno['exito']==true){
            unset($_SESSION['usuario']);
            unset($_SESSION['userProfile']);
            session_destroy();
        }
        break;
    default:
        break;
}

if($retorno['exito']==true){
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($retorno); 
}else {
    header('HTTP/1.1 500');
    die (json_encode($retorno));
}

function peticion_ajax(){
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
}