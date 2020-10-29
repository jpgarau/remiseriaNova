<?php

include_once '../modelo/tipoDoc.php';

class ApiTipoDoc{

    function getAll(){
        $tipoDoc = new TipoDoc();
        $tiposDoc = array();

        $res = $tipoDoc->listaTipoDoc();

        if(($res->num_rows)>0){
            while($fila=$res->fetch_assoc()){
                $elemento = array(
                    'idTipoDoc'=>$fila['idTipoDoc'],
                    'descripcion'=>$fila['descripcion']
                );
                $tiposDoc[]=$elemento;
            }
            return json_encode($tiposDoc);
        }else{
            return json_encode(array('mensaje'=>'No hay elementos registrados'));
        }
    }
}