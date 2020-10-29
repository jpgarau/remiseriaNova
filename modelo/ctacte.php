<?php

include_once('validar.php');
include_once('conexion.php');

class Ctacte
{
    private $siglas = ['VI', 'RC', 'DB'];
    private $idCtaCte;
    private $sigla;
    private $idViaje;
    private $fecha;
    private $hora;
    private $idCliente;
    private $observa;
    private $importe;

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idCtaCte = 0;
        $this->sigla = "";
        $this->idViaje = 0;
        $this->fecha = "";
        $this->hora = "";
        $this->idCliente = 0;
        $this->observa = "";
        $this->importe = "";
    }

    //getters

    public function getIdCtaCte()
    {
        return $this->idCtaCte;
    }
    public function getSigla()
    {
        return $this->sigla;
    }
    public function getIdViaje()
    {
        return $this->idViaje;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getHora()
    {
        return $this->hora;
    }
    public function getIdCliente()
    {
        return $this->idCliente;
    }
    public function getObserva()
    {
        return $this->observa;
    }
    public function getImporte()
    {
        return $this->importe;
    }

    //setters
    public function setIdCtaCte($idCtaCte)
    {
        $this->idCtaCte = $idCtaCte;
    }
    public function setSigla($sigla)
    {
        $this->sigla = "";
        $error = false;
        $sigla = trim($sigla);
        $sigla = filter_var($sigla, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($sigla === FALSE || is_null($sigla) || strlen($sigla) === 0) $error = true;
        if (!$error && in_array($sigla, $this->siglas, true)) {
            $this->sigla = $sigla;
        }
        return $error;
    }
    public function setIdViaje($idViaje)
    {
        $this->idViaje = null;
        $error = false;
        $idViaje = filter_var($idViaje, FILTER_VALIDATE_INT);
        if ($idViaje === FALSE) $error = true;
        if (!$error) {
            $this->idViaje = $idViaje;
        }
    }
    public function setFecha($fecha)
    {
        $this->fecha = "";
        $error = false;
        $fecha = trim($fecha);
        $fecha = filter_var($fecha, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($fecha === FALSE || is_null($fecha) || strlen($fecha) === 0) $error = true;
        if (!$error) {
            $this->fecha = $fecha;
        }
        return $error;
    }
    public function setHora($hora)
    {
        $this->hora = "";
        $error = false;
        $hora = trim($hora);
        $hora = filter_var($hora, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if ($hora === FALSE || is_null($hora) || strlen($hora) === 0) $error = true;
        if (!$error) {
            $this->hora = $hora;
        }
        return $error;
    }
    public function setIdCliente($idCliente)
    {
        $this->idCliente = 0;
        $error = false;
        $idCliente = filter_var($idCliente, FILTER_VALIDATE_INT);
        if ($idCliente === FALSE) $error = true;
        if(!$error){
            $this->idCliente = $idCliente;
        }
        return $error;
    }
    public function setObserva($observa){
        $this->observa = '';
        $error = false;
        $observa = trim($observa);
        $observa = filter_var($observa, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($observa===FALSE || is_null($observa) || strlen($observa)===0) $error = true;
        if(!$error){
            $this->observa = $observa;
        }
        return $error;
    }
    public function setImporte($importe){
        $this->importe = 0;
        $error = false;
        $importe = filter_var($importe, FILTER_VALIDATE_FLOAT);
        if($importe===FALSE && $importe<0) $error = true;
        if(!$error){
            $this->importe = $importe;
        }
        return $error;
    }

    public function listarCtaCte($fechaHasta){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al listar');
        try {
            $idCliente = $this->getIdCliente();
            $fechaDesde = $this->getFecha();
            $saldo = [];
            date_default_timezone_set('America/Argentina/Buenos_Aires');
            $fechaHasta = filter_var($fechaHasta, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if($fechaHasta===FALSE || is_null($fechaHasta) || strlen($fechaHasta)===0) $fechaHasta=date("Y-m-d");
            $sql = "SELECT ctacte.idCtaCte, ctacte.sigla, ctacte.fecha, ctacte.hora, ctacte.observa, ctacte.importe, viajes.origen, viajes.destino, servicio.idVehiculo FROM ctacte LEFT JOIN viajes ON ctacte.idViaje=viajes.idViaje LEFT JOIN servicio ON viajes.idServicio=servicio.idServicio WHERE ctacte.idCliente=?";
            $sql .= $fechaDesde!==""?" AND ctacte.fecha BETWEEN ? AND ?":"";
            $sql .= " ORDER BY ctacte.fecha, ctacte.hora";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                if($fechaDesde!==""){
                    $stmt->bind_param('iss',$idCliente,$fechaDesde,$fechaHasta);
                }else{
                    $stmt->bind_param('i',$idCliente);
                }
                $stmt->execute();
                $respuesta = $stmt->get_result();
                $stmt->close();
                if($fechaDesde!==""){
                    $sql = "SELECT SUM(IF(sigla='RC',-importe,importe)) AS saldoInicial  FROM ctacte WHERE idCliente=? AND fecha<?";
                    $stmt2 = $mysqli->prepare($sql);
                    if($stmt2!==FALSE){
                        $stmt2->bind_param('is',$idCliente, $fechaDesde);
                        $stmt2->execute();
                        $res = $stmt2->get_result();
                        $stmt2->close();
                        $saldo = $res->fetch_assoc();
                    }
                }
                $sql = "SELECT SUM(IF(sigla='RC',-importe,importe)) AS saldoCtaCte FROM ctacte WHERE idCliente=?";
                $stmt3 = $mysqli->prepare($sql);
                if($stmt3!==FALSE){
                    $stmt3->bind_param('i', $idCliente);
                    $stmt3->execute();
                    $res = $stmt3->get_result();
                    $stmt3->close();
                    $mysqli->close();
                    $arrRes = $res->fetch_assoc();
                    $saldo['saldoCtaCte'] = $arrRes['saldoCtaCte'];
                    $resultados = $respuesta->fetch_all(MYSQLI_ASSOC);
                    $arr = array('exito'=>true,'msg'=>"",$resultados, $saldo);
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function agregarMovimiento(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al agregar');
        try {
            $sigla = $this->getSigla();
            $fecha = $this->getFecha();
            $hora = $this->getHora();
            $idCliente = $this->getIdCliente();
            $observa = $this->getObserva();
            $importe = $this->getImporte();
            $sql= 'INSERT INTO ctacte (sigla, fecha, hora, idCliente, observa, importe) VALUES(?,?,?,?,?,?)';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('sssisd',$sigla, $fecha, $hora, $idCliente, $observa, $importe);
                $stmt->execute();
                $stmt->close();
                $mysqli->close();
                $arr = array('exito'=>true, 'msg'=>$importe);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function eliminarMovimiento(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al eliminar');
        try {
            $idCtaCte = $this->getIdCtaCte();
            $sql = 'DELETE FROM ctacte WHERE idCtaCte=?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if ($stmt!==FALSE){
                $stmt->bind_param('i', $idCtaCte);
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

    public function agregarViajeACtaCte(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al agregar el Viaje a la CtaCte');
        try {
            $sigla = $this->getSigla();
            $idViaje = $this->getIdViaje();
            $fecha = $this->getFecha();
            $hora = $this->getHora();
            $idCliente = $this->getIdCliente();
            $importe = $this->getImporte();
            $sql = 'INSERT INTO ctacte (sigla, idViaje, fecha, hora, idCliente, importe) VALUES (?,?,?,?,?,?)';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('sissid', $sigla, $idViaje, $fecha, $hora, $idCliente, $importe);
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

    public function buscarViaje(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error en la busqueda');
        try {
            $idViaje = $this->getIdViaje();
            $sql = 'SELECT * FROM ctacte WHERE idViaje=?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i', $idViaje);
                $stmt->execute();
                $resultado = $stmt->get_result();
                $stmt->close();
                if($resultado->num_rows>0){
                    $ctacte = $resultado->fetch_assoc();
                    $arr = array('exito'=>true, 'msg'=>'', $ctacte);
                }else{
                    $arr = array('exito'=>true, 'msg'=>'No se encontro viaje',['idCtaCte'=>0]);
                }
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function modificarCtaCte(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al modificar la CtaCte');
        try {
            $idCtaCte = $this->getIdCtaCte();
            $idCliente = $this->getIdCliente();
            $importe = $this->getImporte();
            $sql = 'UPDATE ctacte SET idCliente=?, importe=? WHERE idCtaCte = ?';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('idi', $idCliente, $importe, $idCtaCte);
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
