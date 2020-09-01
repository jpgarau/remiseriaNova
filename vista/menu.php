<nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand text-warning text-uppercase" href="/remiseria" style="font-family: 'Russo One', sans-serif;">Gestion Remises</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarGestion" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarGestion">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="">Consola Movil</a>
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
            <p class="mr-sm-2 my-auto text-white font-weight-bold" style="font-family: 'Play', sans-serif;"><i class="fas fa-user"></i> <?php echo $_SESSION['usuario']; ?></p>
            <button class="btn btn-info btn-sm my-2 my-sm-0" name="cerrars" id="cerrars" title="Cerrar Session"><i class="fas fa-door-open"></i></button>
        </form>
    </div>
</nav>