<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-warning text-uppercase" href="/remiseria" style="font-family: 'Russo One', sans-serif;">Gestion Remises</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarGestion" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarGestion">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="" id="consolaMovil">Consola Movil</a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" id="menuDrop" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    ABM
                </a>
                <div class="dropdown-menu" aria-labelledby="menuDrop">
                <a class="dropdown-item" href="" id="vehiculos">Vehículos</a>
                <a class="dropdown-item" href="" id="propietarios">Propietarios</a>
                <a class="dropdown-item" href="" id="clientes">Clientes</a>
                <a class="dropdown-item" href="" id="choferes">Choferes</a>

                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="" id="informes">Informes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="" id="consola">Consola</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="" id="servicios">Servicios</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="" id="ctacte">Cta. Cte.</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="post">
            <li class="nav-item dropdown list-group">
                <a href="#" class="nav-link dropdown-toggle text-white font-weight-bold" id="menuUsuario" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-left dropdown-menu-sm-right" aria-labelledby="menuUsuario">
                <a class="dropdown-item" href="" id="cambioPass"><i class="fas fa-key"></i> Cambiar Contraseña</a>
                <a class="dropdown-item" href="" id="config"><i class="fas fa-cogs"></i> Configuración</a>
                <div class="dropdown-divider"></div>
                <button class="dropdown-item" href="" name="cerrars" id="cerrars"><i class="fas fa-door-open"></i> Cerrar Session</button>
                </div>
            </li>
        </form>
    </div>
</nav>