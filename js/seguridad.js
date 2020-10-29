$(document).ready(function () {
    $("#usuarios").click(function (e) { 
        e.preventDefault();
        $("#contenido").load("vista/admusuarios.php");
    });
    $("#perfiles").click(function (e) { 
        e.preventDefault();
        $("#contenido").load("vista/admperfil.php");
    });
    $("#programas").click(function (e) { 
        e.preventDefault();
        $("#contenido").load("vista/admprogramas.php");
    });
});