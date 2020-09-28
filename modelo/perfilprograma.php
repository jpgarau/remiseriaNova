<?php
include_once("validar.php");
include_once("conexion.php");

class PerfilPrograma{
    private $perfilid;
    private $programaid;

    public function __construct(){
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->perfilid = 0;
        $this->programaid = 0;
    }

    //getters

    public function getPerfilId(){
        return $this->perfilid;
    }
    public function getProgramaId(){
        return $this->programaid;
    }

    //setters

    public function setPerfilId($perfilid){
        $this->perfilid = $perfilid;
    }

    public function setProgramaId($programaid){
        $this->programaid = $programaid;
    }

    public function actualizar($programas,$perfilid){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al actualizar');
        try {
            $agregar = array();
            $programasActivos = array();
            $quitar = array();
            $respuesta = array();
            
            if(count($programas)==0){
                $sql = 'DELETE FROM perfilprograma WHERE perfilid=?';
                $mysqli = Conexion::abrir();
                $mysqli->set_charset('utf8');
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('i',$perfilid);
                    $stmt->execute();
                    $stmt->close();
                    $arr = array('exito'=>true,'msg'=>'');
                }
                return $arr;
            }else{
                $mysqli = Conexion::abrir();
                $mysqli->set_charset('utf8');
                $sql = 'SELECT programaid FROM perfilprograma WHERE perfilid=?';
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('i',$perfilid);
                    $stmt->execute();
                    $respuesta = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                    if(count($respuesta)>0){
                        foreach ($respuesta as $r){
                            $programasActivos[]=$r['programaid'];
                        }
                    }
                    $stmt->close();
                }
                if(count($programasActivos)===0){
                    foreach ($programas as $p) {
                            $agregar[]=$p;
                    }
                }else{
                    foreach ($programas as $p) {
                        if(!in_array(intval($p),$programasActivos,false)){
                            $agregar[]=intval($p);
                        };
                    }
                    foreach ($programasActivos as $pa) {
                        if(!in_array($pa,$programas,false)){
                            $quitar[]=$pa;
                        }
                    }
                }
                
                $cantAgregar = count($agregar);
                if($cantAgregar>0){
                    $sqlagregar = 'INSERT INTO perfilprograma(perfilid, programaid) VALUES ';
                    foreach ($agregar as $a) {
                        $sqlagregar.='('.$perfilid.','.$a.')';
                        --$cantAgregar;
                        if($cantAgregar!==0){
                         $sqlagregar.=', ';
                        }
                    }
                    $mysqli = Conexion::abrir();
                    $mysqli->set_charset('utf8');
                    $stmt = $mysqli->prepare($sqlagregar);
                    $stmt->execute();
                    $stmt->close();
                }
                
                $cantQuitar = count($quitar);
                if($cantQuitar>0){
                    $sqlquitar = 'DELETE FROM perfilprograma WHERE ';
                    foreach ($quitar as $q) {
                        $sqlquitar.='(perfilid='.$perfilid.' AND programaid='.$q.')';
                        --$cantQuitar;
                        if($cantQuitar!==0){
                            $sqlquitar.=' OR ';
                        }
                    }
                    $mysqli = Conexion::abrir();
                    $mysqli->set_charset('utf8');
                    $stmt = $mysqli->prepare($sqlquitar);
                    $stmt->execute();
                    $stmt->close();
                }
                
                $arr = array('exito'=>true, 'msg'=>'');
                $mysqli->close();
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    
    public function agregar(){
        try {
            $error = true;
            $perfilid = $_SESSION['sperfilid'];
            $programaid = $_SESSION['sprogramaid'];
            $sql = "INSERT INTO perfilprograma (perfilid, programaid) VALUES (?,?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $perfilid, $programaid);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    public function eliminar(){
        try {
            $error = true;
            $perfilid = $_SESSION['sperfilid'];
            $programaid = $_SESSION['sprogramaid'];
            $sql = "DELETE FROM perfilprograma WHERE perfilid=? AND programaid=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $perfilid, $programaid);
                $stmt->execute();
                $stmt->close();
            }
            $error = false;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    public function listar(){
        try{
            $arr = array('exito'=>false,'msg'=>'Error al Listar');
            $arrperpro = array();
            $perfilid = $this->getPerfilId();
            $sql = "SELECT programaid FROM perfilprograma WHERE perfilid=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param("i", $perfilid);
                $stmt->execute();
                $arrperpro = $stmt->get_result();
                $programas = $arrperpro->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                $arr=array('exito'=>true,'msg'=>'',$programas);
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarProgramas(){
        $arr = array('exito'=>false,'msg'=>'Error al Listar');
        try{
            $perfilid = $this->getPerfilId();
            $sql = "SELECT * FROM perfilprograma, programa WHERE perfilid=? AND perfilprograma.programaid=programa.programaid AND programa.estado=0 ORDER BY ORDEN";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param("i", $perfilid);
                $stmt->execute();
                $rs = $stmt->get_result();
                $programas = $rs->fetch_all(MYSQLI_ASSOC);
                $encontrados = $rs->num_rows;
                $stmt->close();
                $arr=array('exito'=>true,'msg'=>'', 'encontrados'=>$encontrados, $programas);
                $mysqli->close();
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
}
