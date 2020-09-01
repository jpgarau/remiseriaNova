<?php
// include_once "../settings.php";
$dir = is_dir('public')?"":"../";
include_once($dir.'modelo/validar.php');
include_once($dir.'vista/header.php');
if(isset($_SESSION['usuario'])){
?>

<body>
    <?php include_once($dir.'vista/menu.php'); ?>
    <div id="contenedor" class="menu-principal">

    </div>
    <!--.container-->
    <?php require_once($dir.'vista/footer.php'); ?>

</body>

</html>

<?php }?>