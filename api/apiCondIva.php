<?php

include_once '../modelo/condIva.php';

class ApiCondIva{

    function getAll(){
        $condIva = new CondIva();
        $condIvas = array();

        $res = $condIva->listaCondIva();

        if(($res->num_rows)>0){
            while($fila=$res->fetch_assoc()){
                $elemento = array(
                    'idcondiva'=>$fila['idcondiva'],
                    'desccondiva'=>$fila['desccondiva']
                );
                $condIvas[]=$elemento;
            }
            return json_encode($condIvas);
        }else{
            return json_encode(array('mensaje'=>'No hay elementos registrados'));
        }
    }
}