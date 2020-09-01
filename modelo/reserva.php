<?php
include_once('validar.php');
include_once('conexion.php');

class Reserva{
    private $idReserva;
    private $fecha;
    private $hora;
    private $idCliente;
    private $origen;
    private $destino;
    private $observa;
    private $estado;

    public function __construct()
    {
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idReserva = null;
        $this->fecha = null;
        $this->hora = null;
        $this->idCliente = null;
        $this->origen = null;
        $this->destino = null;
        $this->observa = null;
        $this->estado = 0;
    }

    //getters

    public function getIdReserva(){
        return $this->idReserva;
    }
    public function getFecha(){
        return $this->fecha;
    }
    public function getHora(){
        return $this->hora;
    }
    public function getIdCliente(){
        return $this->idCliente;
    }
    public function getOrigen(){
        return $this->origen;
    }
    public function getDestino(){
        return $this->destino;
    }
    public function getObserva(){
        return $this->observa;
    }
    public function getEstado(){
        return $this->estado;
    }

    //setters

    public function setIdReserva($idReserva){
        $this->idReserva = $idReserva;
    }
    public function setFecha($fecha){
        $this->fecha = '';
        $error = false;
        $fecha = trim($fecha);
        $fecha = filter_var($fecha,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($fecha===FALSE || is_null($fecha) || strlen($fecha)===0) $error = true;
        if(!$error){
            $this->fecha = $fecha;
        }else{
            $this->fecha = $error;
        }
        return $error;
    }
    public function setHora($hora){
        $this->hora = '';
        $error = false;
        $hora = trim($hora);
        $hora = filter_var($hora,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($hora === FALSE || is_null($hora) || strlen($hora)===0) $error = true;
        if(!$error){
            $this->hora = $hora;
        }else{
            $this->hora = $error;
        }
        return $error;
    }
    public function setIdCliente($idCliente){
        $this->idCliente = null;
        $error = false;
        $idCliente = filter_var($idCliente,FILTER_VALIDATE_INT);
        if($idCliente===FALSE) $error = true;
        if(!$error){
            $this->idCliente = $idCliente;
        }
        return $error;
    }
    public function setOrigen($origen){
        $this->origen = '';
        $error = false;
        $origen = trim($origen);
        $origen = filter_var($origen,FILTER_SANITIZE_SPECIAL_CHARS);
        if($origen===FALSE || is_null($origen) || strlen($origen)===0) $error = true;
        if(!$error){
            $this->origen = $origen;
        }else{
            $this->$error;
        }
        return $error;
    }
    public function setDestino($destino){
        $this->destino = null;
        $error = false;
        $destino = trim($destino);
        $destino = filter_var($destino,FILTER_SANITIZE_SPECIAL_CHARS);
        if($destino===FALSE) $error = true;
        if(!$error){
            $this->destino = $destino;
        }
        return $error;
    }
    public function setObserva($observa){
        $this->observa = null;
        $error = false;
        $observa = trim($observa);
        $observa = filter_var($observa,FILTER_SANITIZE_SPECIAL_CHARS);
        if($observa===FALSE) $error = true;
        if(!$error){
            $this->observa = $observa;
        }
        return $error;
    }
    public function setEstado($estado){
        $this->estado = $estado;
    }

    public function agregarReserva(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al agregar');
        try {
            $fecha = $this->getFecha();
            $hora = $this->getHora();
            $idCliente = $this->getIdCliente();
            $origen = $this->getOrigen();
            $destino = $this->getDestino();
            $observa = $this->getObserva();
            $estado = 1;
            $sql = 'INSERT INTO reservas(fecha,hora,idCliente,origen,destino,observa,estado) VALUES (?,?,?,?,?,?,?)';
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ssisssi',$fecha,$hora,$idCliente,$origen,$destino,$observa,$estado);
                $stmt->execute();
                $stmt->close();
                $idReserva = $mysqli->insert_id;
                $mysqli->close();
                $arr = array('exito'=>true,'msg'=>'','idReserva'=>$idReserva);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function cancelarReserva(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al cancelar la reserva');
        try {
            $idReserva = $this->getIdReserva();
            $estado = 3;
            $sql = "UPDATE reservas SET estado=? WHERE idReserva=?";
            $myslqi = Conexion::abrir();
            $myslqi->set_charset('utf8');
            $stmt = $myslqi->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii',$estado,$idReserva);
                $stmt->execute();
                $stmt->close();
                $myslqi->close();
                $arr = array('exito'=>true,'msg'=>'');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscarReserva(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error al buscar');
        try {
            $idReserva = $this->getIdReserva();
            $sql = "SELECT * FROM reservas WHERE idReserva = ?";
            $myslqi = Conexion::abrir();
            $myslqi->set_charset('utf8');
            $stmt = $myslqi->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idReserva);
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                $myslqi->close();
                $reserva = $rs->fetch_assoc();
                $arr = array('exito'=>true,'msg'=>'',$reserva);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function actualizarReserva(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error al actualizar');
        try {
            $idReserva = $this->getIdReserva();
            $idCliente = $this->getIdCliente();
            $fecha = $this->getFecha();
            $hora = $this->getHora();
            $origen = $this->getOrigen();
            $destino = $this->getDestino();
            $observa = $this->getObserva();
            $sql = "UPDATE reservas SET idCliente=?, fecha=?, hora=?, origen=?, destino=?, observa=? WHERE idReserva=?";
            $myslqi = Conexion::abrir();
            $myslqi->set_charset('utf8');
            $stmt = $myslqi->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('isssssi',$idCliente,$fecha,$hora,$origen,$destino,$observa,$idReserva);
                $stmt->execute();
                $stmt->close();
                $myslqi->close();
                $arr = array('exito'=>true, 'msg'=>'');
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function asignarReserva(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al asignar');
        try {
            $idReserva = $this->getIdReserva();
            $estado = 2;
            $sql = "UPDATE reservas SET estado=? WHERE idReserva=?";
            $myslqi = Conexion::abrir();
            $myslqi->set_charset('utf8');
            $stmt = $myslqi->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii',$estado,$idReserva);
                $stmt->execute();
                $stmt->close();
                $myslqi->close();
                $arr = $this->buscarReserva();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarfecha(){
        $arr = array('exito'=>false,'msg'=>'Error al listar');
        try {
            $fecha = $this->getFecha();
            $sql = 'SELECT reservas.idReserva, reservas.fecha, reservas.hora, reservas.origen, reservas.destino, personas.ayn FROM reservas LEFT JOIN personas on reservas.idCliente=personas.idPersonas WHERE reservas.fecha=? AND reservas.estado = 1 ORDER BY reservas.hora';
            $myslqi = Conexion::abrir();
            $myslqi->set_charset('utf8');
            $stmt = $myslqi->prepare($sql);
            if($stmt !==FALSE){
                $stmt->bind_param('s',$fecha);
                $stmt->execute();
                $arrreservas = $stmt->get_result();
                $stmt->close();
                $reservas = $arrreservas->fetch_all(MYSQLI_ASSOC);
                $myslqi->close();
                $arr = array('exito'=>true,'msg'=>'',$reservas);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

}