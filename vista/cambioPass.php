<?php
$dir = is_dir('modelo') ? "" : "../";
include_once($dir . 'modelo/validar.php');
include_once($dir . 'controlador/usuarioc.php');
if (isset($_POST['btnCambiarPass'])) {
    $clave = isset($_POST['clave'])?$_POST['clave']:'';
    $password = isset($_POST['password'])?$_POST['password']:'';
    if(strlen($clave)>0 && strlen($password)>0){
        if($clave===$password){
            $nuevoU = new UsuarioC();
            $respuesta = $nuevoU->cambiarClave($_SESSION['userProfile']['usuarioid'], $clave);
            if ($respuesta['exito']) {
                unset($_SESSION['usuario']);
                unset($_SESSION['userProfile']);
                echo "<div class='alert alert-success mb-0'>Cambio realizado con exito. Redirigiendo<i class='fas fa-circle-notch fa-spin'></i></div>";
                header('Location: /remiseria');
            } else {
                echo "<div class='alert alert-danger mb-0'>Error al conectarse con la base de datos. " . $respuesta['msg'] . "</div>";
            }
        }else{
            echo "<div class='alert alert-danger mb-0'>No son iguales</div>";
        }
    }else{
        echo "<div class='alert alert-danger mb-0'>No pueden estar vacios</div>";
    }
}
require_once('header.php');
?>
<?php if (isset($_SESSION['usuario']) && $_SESSION['userProfile']['estado']==50) { ?>
    <div class="fluid-container text-center site-login">
        <header style="background-color: rgba(0,0,0,0.5)">
            <h1 class="text-warning" style="font-family: 'Russo One', sans-serif;">Sistema de Remises</h1>
        </header>
        <main class="container">
            <form method="POST" id="login">
                <fieldset class="form-login">
                    <h3 class="text-dark">Cambio de Contraseña</h3>
                    <div class="center-block">
                        <label for="clave" class="text-dark">Nueva Contraseña</label>
                        <input type="password" name="clave" class="form-control" id="clave" required title="Ingrese la nueva contraseña.">
                    </div>

                    <div class="center-block">
                        <label for="password" class="text-dark">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required title="Reingrese la contraseña.">
                    </div>
                    <div class="center-block">
                        <div>
                            <button class="btn btn-primary btn-lg mt-2" type="submit" id="btnCambiarPass" name="btnCambiarPass">Cambiar</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </main>
    </div>
<?php } ?>
<?php
require_once('footer.php');
?>
</body>

</html>