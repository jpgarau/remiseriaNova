<?php
$dir = is_dir('modelo') ? "" : "../";
include_once $dir . "modelo/validar.php";
include_once $dir . "modelo/conexion.php";

class Usuario
{
    private $usuarioId;
    private $nombre;
    private $apellido;
    private $usuario;
    private $clave;
    private $perfilId;
    private $idChofer;
    private $estado;
    private $idPropietario;

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->usuarioId = 0;
        $this->nombre = "";
        $this->apellido = "";
        $this->usuario = "";
        $this->clave = "";
        $this->perfilId = 0;
        $this->idChofer = null;
        $this->estado = 0;
        $this->idPropietario = 0;
    }

    //getters

    public function getUsuarioId()
    {
        return $this->usuarioId;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getApellido()
    {
        return $this->apellido;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function getClave()
    {
        return $this->clave;
    }
    public function getPerfilId()
    {
        return $this->perfilId;
    }
    public function getIdChofer()
    {
        return $this->idChofer;
    }
    public function getIdPropietario()
    {
        return $this->idPropietario;
    }
    public function getEstado()
    {
        return $this->estado;
    }

    //setters

    public function setUsuarioId($usuarioId)
    {
        $this->usuarioId = $usuarioId;
    }
    public function setNombre($nombre)
    {
        $this->nombre = '';
        $error = false;
        $nombre = trim($nombre);
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($nombre === FALSE || is_null($nombre) || strlen($nombre) === 0) $error = true;
        if (!$error) {
            $this->nombre = $nombre;
        }
        return $error;
    }
    public function setApellido($apellido)
    {
        $this->apellido = '';
        $error = false;
        $apellido = trim($apellido);
        $apellido = filter_var($apellido, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($apellido === FALSE || is_null($apellido) || strlen($apellido) === 0) $error = true;
        if (!$error) {
            $this->apellido = $apellido;
        }
        return $error;
    }
    public function setUsuario($usuario)
    {
        $this->usuario = '';
        $error = false;
        $usuario = trim($usuario);
        $usuario = filter_var($usuario, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($usuario === FALSE || is_null($usuario) || strlen($usuario) === 0) $error = true;
        if (!$error) {
            $this->usuario = $usuario;
        }
        return $error;
    }
    public function setClave($clave)
    {
        $this->clave = '';
        $error = false;
        $clave = trim($clave);
        $clave = filter_var($clave, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($clave === FALSE || is_null($clave) || strlen($clave) === 0) $error = true;
        if (!$error) {
            $this->clave = password_hash($clave, PASSWORD_DEFAULT);
        }
        return $error;
    }
    public function setPerfilId($perfilId)
    {
        $this->perfilId = 0;
        $error = false;
        $perfilId = filter_var($perfilId, FILTER_VALIDATE_INT);
        if ($perfilId === FALSE || is_null($perfilId)) $error = true;
        if (!$error) {
            $this->perfilId = $perfilId;
        }
        return $error;
    }
    public function setIdChofer($idChofer)
    {
        $this->idChofer = null;
        $error = false;
        $idChofer = filter_var($idChofer, FILTER_VALIDATE_INT);
        if ($idChofer === FALSE || $idChofer === 0) $error = true;
        if (!$error) {
            $this->idChofer = $idChofer;
        }
    }
    public function setIdPropietario($idPropietario)
    {
        $this->idPropietario = null;
        $error = false;
        $idPropietario = filter_var($idPropietario, FILTER_VALIDATE_INT);
        if ($idPropietario === FALSE || $idPropietario === 0) $error = true;
        if (!$error) {
            $this->idPropietario = $idPropietario;
        }
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    
    public function agregarUsuario()
    {
        $arr = array('exito' => false, 'msg' => 'Error al agregar');
        try {
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            $usuario = $this->getUsuario();
            $pass = explode(" ",strtolower(trim($apellido)));
            $this->setClave(substr(strtolower(trim($nombre)),0,1).$pass[0]);
            $clave = $this->getClave();
            $perfilId = $this->getPerfilId();
            $idChofer = $this->getIdChofer();
            $idPropietario = $this->getIdPropietario();
            $estado = 50;
            $sql = "INSERT INTO usuarios (nombre, apellido, usuario, clave, perfilId, idChofer, idPropietario, estado) VALUES (?,?,?,?,?,?,?,?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('ssssiiii', $nombre, $apellido, $usuario, $clave, $perfilId, $idChofer, $idPropietario, $estado);
                $stmt->execute();
                $id = $mysqli->insert_id;
                $stmt->close();
                $mysqli->close();
                $arr = array('exito' => true, 'msg' => '', 'id'=>$id);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function modificarUsuario()
    {
        $arr = array('exito' => false, 'msg' => 'Error al modificar');
        try {
            $nombre = $this->getNombre();
            $apellido = $this->getApellido();
            $usuario = $this->getUsuario();
            $perfilId = $this->getPerfilId();
            $idChofer = $this->getIdChofer();
            $idPropietario = $this->getIdPropietario();
            $usuarioId = $this->getUsuarioId();
            $sql = "UPDATE usuarios SET nombre=?, apellido=?, usuario=?, perfilId=?, idChofer=?, idPropietario=? WHERE usuarioId=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('sssiiii', $nombre, $apellido, $usuario, $perfilId, $idChofer, $idPropietario,  $usuarioId);
                $stmt->execute();
                $stmt->close();
                $arr = array('exito' => true, 'msg' => '');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function eliminarUsuario()
    {
        $arr = array('exito' => false, 'msg' => 'Error al eliminar');
        try {
            $usuarioId = $this->getUsuarioId();
            $estado = 99;
            $sql = "UPDATE usuarios SET estado=? WHERE usuarioid=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('ii', $estado, $usuarioId);
                $stmt->execute();
                $stmt->close();
                $arr = array('exito' => true, 'msg' => '');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscarUsuario()
    {
        $arr = array('exito' => false, 'msg' => 'Error al buscar');
        try {
            $usuarioId = $this->getUsuarioId();
            $sql = "SELECT nombre, apellido, usuario, clave, perfilId, idChofer, idPropietario, estado FROM usuarios WHERE usuarioId";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('i', $usuarioId);
                $stmt->execute();
                $rs = $stmt->get_result();
                $usuario = $rs->fetch_assoc();
                $stmt->close();
                $mysqli->close();
                $arr = array('exito' => true, 'msg' => '', $usuario);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarUsuario()
    {
        $arr = array('exito' => false, 'msg' => 'Error al listar');
        try {
            $perfilid = $_SESSION['userProfile']['perfilid'];
            $sql = "SELECT usuarios.usuarioid, usuarios.apellido, usuarios.nombre, usuarios.usuario, usuarios.perfilid, usuarios.idChofer, usuarios.idPropietario, perfil.descripcion FROM usuarios, perfil WHERE  usuarios.perfilid=perfil.perfilid AND usuarios.perfilid >= ? AND usuarios.estado BETWEEN 0 AND 90";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('i',$perfilid);
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                $usuarios = $rs->fetch_all(MYSQLI_ASSOC);
                $mysqli->close();
                $arr = array('exito' => true, 'msg' => '', $usuarios);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function verificarUsuario($user, $pass)
    {
        $arr = array('exito' => false, 'msg' => 'Error al verificar');
        try {
            $error = false;
            $user = trim($user);
            $user = filter_var($user, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($user === FALSE || is_null($user) || strlen($user) === 0) $error = true;
            $pass = trim($pass);
            $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if ($pass === FALSE || is_null($pass) || strlen($pass) === 0 || $error === true) $error = true;
            if (!$error) {
                $sql = "SELECT * FROM usuarios WHERE usuario=? AND estado BETWEEN 0 AND 90";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset('utf8');
                $stmt = $mysqli->prepare($sql);
                if ($stmt !== FALSE) {
                    $stmt->bind_param('s', $user);
                    $stmt->execute();
                    $rs = $stmt->get_result();
                    $stmt->close();
                    $arr = array('exito' => true, 'msg' => '', 'encontrado' => false);
                    while ($fila = $rs->fetch_array(MYSQLI_ASSOC)) {
                        $error = password_verify($pass, $fila['clave']);
                        if ($error) {
                            unset($fila['clave']);
                            $arr = array('exito' => true, 'msg' => '', 'encontrado' => true, $fila);
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function solicitarCambioClave(){
        $arr = array('exito'=>false, 'msg'=>'Error al solicitar el cambio de clave');
        try {
            $usuarioid = $this->getUsuarioId();
            $sql = "UPDATE usuarios SET estado=50 WHERE usuarioid=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $usuarioid);
                $stmt->execute();
                $stmt->close();
                $mysqli->close();
                $arr = array('exito'=>true, 'msg'=>'');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function cambiarClave(){
        $arr = array('exito'=>false, 'msg'=>'Error al realizar el cambio de clave');
        try {
            $usuarioid = $this->getUsuarioId();
            $clave = $this->getClave();
            $estado = 0;
            $sql = 'UPDATE usuarios SET clave=?, estado=? WHERE usuarioid=?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('sii', $clave, $estado, $usuarioid);
                $stmt->execute();
                $stmt->close();
                $mysqli->close();
                $arr = array('exito'=>true, 'msg'=>'');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}