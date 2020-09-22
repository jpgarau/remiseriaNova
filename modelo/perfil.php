<?php
include_once('validar.php');
include_once('conexion.php');
class Perfil{
	private $perfilid;
	private $descripcion;
	private $estado;

	public function __construct(){
		$driver = new mysqli_driver();
    	$driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
		$this->perfilid = 0;
		$this->descripcion = '';
		$this->estado = 0;
	}
	public function getPerfilId(){
		return $this->perfilid;
	}
	public function setPerfilId($perfilid){
		$this->perfilid = 0;
		$error = false;
		$perfilid = filter_var($perfilid, FILTER_VALIDATE_INT);
		if($perfilid===FALSE || is_null($perfilid)) $error = true;
		if(!$error){
			$this->perfilid = $perfilid;
		}else{
			$this->perfilid = $error;
		}
		return $error;
	}
	public function getDescripcion(){
		return $this->descripcion;
	}
	public function setDescripcion($descripcion){
		$this->descripcion = '';
		$error = false;
		$descripcion = filter_var($descripcion, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		if($descripcion===FALSE || is_null($descripcion) || strlen($descripcion)===0) $error = true;
		if(!$error){
			$this->descripcion = $descripcion;
		}else{
			$this->descripcion = $error;
		}
		
		return $error;
	}
	public function getEstado(){
		return $this->estado;
	}
	public function setEstado($estado){
		$this->estado = $estado;
	}
	public function agregar(){
		try{
			$arr = array('exito'=>false, 'msg'=>'Error en agregar perfil');
			$descripcion = $this->getDescripcion();
			$estado = 0;
			if(!is_bool($descripcion)){
				$sql = "INSERT INTO perfil (descripcion, estado) VALUES (?,?)";
				$mysqli = Conexion::abrir();
				$mysqli->set_charset("utf8");
				$stmt = $mysqli->prepare($sql);
				if($stmt!==FALSE){
					$stmt->bind_param('si',$descripcion,$estado);
					$stmt->execute();
					$stmt->close();
				}
				$id = $mysqli->insert_id;
				$arr = array('exito'=>true, 'msg'=>'', 'id'=>$id);
			}
		}catch(Exception $e){
			$arr = array('exito'=>false, 'msg'=>$e->getMessage());
		}
		return $arr;
	}
	public function modificar(){
		try{
			$arr = array('exito'=>false, 'msg'=>'Error al modificar el Perfil');
			$descripcion = $this->getDescripcion();
			$perfilid = $this->getPerfilId();
			if(!is_bool($descripcion) && !is_bool($perfilid)){

				$sql = "UPDATE perfil SET descripcion = ? WHERE perfilid = ?";
				$mysqli = Conexion::abrir();
				$mysqli->set_charset("utf8");
				$stmt = $mysqli->prepare($sql);
				if($stmt!==FALSE){
					$stmt->bind_param('si',$descripcion,$perfilid);
					$stmt->execute();
					$stmt->close();
				}
				$arr = array('exito'=>true,'msg'=>'');	
			}
		}catch(Exception $e){
			$arr = array('exito'=>false, 'msg'=>$e->getMessage());
		}
		return $arr;
	}
	public function eliminar(){
		try{
			$arr = array('exito'=>false, 'msg'=>'Error al modificar el Perfil');
			$perfilid = $this->getPerfilId();
			$estado = 99;
			if(!is_bool($perfilid)){
				$sql = "UPDATE perfil SET estado = ? WHERE perfilid = ?";
				$mysqli = Conexion::abrir();
				$stmt = $mysqli->prepare($sql);
				if($stmt!==FALSE){
					$stmt->bind_param('ii',$estado,$perfilid);
					$stmt->execute();
					$stmt->close();
				}
				$arr = array('exito'=>true, 'msg'=>'');
			}
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
		}
		return $arr;
	}
	public function buscar($busca='n'){
		try{
			if($busca==='n'){
				$perfilid = $_SESSION['sperfilid'];
			}else{
				$perfilid = $busca;
			}

			$sql = "SELECT descripcion, estado FROM perfil WHERE perfilid = ?";
			$mysqli = Conexion::abrir();
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->bind_param('i',$perfilid);
				$stmt->execute();
				$rs = $stmt->get_result();
				while($fila=$rs->fetch_array()){
					$this->setPerfilId($perfilid);
					$this->setDescripcion($fila[0]);
					$this->setEstado($fila[1]);
				}
				$stmt->close();
				return $this;
			}else{
				return true;
			}			
		}catch(Exception $e){
			return $e->getMessage();
		}		
	}
	public function listar(){
		$arr = array('exito'=>false,'msg'=>'Error al Listar');
		try{
			$arrperfil = array();
			$perfilid = $_SESSION['userProfile']['perfilid'];
			$sql = "SELECT perfilid, descripcion, estado FROM perfil WHERE estado = 0 AND perfilid >= ?";
			$mysqli = Conexion::abrir();
			$stmt = $mysqli->prepare($sql);
			if($stmt!==FALSE){
				$stmt->bind_param('i', $perfilid);
				$stmt->execute();
				$arrperfil = $stmt->get_result();
				$stmt->close();
				$perfiles = $arrperfil->fetch_all(MYSQLI_ASSOC);
				$arr = array('exito'=>true,'msg'=>'' , $perfiles);
			}			
		}catch(Exception $e){
			$arr['msg'] = $e->getMessage();
		}
		return $arr;		
	}
}
?>