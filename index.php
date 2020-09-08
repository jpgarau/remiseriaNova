<?php
// include_once "../settings.php";
$dir = is_dir('modelo')?"":"../";
include_once($dir.'modelo/validar.php');
include_once($dir.'vista/header.php');
if(isset($_SESSION['usuario'])){
?>

<body>
    <div id="contenedor" class="menu-principal">
        <?php include_once($dir.'vista/menu.php'); ?>
        <div id="contenedor2" >
            </div>
            <!--.container-->
    </div>
    <?php require_once($dir.'vista/footer.php'); ?>

</body>

</html>

<?php }?>