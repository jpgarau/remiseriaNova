<?php

include_once('validar.php');
include_once('conexion.php');
include_once('persona.php');

class Cliente extends Persona{
    private $idClientes;
    private $ctaCte;

    public function __construct(){
        $driver = new mysqli_driver();
    	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
		$this->idClientes = 0;
        $this->ctaCte = 0;
    }

    //getters
    public function getIdClientes(){
        return $this->idClientes;
    }
    public function getCtaCte(){
        return $this->ctaCte;
    }

    //setters
    public function setIdClientes($idClientes){
        $this->idClientes = $idClientes;
    }
    public function setCtaCte($ctaCte){
        $this->ctaCte = $ctaCte;
    }

    public function agregarCliente(){
        $arr = array('exito'=>false,'msg'=>'Error en agregar');
		try{
            $persona = $this->agregarPersona();
            if($persona['exito']){
                $idClientes = $persona['idPersona'];
                $ctacte = null;
                $sql = "INSERT INTO clientes (idClientes, ctacte) VALUES (?,?)";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('ii',$idClientes,$ctacte);
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

    public function modificarCliente(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un Error');
		try{
            $persona = $this->modificarPersona();
            if($persona['exito']){
                $arr=$persona;
            }
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
		}
		return $arr;
    }

    public function eliminarCliente(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al eliminar');
        try{
            $idClientes = $this->getIdClientes();
            $sql = "DELETE FROM clientes WHERE idClientes=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idClientes);
                $stmt->execute();
                $stmt->close();
            }
            $arr = array('exito'=>true, 'msg'=>'');
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();

        }
        return $arr;
    }

    public function buscarCliente($idClientes){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error en la busqueda');
        try{
			$sql = "SELECT * FROM personas, clientes WHERE idClientes = ? AND personas.idPersonas = clientes.idClientes";
			$mysqli = Conexion::abrir();
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->bind_param('i',$idClientes);
				$stmt->execute();
				$cliente = $stmt->get_result();
                $stmt->close();
                $datosCliente = $cliente->fetch_assoc();
                $datosCliente['ayn'] = htmlspecialchars_decode($datosCliente['ayn'],ENT_QUOTES);
                $arr = array('exito'=>true, 'msg'=>'', $datosCliente);
			}
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarCliente(){
        $arr = array('exito'=>false, 'msg'=>'Error al listar');
        try{
			$arrcliente = array();
			$sql = "SELECT * FROM personas, clientes WHERE personas.idPersonas = clientes.idClientes ORDER BY personas.ayn";  
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->execute();
				$arrcliente = $stmt->get_result();
                $stmt->close();
                $clientes = $arrcliente->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true, 'msg'=>'', $clientes);
			}			
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
        }		
        return $arr;
    }

    public function listarCuit(){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error al listar');
        try{
			$arrclientes = array();
			$sql = "SELECT cuit FROM personas, clientes WHERE personas.idPersonas = clientes.idClientes AND personas.cuit IS NOT NULL";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->execute();
				$arrclientes = $stmt->get_result();
                $stmt->close();
                $respuesta = $arrclientes->fetch_all(MYSQLI_ASSOC);
				$arr = array('exito'=>true, 'msg'=>$respuesta, $respuesta);
			}
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    
    public function listarDniClientes(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $arrDni = array();
            $sql = "SELECT nrodoc FROM personas, clientes WHERE personas.idPersonas = clientes.idClientes AND personas.nrodoc IS NOT NULL";
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
            $sql = "SELECT DISTINCT nrodoc FROM personas, clientes WHERE personas.idPersonas NOT IN(SELECT idClientes From clientes) AND personas.nrodoc IS NOT NULL ";
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

    public function vincularCliente(){
        $arr = array('exito'=>false,'msg'=>'Hubo un error');
        try {
            $idClientes = $this->getIdClientes();
            $sql = "INSERT INTO clientes (idClientes) VALUES (?)";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$idClientes);
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

    public function vincularClienteExistente($idClientesActual){
        $arr = array('exito'=>false, 'msg'=>'Hubo un error');
        try {
            $idClientes = $this->getIdClientes();
            $sql = "UPDATE clientes SET idClientes=? WHERE idClientes=?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset('utf8');
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii', $idClientes, $idClientesActual);
                $stmt->execute();
                $stmt->close();
                $sql = "UPDATE reservas SET idCliente=? WHERE idCliente=?";
                $stmt2 = $mysqli->prepare($sql);
                if($stmt2!==FALSE){
                    $stmt2->bind_param('ii',$idClientes, $idClientesActual);
                    $stmt2->execute();
                    $stmt2->close();
                    $sql = "UPDATE viajes SET idCliente=? WHERE idCliente=?";
                    $stmt3 = $mysqli->prepare($sql);
                    if($stmt3!==FALSE){
                        $stmt3->bind_param('ii',$idClientes, $idClientesActual);
                        $stmt3->execute();
                        $stmt3->close();
                        $mysqli->close();
                        $arr = array('exito'=>true, 'msg'=>'');
                    }
                }
            }
        } catch (Exception $e) {
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    
}