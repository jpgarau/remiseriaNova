<?php
$dir = is_dir('modelo') ? "" : "../";
include_once($dir . 'modelo/validar.php');
include_once($dir . 'controlador/usuarioc.php');
include_once($dir . 'modelo/telegram.php');
if (isset($_POST['btnlogin'])) {
    $nuevoU = new UsuarioC();
    $respuesta = $nuevoU->verificar($_POST['usuario'], $_POST['password']);
    if ($respuesta['exito']) {
        if ($respuesta["encontrado"]) {
            $_SESSION['usuario'] = $respuesta[0]['apellido'] . ', ' . $respuesta[0]['nombre'];
            $_SESSION['userProfile'] = $respuesta[0];
            $oTelegram = new Telegram();
            $oTelegram->actualizarTabla();
            header('Location: /remiseria');
        } else {
            echo "<div class='alert alert-danger mb-0'>Usuario o Contraseña Incorrectos</div>";
        }
    } else {
        echo "<div class='alert alert-danger mb-0'>Error al conectarse con la base de datos. " . $respuesta['msg'] . "</div>";
    }
}
require_once('header.php');
?>
<?php if (!isset($_SESSION['usuario'])) { ?>
    <div class="fluid-container text-center site-login">
        <header style="background-color: rgba(0,0,0,0.5)">
            <h1 class="text-warning" style="font-family: 'Russo One', sans-serif;">Sistema de Remises</h1>
        </header>
        <main class="container">
            <form method="POST" id="login">
                <fieldset class="form-login">
                    <h3 class="text-dark">Ingreso</h3>
                    <div class="center-block">
                        <label for="usuario" class="text-dark">Usuario</label>
                        <input type="text" name="usuario" class="form-control" id="usuario" required title="Ingrese el nombre de Usuario.">
                    </div>

                    <div class="center-block">
                        <label for="password" class="text-dark">Password</label>
                        <input type="password" name="password" class="form-control" id="password" required title="Ingrese la contraseña.">
                    </div>
                    <div class="center-block">
                        <div>
                            <button class="btn btn-primary btn-lg mt-2" type="submit" id="btnlogin" name="btnlogin">Ingresar</button>
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