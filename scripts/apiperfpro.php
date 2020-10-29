<?php
if(!peticion_ajax()){
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false, 'msg'=>'No Autorizado')));
}
if(!isset($_POST['param'])){
    header('HTTP/1.1 401');
    die(json_encode(array('exito'=>false, 'msg'=>'No Autorizado')));
}

$dir = is_dir('modelo')?'':'../';
include_once($dir.'modelo/perfilprograma.php');

$tarea = $_POST['param'];
if(isset($_POST['perfilid'])){$perfilid = $_POST['perfilid'];}
if(isset($_POST['programas'])){$programas=$_POST['programas'];}else{$programas = array();}

switch ($tarea){
    case 1:
        $operfilpro = new PerfilPrograma();
        $operfilpro->setPerfilId($perfilid);
        $retorno = $operfilpro->listar();
        break;
    case 2:
        $operfilpro = new PerfilPrograma();
        $retorno = $operfilpro->actualizar($programas,$perfilid);
        break;
    case 3:
        break;
    case 4:
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