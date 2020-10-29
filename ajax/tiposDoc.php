<?php 
include_once("../api/apiTipoDoc.php");

$nuevoTD = new ApiTipoDoc();

$arrTD = [];

$arrTD = $nuevoTD->getAll();

echo $arrTD;

?>