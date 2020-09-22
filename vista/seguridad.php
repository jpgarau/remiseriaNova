<?php
$dir = is_dir('modelo')?'':'../';
include_once $dir.'modelo/validar.php';
?>
<div class="contenedor-seguridad">
  <button name="usuarios" id="usuarios" class="btn btn-outline-dark m-2">Usuarios</button>
  <button name="perfiles" id="perfiles" class="btn btn-outline-dark m-2">Perfiles</button>
  <?php 
    if($_SESSION['userProfile']['perfilid']===1){
      echo '<button name="programas" id="programas" class="btn btn-outline-dark m-2">Programas</button>';
    }
  ?>
  
  <div id="contenido" class="container"></div>
</div>
<script src="js/seguridad.js"></script>