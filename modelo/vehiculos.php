<?php

include_once('validar.php');
include_once('conexion.php');

class Vehiculo
{
    private $idVehiculos;
    private $marca;
    private $anio;
    private $patente;
    private $falta;
    private $habilita;
    private $vtoseguro;
    private $titular;
    private $observa;

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idVehiculos = 0;
        $this->marca = "";
        $this->anio = 0;
        $this->patente = "";
        $this->falta = "";
        $this->habilita = 0;
        $this->vtoseguro = "";
        $this->titular = 0;
        $this->observa = "";
    }

    // getters

    public function getIdVehiculo()
    {
        return $this->idVehiculos;
    }
    public function getMarca()
    {
        return $this->marca;
    }
    public function getAnio()
    {
        return $this->anio;
    }
    public function getPatente()
    {
        return $this->patente;
    }
    public function getFAlta()
    {
        return $this->falta;
    }
    public function getHabilita()
    {
        return $this->habilita;
    }
    public function getVtoSeguro()
    {
        return $this->vtoseguro;
    }
    public function getTitular()
    {
        return $this->titular;
    }
    public function getObserva()
    {
        return $this->observa;
    }

    //setters

    public function setIdVehiculo($idVehiculos)
    {
        $this->idVehiculos = $idVehiculos;
    }
    public function setMarca($marca)
    {
        $this->marca = '';
        $error = false;
        $marca = trim($marca);
        $marca = filter_var($marca, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($marca === FALSE || is_null($marca) || strlen($marca) === 0) $error = true;
        if (!$error) {
            $this->marca = $marca;
        } else {
            $this->marca = $error;
        }
        return $error;
    }
    public function setAnio($anio)
    {
        $this->anio = null;
        $error = false;
        $anio = filter_var($anio, FILTER_VALIDATE_INT);
        if ($anio === FALSE) $error = true;
        if (!$error) {
            $this->anio = $anio;
        }
        return $error;
    }
    public function setPatente($patente)
    {
        $this->patente = null;
        $error = false;
        $patente = trim($patente);
        $patente = filter_var($patente, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($patente === FALSE || is_null($patente) || strlen($patente) === 0) $error = true;
        if (!$error) {
            $this->patente = $patente;
        }
        return $error;
    }
    public function setFAlta($falta)
    {
        $this->falta = null;
        $error = false;
        $falta = trim($falta);
        $falta = filter_var($falta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($falta === FALSE || is_null($falta) || strlen($falta) === 0) $error = true;
        if (!$error) {
            $this->falta = $falta;
        }
        return $error;
    }
    public function setHablita($habilita)
    {
        $this->habilita = 0;
        $error = false;
        $habilita = filter_var($habilita, FILTER_VALIDATE_INT);
        if ($habilita === FALSE) $error = true;
        if (!$error) {
            $this->habilita = $habilita;
        }
        return $error;
    }
    public function setVtoSeguro($vtoseguro)
    {
        $this->vtoseguro = null;
        $error = false;
        $vtoseguro = trim($vtoseguro);
        $vtoseguro = filter_var($vtoseguro, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($vtoseguro === FALSE || is_null($vtoseguro) || strlen($vtoseguro) === 0) $error = true;
        if (!$error) {
            $this->vtoseguro = $vtoseguro;
        }
        return $error;
    }
    public function setTitular($titular)
    {
        $this->titular = 0;
        $error = false;
        $titular = filter_var($titular, FILTER_VALIDATE_INT);
        if ($titular === FALSE) $error = true;
        if (!$error) {
            $this->titular = $titular;
        }
        return $error;
    }
    public function setObserva($observa)
    {
        $this->observa = null;
        $error = false;
        $observa = trim($observa);
        $observa = filter_var($observa, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($observa === FALSE || is_null($observa) || strlen($observa) === 0) $error = true;
        if (!$error) {
            $this->observa = $observa;
        }
        return $error;
    }

    public function agregarVehiculo()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error al agregar');
        try {
            $marca = $this->getMarca();
            $anio = $this->getAnio();
            $patente = $this->getPatente();
            $falta = $this->getFAlta();
            $habilita = $this->getHabilita();
            $vtoseguro = $this->getVtoSeguro();
            $titular = $this->getTitular();
            $observa = $this->getObserva();
            $sql = "INSERT INTO vehiculos (marca, anio, patente, falta, habilita, vtoseguro, titular, observa) VALUES (?,?,?,?,?,?,?,?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('sissisis', $marca, $anio, $patente, $falta, $habilita, $vtoseguro, $titular, $observa);
                $stmt->execute();
                $stmt->close();
                $idVehiculos = $mysqli->insert_id;
                $arr = array('exito' => true, 'msg' => '', 'idVehiculos' => $idVehiculos);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function modificarVehiculo()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error al agregar');
        try {
            $marca = $this->getMarca();
            $anio = $this->getAnio();
            $patente = $this->getPatente();
            $falta = $this->getFAlta();
            $habilita = $this->getHabilita();
            $vtoseguro = $this->getVtoSeguro();
            $titular = $this->getTitular();
            $observa = $this->getObserva();
            $idVehiculos = $this->getIdVehiculo();
            $sql = "UPDATE vehiculos SET marca=?, anio=?, patente=?, falta=?, habilita=?, vtoseguro=?, titular=?, observa=? WHERE idVehiculos=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('sissisisi', $marca, $anio, $patente, $falta, $habilita, $vtoseguro, $titular, $observa,$idVehiculos);
                $stmt->execute();
                $stmt->close();
                $arr = array('exito' => true, 'msg' => '');
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function eliminarVehiculo(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error');
        try {
            $idVehiculos = $this->getIdVehiculo();
            $sql = "DELETE FROM vehiculos WHERE idVehiculos=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idVehiculos);
                $stmt->execute();
                $stmt->close();
                $mysqli->close();
                $arr = array('exito'=>true,'msg'=>'');
            }
            
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarVehiculos()
    {
        $arr = array('exito' => false, 'msg' => 'Hubo un error al listar');
        try {
            $arrvehiculos = array();
            $sql = "SELECT vehiculos.idVehiculos, vehiculos.marca, vehiculos.patente, vehiculos.vtoseguro, personas.ayn FROM vehiculos, personas WHERE vehiculos.titular = personas.idPersonas";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->execute();
                $vehiculos = $stmt->get_result();
                $stmt->close();
                $arrvehiculos = $vehiculos->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito' => true, 'msg' => '', $arrvehiculos);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscarVehiculo(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $idVehiculos = $this->getIdVehiculo();
            $sql = "SELECT vehiculos.marca,vehiculos.anio,vehiculos.patente,vehiculos.falta, vehiculos.habilita, vehiculos.vtoseguro, vehiculos.titular, vehiculos.observa, personas.ayn FROM vehiculos, personas WHERE vehiculos.idVehiculos=? AND vehiculos.titular = personas.idPersonas";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idVehiculos);
                $stmt->execute();
                $vehiculos = $stmt->get_result();
                $stmt->close();
                $vehiculo = $vehiculos->fetch_assoc();
                $arr = array('exito'=>true, 'msg'=>'',$vehiculo);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function listarVehiculosHabilitados(){
        $arr = array('exito' => false, 'msg' => 'Hubo un error al listar');
        try {
            $arrvehiculos = array();
            $sql = "SELECT vehiculos.idVehiculos, vehiculos.marca, vehiculos.patente FROM vehiculos WHERE vehiculos.habilita = 1";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->execute();
                $vehiculos = $stmt->get_result();
                $stmt->close();
                $arrvehiculos = $vehiculos->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito' => true, 'msg' => '', $arrvehiculos);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function listarVehiculosPropietario(){
        $arr = array('exito' => false, 'msg' => 'Hubo un error al listar');
        try {
            $arrvehiculos = array();
            $idPropietario = $_SESSION['userProfile']['idPropietario'];
            $sql = "SELECT vehiculos.idVehiculos, vehiculos.marca, vehiculos.patente, vehiculos.vtoseguro, personas.ayn FROM vehiculos, personas WHERE vehiculos.titular = personas.idPersonas AND vehiculos.titular=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt !== FALSE) {
                $stmt->bind_param('i',$idPropietario);
                $stmt->execute();
                $vehiculos = $stmt->get_result();
                $stmt->close();
                $arrvehiculos = $vehiculos->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito' => true, 'msg' => '', $arrvehiculos);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}
