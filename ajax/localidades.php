<?php 
include_once("../api/apiLocalidades.php");

$nuevoL = new ApiLocalidades();

$arrL = [];

$arrL = $nuevoL->getAll();

echo $arrL;

?>