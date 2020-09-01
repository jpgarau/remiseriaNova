<?php
$dir = is_dir('modelo')?"":"../";
include_once($dir."modelo/validar.php");
include_once($dir."modelo/conexion.php");

class Usuario{
    private $usuarioId;
    private $nombre;
    private $apellido;
    private $usuario;
    private $clave;
    private $perfilId;
    private $estado;

    public function __construct(){
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->usuarioId = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->usuario = "";
        $this->clave = "";
        $this->perfilId = 0;
        $this->estado = 0;
    }

    //getters

    public function getUsuarioId(){
        return $this->usuarioId;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getApellido(){
        return $this->apellido;
    }
    public function getUsuario(){
        return $this->usuario;
    }
    public function getClave(){
        return $this->clave;
    }
    public function getPerfilId(){
        return $this->perfilId;
    }
    public function getEstado(){
        return $this->estado;
    }

    //setters

    public function setUsuarioId($usuarioId){
        $this->usuarioId = $usuarioId;
    }
    public function setNombre($nombre){
        $this->nombre = '';
        $error = false;
        $nombre = trim($nombre);
        $nombre = filter_var($nombre,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombre===FALSE || is_null($nombre) || strlen($nombre)===0) $error = true;
        if(!$error){
            $this->nombre = $nombre;
        }
        return $error;
    }
    public function setApellido($apellido){
        $this->apellido = '';
        $error = false;
        $apellido = trim($apellido);
        $apellido = filter_var($apellido,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($apellido===FALSE || is_null($apellido) || strlen($apellido)===0) $error = true;
        if(!$error){
            $this->apellido = $apellido;
        }
        return $error;
    }
    public function setUsuario($usuario){
        $this->usuario = '';
        $error = false;
        $usuario = trim($usuario);
        $usuario = filter_var($usuario,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($usuario===FALSE || is_null($usuario) || strlen($usuario)===0) $error = true;
        if(!$error){
            $this->usuario = $usuario;
        }
        return $error;
    }
    public function setClave($clave){
        $this->clave = '';
        $error = false;
        $clave = trim($clave);
        $clave = filter_var($clave,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($clave===FALSE || is_null($clave) || strlen($clave)===0) $error = true;
        if(!$error){
            $this->clave = password_hash($clave,PASSWORD_DEFAULT);
        }
        return $error;
    }
    public function setPerfilId($perfilId){
        $this->perfilId = 0;
        $error = false;
        $perfilId = filter_var($perfilId, FILTER_VALIDATE_INT);
        if($perfilId===FALSE || is_null($perfilId)) $error = true;
        if(!$error){
            $this->perfilId = $perfilId;
        }
        return $error;
    }
    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function agregarUsuario(){
        try {
            $error = true;
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            $usuario = $this->getUsuario();
            $clave = $this->getClave();
            $perfilId = $this->getPerfilId();
            $estado = 0;
            $sql = "INSERT INTO usuarios (nombre, apellido, usuario, clave, perfilId, estado) VALUES (?,?,?,?,?,?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ssssii',$nombre,$apellido,$usuario,$clave,$perfilId,$estado);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }
    public function modificarUsuario(){
        try {
            $error = true;
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            $usuario = $this->getUsuario();
            $clave = $this->getClave();
            $perfilId = $this->getPerfilId();
            $usuarioId = $_SESSION['$susuarioId'];
            $sql = "UPDATE INTO usuarios (nombre, apellido, usuario, clave, perfilId) VALUES (?,?,?,?,?) WHERE usuarioId=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ssssii', $nombre, $apellido, $usuario, $clave, $perfilId, $usuarioId);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }
    public function eliminarUsuario(){
        try {
            $error = true;
            $usuarioId = $_SESSION['$susuarioId'];
            $estado = 99;
            $sql = "UPDATE INTO usuarios (estado) VALUES (?) WHERE usuarioid=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii',$estado,$usuarioId);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }
    public function buscarUsuario(){
        try {
            $usuarioId = $_SESSION['$susuarioId'];
            $sql = "SELECT nombre, apellido, usuario, clave, perfilId, estado FROM usuarios WHERE usuarioId";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$usuarioId);
                $stmt->execute();
                $rs = $stmt->get_result();
                while($fila=$rs->fetch_array()){
                    $this->setUsuarioId($usuarioId);
                    $this->setNombre($fila[0]);
                    $this->setApellido($fila[1]);
                    $this->setUsuario($fila[2]);
                    $this->setClave($fila[3]);
                    $this->setPerfilId($fila[4]);
                    $this->setEstado($fila[5]);
                }
                $stmt->close();
                return $this;
            }else{
                return true;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function listarUsuario(){
        try {
            $arrusuarios = array();
            $sql = "SELECT * FROM usuarios";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrusuarios = $stmt->get_result();
                $stmt->close();
                return $arrusuarios;
            }else{
                return true;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
    public function verificarUsuario($user,$pass){
        try {
            $error = false;
            $sql = "SELECT nombre, apellido, usuario, clave FROM usuarios WHERE usuario=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('s',$user);
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                while($fila=$rs->fetch_array()){
                    $error = password_verify($pass,$fila[3]);
                    if ($error){
                        $error = strtoupper($fila[1]).', '.$fila[0];
                        break;
                    }
                }
            }
        } catch (Exception $e) {
            $error = $e; //->getMessage();
        }
        return $error;
    }
}    