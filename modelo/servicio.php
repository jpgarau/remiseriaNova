<?php

include_once('validar.php');
include_once('conexion.php');

class Servicio{
    private $idServicio;
    private $idChofer;
    private $idVehiculo;
    private $fechaEnt;
    private $horaEnt;
    private $fechaSal;
    private $horaSal;
    private $estado;

    public function __construct()
    {   
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idServicio = 0;
        $this->idChofer = 0;
        $this->idVehiculo = 0;
        $this->fechaEnt = null;
        $this->horaEnt = null;
        $this->fechaSal = null;
        $this->horaSal = null;
        $this->estado = 0;
    }

    // getters

    public function getIdServicio(){
        return $this->idServicio;
    }
    public function getIdChofer(){
        return $this->idChofer;
    }
    public function getIdVehiculo(){
        return $this->idVehiculo;
    }
    public function getFechaEnt(){
        return $this->fechaEnt;
    }
    public function getHoraEnt(){
        return $this->horaEnt;
    }
    public function getFechaSal(){
        return $this->fechaSal;
    }
    public function getHoraSal(){
        return $this->horaSal;
    }
    public function getEstado(){
        return $this->estado;
    }

    //setters

    public function setIdServicio($idServicio){
        $this->idServicio = $idServicio;
    }
    public function setIdVehiculo($idVehiculo){
        $this->idVehiculo = $idVehiculo;
    }
    public function setIdChofer($idChofer){
        $this->idChofer = $idChofer;
    }
    public function setFechaEnt($fechaEnt){
        $this->fechaEnt = $fechaEnt;
    }
    public function setHoraEnt($horaEnt){
        $this->horaEnt = $horaEnt;
    }
    public function setFechaSal($fechaSal){
        $this->fechaSal = $fechaSal;
    }
    public function setHoraSal($horaSal){
        $this->horaSal = $horaSal;
    }
    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function iniciarServicio(){
        $arr = array('exito'=>'false','msg'=>'Error al iniciar el servicio');
        try {
            $idChofer = $this->getIdChofer();
            $idVehiculo = $this->getIdVehiculo();
            $fechaEnt = $this->getFechaEnt();
            $horaEnt = $this->getHoraEnt();
            $estado = 1;
            $sql = "INSERT INTO servicio(idChofer,idVehiculo,fechaEnt,horaEnt,estado) VALUES(?,?,?,?,?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('iissi',$idChofer,$idVehiculo,$fechaEnt,$horaEnt,$estado);
                $stmt->execute();
                $stmt->close();
                $idServicio = $mysqli->insert_id;
                $mysqli->close();
                $arr = array('exito'=>true,'msg'=>'','idServicio'=>$idServicio);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function terminarServicio(){
        $arr = array('exito'=>false,'msg'=>'Error al terminar el servicio');
        try {
            $idServicio = $this->getIdServicio();
            $fechaSal = $this->getFechaSal();
            $horaSal = $this->getHoraSal();
            $estado = 0;
            $sql = "UPDATE servicio SET fechaSal=?, horaSal=?, estado=? WHERE idServicio = ?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ssii',$fechaSal,$horaSal,$estado,$idServicio);
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

    public function listarServiciosActivos(){
        $arr = array('exito'=>false, 'msg'=>'Error al listar');
        try {
            $arrservicios = array();
            $sql = "SELECT servicio.idServicio, vehiculos.idVehiculos, vehiculos.marca, vehiculos.patente, personas.ayn, choferes.idChofer FROM servicio, vehiculos, personas, choferes WHERE servicio.estado != 0 AND vehiculos.idVehiculos = servicio.idVehiculo AND choferes.idChofer=servicio.idChofer AND personas.idPersonas=servicio.idChofer ORDER BY servicio.idVehiculo";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('UTF8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrservicios = $stmt->get_result();
                $stmt->close();
                $mysqli->close();
                $servicios = $arrservicios->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true,'msg'=>'',$servicios);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function buscarServicioPorChofer(){
        $arr = array('exito'=>false, 'msg'=>'Error al buscar');
        try {
            $idChofer = $this->getIdChofer();
            $sql = 'SELECT idServicio FROM servicio WHERE idChofer=? AND estado=1';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idChofer);
                $stmt->execute();
                $rs = $stmt->get_result();
                $encontrados = $rs->num_rows;
                $stmt->close();
                if($encontrados>0){
                    $respuesta = $rs->fetch_assoc();
                    $arr = array('exito'=>true, 'msg'=>'', 'encontrados'=>$encontrados, $respuesta);
                }else{
                    $arr = array('exito'=>true, 'msg'=>'', 'encontrados'=>$encontrados);
                }
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}