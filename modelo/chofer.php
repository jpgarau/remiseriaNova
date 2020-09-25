<?php
include_once('validar.php');
include_once('conexion.php');
include_once('persona.php');

class Chofer extends Persona{
    private $idChofer;
    private $comision;
    private $nrolicencia;

    public function __construct(){
        $driver = new mysqli_driver();
    	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
		$this->idChofer = 0;
        $this->comision = 0;
        $this->nrolicencia = '';
    }

    //getters
    
    public function getIdChofer(){
        return $this->idChofer;
    }
    public function getComision(){
        return $this->comision;
    }
    public function getNroLicencia(){
        return $this->nrolicencia;
    }

    //setters

    public function setIdChofer($idChofer){
        $this->idChofer = $idChofer;
    }
    public function setComision($comision){
        $this->comision = 0;
        $error = false;
        $comision = filter_var($comision, FILTER_VALIDATE_INT);
        if($comision===FALSE || is_null($comision)) $error = true;
        if(!$error){
            $this->comision = $comision;
        }
        return $error;
    }
    public function setNroLicencia($nrolicencia){
        $this->nrolicencia = "";
        $error = false;
        $nrolicencia = filter_var($nrolicencia, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nrolicencia===FALSE || is_null($nrolicencia) || strlen($nrolicencia)===0) $error = true;
        if(!$error){
            $this->nrolicencia = $nrolicencia;
        }
        return $error;
    }

    public function agregarChofer(){
        $arr = array('exito'=>false,'msg'=>'Error en agregar Chofer');
		try{
            $persona = $this->agregarPersona();
            if($persona['exito']){
                $idChofer = $persona['idPersona'];
                $comision = $this->getComision();
                $nrolicencia = $this->getNroLicencia();
                $sql = "INSERT INTO choferes (idChofer, comision, nrolicencia) VALUES (?,?,?)";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('iis',$idChofer,$comision,$nrolicencia);
                    $stmt->execute();
                    $stmt->close();
                }
                $arr = $persona;
			}
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
		}
		return $arr;
    }
    public function modificarChofer(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un Error');
		try{
            $persona = $this->modificarPersona();
            $arr=$persona;
            if($persona['exito']){
                $comision = $this->getComision();
                $nrolicencia = $this->getNroLicencia();
                $idChofer = $this->getIdChofer();
                $sql = "UPDATE choferes SET comision=?, nrolicencia=? WHERE idChofer=?";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('isi',$comision,$nrolicencia,$idChofer);
                    $stmt->execute();
                    $stmt->close();
                }
                $arr=$persona;
            }
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
		}
		return $arr;
    }
    public function eliminarChofer(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al eliminar');
        try{
            $idChofer = $this->getIdChofer();
            $sql = "DELETE FROM choferes WHERE idChofer=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idChofer);
                $stmt->execute();
                $stmt->close();
            }
            $arr = array('exito'=>true, 'msg'=>'');
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();

        }
        return $arr;
    }
    public function buscarChofer($idChofer){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error en la busqueda del chofer');
        try{
			$sql = "SELECT * FROM personas, choferes WHERE idChofer = ? AND personas.idPersonas = choferes.idChofer";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->bind_param('i',$idChofer);
				$stmt->execute();
				$chofer = $stmt->get_result();
                $stmt->close();
                $datosChofer = $chofer->fetch_assoc();
                $datosChofer['ayn'] = htmlspecialchars_decode($datosChofer['ayn'],ENT_QUOTES);
                $arr = array('exito'=>true, 'msg'=>'', $datosChofer);
			}
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function listarChofer(){
        $arr = array('exito'=>false, 'msg'=>'Error al listar');
        try{
			$arrchofer = array();
			$sql = "SELECT * FROM personas, choferes WHERE personas.idPersonas = choferes.idChofer ORDER BY personas.ayn"; //personas.idtipopersona=1 AND 
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->execute();
				$arrchofer = $stmt->get_result();
                $stmt->close();
                $choferes = $arrchofer->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'', $choferes);
			}			
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
        }		
        return $arr;
    }
    
    public function listarCuit(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrcuit = array();
            $sql = "SELECT cuit FROM personas, choferes WHERE personas.idPersonas = choferes.idChofer AND personas.cuit IS NOT NULL";
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
    public function listarDniChoferes(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrDni = array();
            $sql = "SELECT nrodoc FROM personas, choferes WHERE personas.idPersonas = choferes.idChofer AND personas.nrodoc IS NOT NULL";
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
            $sql = "SELECT DISTINCT nrodoc FROM personas, choferes WHERE personas.idPersonas NOT IN(SELECT idChofer From choferes) AND personas.nrodoc IS NOT NULL ";
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
    public function vincularChofer(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $idchofer = $this->getIdChofer();
            $sql = "INSERT INTO choferes (idChofer) VALUES (?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idchofer);
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
    public function vincularChoferExistente($idChoferActual){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error');
        try {
            $idChofer = $this->getIdChofer();
            $sql = "UPDATE choferes SET idChofer=? WHERE idChofer=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $idChofer, $idChoferActual);
                $stmt->execute();
                $stmt->close();
                $sql = "UPDATE servicio SET idChofer=? WHERE idChofer=?";
                $stmt2 = $mysqli->prepare($sql);
                if($stmt2!==FALSE){
                    $stmt2->bind_param('ii',$idChofer, $idChoferActual);
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