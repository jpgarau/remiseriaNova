<?php
include_once("validar.php");
include_once("conexion.php");

class Persona{
    private $idPersonas;
    private $ayn;
    private $telefono;
    private $domicilio;
    private $tipodoc;
    private $nrodoc;
    private $nacido;
    private $email;
    private $iva;
    private $cuit;
    private $idlocalidad;
    private $observa;
    private $telid;

    public function __construct(){
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->idPersonas = 0;
        $this->ayn = '';
        $this->telefono = '';
        $this->domicilio = '';
        $this->tipodoc = '';
        $this->nrodoc = '';
        $this->nacido = null;
        $this->email = '';
        $this->iva = 0;
        $this->cuit = 0;
        $this->idlocalidad = 0;
        $this->observa = '';
        $this->telid = null;
    }

    //getters

    public function getIdPersonas(){
        return $this->idPersonas;
    }
    public function getAyN(){
        return $this->ayn;
    }
    public function getTelefono(){
        return $this->telefono;
    }
    public function getDomicilio(){
        return $this->domicilio;
    }
    public function getTipoDoc(){
        return $this->tipodoc;
    }
    public function getNroDoc(){
        return $this->nrodoc;
    }
    public function getNacido(){
        return $this->nacido;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getIva(){
        return $this->iva;
    }
    public function getCuit(){
        return $this->cuit;
    }
    public function getIdLocalidad(){
        return $this->idlocalidad;
    }
    public function getObserva(){
        return $this->observa;
    }
    public function getTelId(){
        return $this->telid;
    }

    //setters

    public function setIdPersonas($idPersonas){
        $this->idPersonas = $idPersonas;
    }
    public function setAyN($ayn){
        $this->ayn = '';
        $error = false;
        $ayn = trim($ayn);
        $ayn = filter_var($ayn,FILTER_SANITIZE_SPECIAL_CHARS);
        if($ayn===FALSE || is_null($ayn) || strlen($ayn)===0) $error = true;
        if(!$error){
            $this->ayn = $ayn;
        }else{
            $this->ayn = $error;
        }
        return $error;
    }
    public function setTelefono($telefono){
        $this->telefono = '';
        $error = false;
        $telefono = trim($telefono);
        $telefono = filter_var($telefono, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($telefono===FALSE || is_null($telefono) || strlen($telefono)===0) $error = true;
        if(!$error){
            $this->telefono = $telefono;
        }
        return $error;
    }
    public function setDomicilio($domicilio){
        $this->domicilio = '';
        $error = false;
        $domicilio = trim($domicilio);
        $domicilio = filter_var($domicilio, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($domicilio===FALSE || is_null($domicilio) || strlen($domicilio)===0) $error = true;
        if(!$error){
            $this->domicilio = $domicilio;
        }
        return $error;
    }
    public function setTipoDoc($tipodoc){
        $this->tipodoc = '';
        $error = false;
        $tipodoc = trim($tipodoc);
        $tipodoc = filter_var($tipodoc,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($tipodoc===FALSE || is_null($tipodoc) || strlen($tipodoc)===0) $error = true;
        if(!$error){
            $this->tipodoc = $tipodoc;
        }
        return $error;
    }
    public function setNroDoc($nrodoc){
        $this->nrodoc = NULL;
        $error = false;
        $nrodoc = trim($nrodoc);
        $nrodoc = filter_var($nrodoc, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nrodoc===FALSE || is_null($nrodoc) || strlen($nrodoc)<=7) $error = true;
        if(!$error){
            $this->nrodoc = $nrodoc;
        }
        return $error;
    }
    public function setNacido($nacido){
        $this->nacido = NULL;
        $error = false;
        $nacido = trim($nacido);
        $nacido = filter_var($nacido,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nacido===FALSE || is_null($nacido) || strlen($nacido)===0) $error = true;
        if(!$error){
            $this->nacido = $nacido;
        }
        return $error;
    }
    public function setEmail($email){
        $this->email = '';
        $error = false;
        $email = trim($email);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if($email===FALSE || is_null($email) || strlen($email)===0) $error = true;
        if(!$error){
            $this->email = $email;
        }
        return $error;
    }
    public function setIva($iva){
        $this->iva = 0;
        $error = false;
        $iva = filter_var($iva, FILTER_VALIDATE_INT);
        if($iva===FALSE || is_null($iva)) $error = true;
        if(!$error){
            $this->iva = $iva;
        }
        return $error;
    }
    public function setCuit($cuit){
        $this->cuit = NULL;
        $error = false;
        $cuit = 
        $cuit = filter_var($cuit, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($cuit===FALSE || is_null($cuit) || strlen($cuit)===0) $error = true;
        if(!$error){
            $this->cuit = $cuit;
        }
        return $error;
    }
    public function setIdLocalidad($idlocalidad){
        $this->idlocalidad = 0;
        $error = false;
        $idlocalidad = filter_var($idlocalidad, FILTER_VALIDATE_INT);
        if($idlocalidad===FALSE || is_null($idlocalidad)) $error = true;
        if(!$error){
            $this->idlocalidad = $idlocalidad;
        }
        return $error;
    }
    public function setObserva($observa){
        $this->observa = '';
        $error = false;
        $observa = filter_var($observa, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($observa===FALSE || is_null($observa) || strlen($observa)===0) $error = true;
        if(!$error){
            $this->observa = $observa;
        }
        return $error;
    }
    public function setTelId($telid){
        $this->telid = null;
        $error = false;
        $telid = filter_var($telid, FILTER_VALIDATE_INT);
        if($telid===FALSE || $telid < 1) $error = true;
        if(!$error){
            $this->telid = $telid;
        }
        return $error;
    }

    public function agregarPersona(){
        $arr = array('exito'=>false, 'msg'=>'Error en agregar Persona');
        try {
            $ayn = $this->getAyN();
            $telefono = $this->getTelefono();
            $domicilio = $this->getDomicilio();
            $tipodoc = $this->getTipoDoc();
            $nrodoc = $this->getNroDoc();
            $nacido = $this->getNacido();
            $email = $this->getEmail();
            $iva = $this->getIva();
            $cuit = $this->getCuit();
            $idlocalidad = $this->getIdLocalidad();
            $observa = $this->getObserva();
            $telid = $this->getTelId();
            $cuitvalido = $iva==1?false:is_null($cuit);
            if(!(is_bool($ayn) || $cuitvalido)){
                $sql = "INSERT INTO personas (ayn, telefono, domicilio, tipodoc, nrodoc, nacido, email, iva, cuit, idlocalidad, observa, telid) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('sssssssisisi',$ayn,$telefono,$domicilio,$tipodoc,$nrodoc,$nacido,$email,$iva,$cuit,$idlocalidad, $observa, $telid);
                    $stmt->execute();
                    $stmt->close();
                    $idPersona = $mysqli->insert_id;
                    $mysqli->close();
                    $arr = array('exito'=>true, 'msg'=>'', 'idPersona'=>$idPersona);
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function modificarPersona(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al modificar');
        try {
            $ayn = $this->getAyN();
            $telefono = $this->getTelefono();
            $domicilio = $this->getDomicilio();
            $tipodoc = $this->getTipoDoc();
            $nrodoc = $this->getNroDoc();
            $nacido = $this->getNacido();
            $email = $this->getEmail();
            $iva = $this->getIva();
            $cuit = $this->getCuit();
            $idlocalidad = $this->getIdLocalidad();
            $idPersonas = $this->getIdPersonas();
            $observa = $this->getObserva();
            $telid = $this->getTelId();
            if(!(is_bool($ayn))){
                $sql = "UPDATE personas SET ayn=?, telefono=?, domicilio=?, tipodoc=?, nrodoc=?, nacido=?, email=?, iva=?, cuit=?, idlocalidad=?, observa=?, telid=? WHERE idPersonas=?";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset('utf8');
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('sssssssisisii', $ayn, $telefono, $domicilio, $tipodoc, $nrodoc, $nacido, $email, $iva, $cuit, $idlocalidad, $observa, $telid, $idPersonas);
                    $stmt->execute();
                    $stmt->close();
                    $mysqli->close();
                    $arr = array('exito'=>true, 'msg'=>'');
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function eliminarPersona(){
        try {
            $error = true;
            $idPersonas = $_SESSION['$sidPersonas'];
            $sql = "DELETE FROM personas WHERE idPersonas=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idPersonas);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }
    public function buscarPersona(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error en la busqueda');
        try {
            $idPersonas = $_SESSION['$sidPersonas'];
            $sql = "SELECT ayn, telefono, domicilio, tipodoc, nrodoc, nacido, email, iva, cuit, idlocalidad, idtipopersona, observa, telid FROM personas WHERE idPersonas=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idPersonas);
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                $datosPersona=$rs->fetch_assoc();
                $datosPersona['ayn'] = htmlspecialchars_decode($datosPersona['ayn'],ENT_QUOTES);
                $arr = array('exito'=>true, 'msg'=>'', $datosPersona);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function listarPersona(){
        $arr = array('exito'=>false, 'msg'=>'Error al listar');
        try {
            $arrpersonas = array();
            $sql = "SELECT * FROM personas";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrpersonas = $stmt->get_result();
                $stmt->close();
                $personas = $arrpersonas->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'',$personas);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarCuit(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrcuit = array();
            $sql = "SELECT cuit FROM personas WHERE cuit IS NOT NULL";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrcuit = $stmt->get_result();
                $stmt->close();
                $respuesta = $arrcuit->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'',$respuesta);
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscarPersonaDni(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error en la busqueda');
        try {
            $nrodoc = $this->getNroDoc();
            $sql="SELECT * FROM personas WHERE nrodoc=?";
            $mysqli=Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('s',$nrodoc);
                $stmt->execute();
                $persona = $stmt->get_result();
                $stmt->close();
                $datosPersona = $persona->fetch_assoc();
                $datosPersona['ayn'] = htmlspecialchars_decode($datosPersona['ayn'],ENT_QUOTES);
                $arr= array('exito'=>true,'msg'=>'',$datosPersona);
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg']=$e->getMessage();
        }
        return $arr;
    }
}