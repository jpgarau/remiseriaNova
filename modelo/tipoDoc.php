<?php

include_once '../modelo/conexion.php';

class TipoDoc{

    function listaTipoDoc(){
        try {
            $mysql = Conexion::abrir();
            $mysql->set_charset('utf8');
            $sql = 'SELECT * FROM tipodoc';
            $stmt = $mysql->prepare($sql);
            if($stmt!==FALSE){
                $stmt->execute();
                $rs = $stmt->get_result();
                $stmt->close();
                return $rs;
            }
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}