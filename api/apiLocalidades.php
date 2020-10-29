<?php

include_once '../modelo/localidad.php';

class ApiLocalidades{

    function getAll(){
        $localidad = new Localidad();
        $localidades = array();

        $res = $localidad->listaLocalidades();

        if(($res->num_rows)>0){
            while($fila=$res->fetch_assoc()){
                $elemento = array(
                    'idlocalidad'=>$fila['idlocalidad'],
                    'localidad'=>$fila['localidad']
                );
                $localidades[]=$elemento;
                // array_push($localidades,$elemento);
            }
            return json_encode($localidades);
        }else{
            return json_encode(array('mensaje'=>'No hay elementos registrados'));
        }
    }
}