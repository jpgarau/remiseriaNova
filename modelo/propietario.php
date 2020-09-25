<?php

include_once('validar.php');
include_once('conexion.php');
include_once('persona.php');

class Propietario extends Persona
{
    private $idPropietario;
    private $comision;

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idPropietario = 0;
        $this->comision = 0;
    }

    //getters
    public function getIdPropietario()
    {
        return $this->idPropietario;
    }
    public function getComision()
    {
        return $this->comision;
    }

    //setters
    public function setIdPropietario($idPropietario)
    {
        $this->idPropietario = $idPropietario;
    }
    public function setComision($comision)
    {
        $this->comision = $comision;
    }

    public function agregarPropietario()
    {
        $arr = array('exito' => false, 'msg' => 'Error en agregar');
        try {
            $persona = $this->agregarPersona();
            if ($persona['exito']) {
                $idPropietario = $persona['idPersona'];
                $comision = $this->getComision();
                $sql = "INSERT INTO propietarios (idPropietario, comision) VALUES (?,?)";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if ($stmt !== FALSE) {
                    $stmt->bind_param('ii', $idPropietario, $comision);
                    $stmt->execute();
                    $stmt->close();
                }
                $arr = $persona;
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function modificarPropietario()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un Error');
        try {
            $persona = $this->modificarPersona();
            if ($persona['exito']) {
                $arr = $persona;
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function eliminarPropietario()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error al eliminar');
        try {
            $idPropietario = $this->getIdPropietario();
            $sql = "DELETE FROM propietarios WHERE idPropietario=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('i', $idPropietario);
                $stmt->execute();
                $stmt->close();
            }
            $arr = array('exito' => true, 'msg' => '');
            $mysqli->close();
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscarPropietario($idPropietario)
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error en la busqueda');
        try {
            $sql = "SELECT * FROM personas, propietarios WHERE idPropietario = ? AND personas.idPersonas = propietarios.idPropietario";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('i', $idPropietario);
                $stmt->execute();
                $propietario = $stmt->get_result();
                $stmt->close();
                $datosPropietario = $propietario->fetch_assoc();
                $datosPropietario['ayn'] = htmlspecialchars_decode($datosPropietario['ayn'],ENT_QUOTES);
                $arr = array('exito' => true, 'msg' => '', $datosPropietario);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarPropietarios()
    {
        $arr = array('exito' => false, 'msg' => 'Error al listar');
        try {
            $arrpropietarios = array();
            $sql = "SELECT * FROM personas, propietarios WHERE personas.idPersonas = propietarios.idPropietario ORDER BY personas.ayn";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->execute();
                $arrpropietarios = $stmt->get_result();
                $stmt->close();
                $propietarios = $arrpropietarios->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito' => true, 'msg' => '', $propietarios);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarCuit()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error al listar');
        try {
            $arrPropietarios = array();
            $sql = "SELECT cuit FROM personas, propietarios WHERE personas.idPersonas = propietarios.idPropietario AND personas.cuit IS NOT NULL";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->execute();
                $arrPropietarios = $stmt->get_result();
                $stmt->close();
                $respuesta = $arrPropietarios->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito' => true, 'msg' => '', $respuesta);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarDniPropietarios(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrDni = array();
            $sql = "SELECT nrodoc FROM personas, propietarios WHERE personas.idPersonas = propietarios.idPropietario AND personas.nrodoc IS NOT NULL";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrDni = $stmt->get_result();
                $stmt->close();
                $respuesta = $arrDni->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'',$respuesta);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function listarDniPersonas(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrDni = array();
            $sql = "SELECT DISTINCT nrodoc FROM personas, propietarios WHERE personas.idPersonas NOT IN(SELECT idPropietario From propietarios) AND personas.nrodoc IS NOT NULL ";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrDni = $stmt->get_result();
                $stmt->close();
                $respuesta = $arrDni->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'',$respuesta);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function vincularPropietario(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $idPropietario = $this->getIdPropietario();
            $sql = "INSERT INTO propietarios (idPropietario) VALUES (?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idPropietario);
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
    public function vincularPropietarioExistente($idPropietarioActual){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error');
        try {
            $idPropietario = $this->getIdPropietario();
            $sql = "UPDATE propietarios SET idPropietario=? WHERE idPropietario=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $idPropietario, $idPropietarioActual);
                $stmt->execute();
                $stmt->close();
                $sql = "UPDATE vehiculos SET titular=? WHERE titular=?";
                $stmt2 = $mysqli->prepare($sql);
                if($stmt2!==FALSE){
                    $stmt2->bind_param('ii',$idPropietario, $idPropietarioActual);
                    $stmt2->execute();
                    $stmt2->close();
                    $mysqli->close();
                    $arr = array('exito'=>true, 'msg'=>'');
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}
