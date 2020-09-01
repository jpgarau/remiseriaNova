<?php 
include_once("../api/apiCondIva.php");

$nuevoCI = new ApiCondIva();

$arrCI = [];

$arrCI = $nuevoCI->getAll();

echo $arrCI;

?>