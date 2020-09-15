<?php
include_once('validar.php');
include_once('conexion.php');

class Programa{
    private $programaid;
    private $nombre;
    private $link;
    private $padre;
    private $esopcion;
    private $orden;
    private $estado;

    public function __construct(){
        $driver = new mysqli_driver();
        $driver->report_mode = MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
        $this->programaid = 0;
        $this->nombre = '';
        $this->link = '';
        $this->padre = '';
        $this->esopcion = 0;
        $this->orden = 0;
        $this->estado = 0;
    }

    // getters

    public function getProgramaId(){
        return $this->programaid;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getLink(){
        return $this->link;
    }
    public function getPadre(){
        return $this->padre;
    }
    public function getEsOpcion(){
        return $this->esopcion;
    }
    public function getOrden(){
        return $this->orden;
    }
    public function getEstado(){
        return $this->estado;
    }

    //setters

    public function setProgramaId($programaid){
        $this->programaid = $programaid;
    }
    public function setNombre($nombre){
        $this->nombre = '';
        $error = false;
        $nombre = trim($nombre);
        $nombre = filter_var($nombre, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($nombre===FALSE || is_null($nombre) || strlen($nombre)===0) $error = true;
        if(!$error){
            $this->nombre=$nombre;
        }else{
            $this->nombre = $error;
        }
        return $error;
    }
    public function setLink($link){
        $this->link = '';
        $error = false;
        $link = trim($link);
        $link = filter_var($link,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($link===FALSE || is_null($link) || strlen($link)===0) $error = true;
        if(!$error){
            $this->link = $link;
        }
        return $error;
    }
    public function setPadre($padre){
        $this->padre = '';
        $error = false;
        $padre = trim($padre);
        $padre = filter_var($padre,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if($padre===FALSE || is_null($padre))$error = true;
        if(!$error){
            $this->padre = $padre;
        }
        return $error;
    }
    public function setEsOpcion($esopcion){
        $this->esopcion = 0;
        $error = false;
        $esopcion = filter_var($esopcion,FILTER_VALIDATE_INT);
        if($esopcion===FALSE || is_null($esopcion)) $error = true;
        if(!$error){
            $this->esopcion = $esopcion;
        }
        return $error;
    }
    public function setOrden($orden){
        $this->orden = 0;
        $error = false;
        $orden = filter_var($orden,FILTER_VALIDATE_INT);
        if($orden===FALSE || is_null($orden)) $error = true;
        if(!$error){
            $this->orden = $orden;
        }else{
            $this->orden = $error;
        }
        return $error;
    }
    public function setEstado($estado){
        $this->estado = $estado;
    }

    //Metodos

    public function agregar(){
        try{
            $arr = array('exito'=>false,'msg'=>'Hubo un error al agregar');
            $nombre = $this->getNombre();
            $link = $this->getLink();
            $padre = $this->getPadre();
            $esopcion = $this->getEsOpcion();
            $orden = $this->getOrden();
            $estado = 0;
            if(!(is_bool($nombre) || is_bool($orden))){
                $sql = "INSERT INTO programa (nombre, link, padre, esopcion, orden, estado) VALUES (?,?,?,?,?,?)";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('sssiii',$nombre,$link,$padre,$esopcion,$orden,$estado);
                    $stmt->execute();
                    $stmt->close();
                    $id = $mysqli->insert_id;
                    $arr = array('exito'=>true,'msg'=>'','id'=>$id);
                }
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function modificar(){
        try{
            $arr = array('exito'=>false,'msg'=>'Hubo un error al modificar');
            $nombre = $this->getNombre();
            $link = $this->getLink();
            $padre = $this->getPadre();
            $esopcion = $this->getEsOpcion();
            $orden = $this->getOrden();
            $programaid = $this->getProgramaId();
            if(!(is_bool($nombre)||is_bool($orden))){
                $sql = "UPDATE programa SET nombre=?,link=?,padre=?,esopcion=?,orden=? WHERE programaid=?";
                $mysqli = Conexion::abrir();
                $mysqli->set_charset("utf8");
                $stmt = $mysqli->prepare($sql);
                if($stmt!==FALSE){
                    $stmt->bind_param('sssiii',$nombre,$link,$padre,$esopcion,$orden,$programaid);
                    $stmt->execute();
                    $stmt->close();
                    $arr = array('exito'=>true, 'msg'=>'');
                }
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }
    public function eliminar(){
        try{
            $arr = array('exito'=>false,'msg'=>'Hubo un error en la eliminación');
            $programaid = $this->getProgramaId();
            $estado = 99;
            $sql = "UPDATE programa SET estado = ? WHERE programaid = ?";
            $mysqli = Conexion::abrir();
            $mysqli->set_charset("utf8");
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('ii',$estado,$programaid);
                $stmt->execute();
                $stmt->close();
                $arr = array('exito'=>true,'msg'=>'');
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function buscar($busco="N"){
        try{
            $programaid = $busco==="N"?$_SESSION['sprogramaid']:$busco;
            $sql = "SELECT nombre, link, padre, esopcion, orden, estado FROM programa WHERE programaid = ?";
            $mysqli = Conexion::abrir();
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$programaid);
                $stmt->execute();
                $rs = $stmt->get_result();
                while($fila = $rs->fetch_array()){
                    $this->setProgramaId($programaid);
                    $this->setNombre($fila[0]);
                    $this->setLink($fila[1]);
                    $this->setPadre($fila[2]);
                    $this->setEsOpcion($fila[3]);
                    $this->setOrden($fila[4]);
                    $this->setEstado($fila[5]);
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
        $arr = array('exito'=>false,'msg'=>'Error al listar');
        try{
            $arrprogramas = array();
            $sql = "SELECT programaid, nombre, link, padre, esopcion, orden, estado FROM programa WHERE estado = 0 ORDER BY orden";
            $mysqli = Conexion::abrir();
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $arrprogramas = $stmt->get_result();
                $stmt->close();
                $programas = $arrprogramas->fetch_all(MYSQLI_ASSOC);
                $arr = array('exito'=>true,'msg'=>'',$programas);
            }
        }catch(Exception $e){
            $arr['msg'] = $e->getMessage();
        }
        return $arr;
    }

    public function listarI(){
        try{
            $arrprogramas = array();
            $perfilid = $_SESSION["sperfilid"];
            $sql = "SELECT programaid, nombre FROM programa WHERE estado = 0 AND programaid NOT IN (SELECT programaid FROM perfilprograma WHERE perfilid=?)";
            $mysqli = Conexion::abrir();
            $stmt = $mysqli->prepare($sql);
            if($stmt!==FALSE){
                $stmt->bind_param('i',$perfilid);
                $stmt->execute();
                $arrprogramas = $stmt->get_result();
                $stmt->close();
                return $arrprogramas;
            }else{
                return true;
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
}
?>