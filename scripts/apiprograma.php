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
include_once($dir.'modelo/programa.php');

$tarea = $_POST['param'];
if(isset($_POST['nombre'])){$nombre = $_POST['nombre'];}
if(isset($_POST['link'])){$link = $_POST['link'];}
if(isset($_POST['padre'])){$padre = $_POST['padre'];}
if(isset($_POST['esopcion'])){$esopcion = $_POST['esopcion'];}
if(isset($_POST['orden'])){$orden = $_POST['orden'];}
if(isset($_POST['programaid'])){$programaid = $_POST['programaid'];}

switch ($tarea){
    case 1:
        $oprograma = new Programa();
        $retorno = $oprograma->listar();
        break;
    case 2:
        $oprograma = new Programa();
        $oprograma->setNombre($nombre);
        $oprograma->setLink($link);
        $oprograma->setPadre($padre);
        $oprograma->setEsOpcion($esopcion);
        $oprograma->setOrden($orden);
        $retorno = $oprograma->agregar();
        break;
    case 3:
        $oprograma = new Programa();
        $oprograma->setNombre($nombre);
        $oprograma->setLink($link);
        $oprograma->setPadre($padre);
        $oprograma->setEsOpcion($esopcion);
        $oprograma->setOrden($orden);
        $oprograma->setProgramaId($programaid);
        $retorno = $oprograma->modificar();
        break;
    case 4:
        $oprograma = new Programa();
        $oprograma->setProgramaId($programaid);
        $retorno = $oprograma->eliminar();
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