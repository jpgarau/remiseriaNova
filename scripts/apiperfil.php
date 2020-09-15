<?php
if(!peticion_ajax()){
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false,'msg'=>'No Autorizado')));
}
if(!isset($_POST['param'])){ 
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false,'msg'=>'No Autorizado')));
}

$dir = is_dir('modelo')?'':'../';
include_once($dir.'modelo/perfil.php');

$tarea = $_POST['param'];
if(isset($_POST['descripcion'])){$descripcion = $_POST['descripcion'];}
if(isset($_POST['idperfil'])){$idperfil = $_POST['idperfil'];}

switch ($tarea) {
    case 1:
        $operfil = new Perfil();
        $retorno = $operfil->listar();
        break;
    case 2:
        $operfil = new Perfil();
        $operfil->setDescripcion($descripcion);
        $retorno = $operfil->agregar();
        break;
    case 3:
        $operfil = new Perfil();
        $operfil->setDescripcion($descripcion);
        $operfil->setPerfilId($idperfil);
        $retorno = $operfil->modificar();
        break;
    case 4:
        $operfil = new Perfil();
        $operfil->setPerfilId($idperfil);
        $retorno = $operfil->eliminar();
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