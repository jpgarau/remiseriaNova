<?php
    $dir = is_dir('modelo')?"":"../";
include_once($dir.'modelo/validar.php');
include_once($dir.'modelo/usuario.php');

class UsuarioC{
    public function __construct(){
        
    }
    public function buscar(){
        $ret = false;
		$ousuario = new Usuario();
		$ret = $ousuario->buscarUsuario();
		if(is_object($ret)){
			$arr = array();
            $arr[] = $ret->getNombre();
            $arr[] = $ret->getApellido();
            $arr[] = $ret->getUsuario();
            $arr[] = $ret->getClave();
            $arr[] = $ret->getPerfilId();
            $arr[] = $ret->getEstado();
			return $arr;
		}else{
			return false;
		}
    }
    public function verificar($usuario,$password){
        $ret = false;
        $ousuario = new Usuario();
        $ret = $ousuario->verificarUsuario($usuario,$password);
        return $ret;
    }
    public function cambiarClave($usuarioid, $clave){
        $ousuario = new Usuario();
        $ousuario->setUsuarioId($usuarioid);
        $ousuario->setClave($clave);
        $retorno = $ousuario->cambiarClave();
        return $retorno;
    }
}