<?php

include_once 'conexion.php';

class Pila{
    private $idPila = 0;
    private $pila = 0;
    private $idServicio = 0;
    private $fechaHora = "";

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idPila = 0;
        $this->pila = 0;
        $this->idServicio = 0;
        $this->fechaHora = '';
    }

    //setters

    public function setIdPila($idPila){
        $this->idPila = $idPila;
    }
    public function setPila($pila){
        $this->pila = $pila;
    }
    public function setIdServicio($idServicio){
        $this->idServicio = $idServicio;
    }
    public function setFechaHora($fechaHora){
        $this->fechaHora = $fechaHora;
    }

    //getters

    public function getIdPila(){
        return $this->idPila;
    }
    public function getPila(){
        return $this->pila;
    }
    public function getIdServicio(){
        return $this->idServicio;
    }
    public function getFechaHora(){
        return $this->fechaHora;
    }

    public function agregar(){
        $arr = array('exito'=>false, 'msg'=>'Error al agregar');
        try {
            $pila = $this->getPila();
            $idServicio = $this->getIdServicio();
            $sql = 'INSERT INTO pila (pila, idServicio) VALUES (?,?)';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $pila, $idServicio);
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

    public function eliminar(){
        $arr = array('exito'=>false, 'msg'=>'Error al eliminar');
        try {
            $idServicio = $this->getIdServicio();
            $sql = 'DELETE FROM pila WHERE idServicio=?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idServicio);
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

    public function buscarServicio(){
        $arr = array('exito'=>false, 'msg'=>'Error al buscar el servicio');
        try {
            $idServicio = $this->getIdServicio();
            $sql = 'SELECT * FROM pila WHERE idServicio=?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idServicio);
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                $resultado = $rs->fetch_assoc();
                $arr = array('exito'=>true, 'msg'=>'', 'encontrados'=>$rs->num_rows, $resultado);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listar(){
        $arr = array('exito'=>false, 'msg'=>'Error al buscar el servicio');
        try {
            $sql = 'SELECT pila.idPila, pila.pila, pila.idServicio, servicio.idVehiculo, personas.ayn FROM pila LEFT JOIN servicio ON pila.idServicio=servicio.idServicio LEFT JOIN personas ON servicio.idChofer=personas.idPersonas ORDER BY fechaHora';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                $resultado = $rs->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'', 'encontrados'=>$rs->num_rows, $resultado);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}