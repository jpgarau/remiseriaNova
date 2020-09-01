<?php 
include_once("vista/header.php");
?>

<body class="text-center site-viaje ">
        <?php if(isset($_SESSION['usuario'])){
            include_once('vista/menu2.php');
        }?>    <header>
        <h1 style="font-family: 'Russo One', sans-serif;">Gestión de Viajes</h1>
    </header>
    <main class="container marco-viaje">

        <div class="origen">
            <p class="texto-direccion" id="origen">Origen:</p>
        </div>
        <div class="destino">
            <p class="texto-direccion" id="destino">Destino:</p>
        </div>
        <div class="libre">
            <button class="btnlibre" id="btnlibre">LIBRE</button>
        </div>
        <div>
            <form method="post">
                <a href="index.php" class="btn btn-primary btn-lg mt-2 rounded-circle" id="regresar" name="regresar" title="Regresar"><i class="fas fa-undo-alt"></i></a>
            </form>
        </div>
    </main>
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous">
    </script>
    <script src="ajax/consultar_viaje.js"></script>
</body>

</html>