var idServicio = 0;
var locaciones = {1:'Base',2:'Toay', 3:'Santa Rosa'};
$(function () {
    establecerServicio();

    $("#actualizarDestino").on("click", function(){
        actualizarDestino();
    });
    $("#libre_base").on("click", function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        libre(1);
    });
    $("#libre_toay").on("click", function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        libre(2);
    });
    $("#libre_sRosa").on("click", function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        libre(3);
    });
    $("#fuera_servicio").on("click", function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        fueraServicio();
    });

});


function actualizarDestino(){
    let idChofer = $("#actualizarDestino").val();
    $("#origenMovil").html("");
    $("#origenMovil").addClass("text-info");
    $("#origenMovil").append("Cargando...<div class='spinner-border' role='status' aria-hidden='true'></div>");
    $.ajax({
        type:"POST",
        url:"scripts/apiviajes.php",
        data:{param:8, idChofer},
        dataType: 'json',
        success: function(response){
            if(response.exito){
                if(response.encontrados>0){
                    $("#origenMovil").html("");
                    $("#origenMovil").removeClass("text-info");
                    $("#origenMovil").append(response[0].origen);
                    $(".btn").removeClass("active");
                }else{
                    $("#origenMovil").html("");
                    $("#origenMovil").removeClass("text-info");
                    $("#origenMovil").append("No hay viajes disponibles");
                }
            }else{
                console.error(response.msg);
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}

function libre(pila){
    $.ajax({
        type:"POST",
        url:"scripts/apipila.php",
        data:{param:3, idServicio, pila},
        dataType: 'json',
        success: function(response){
            if(response.exito){
                alertify.success('Libre en ' + locaciones[pila]);
            }else{
                console.error(response.msg);
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}

function fueraServicio(){
    $.ajax({
        type:"POST",
        url:"scripts/apipila.php",
        data:{param:2, idServicio},
        dataType: 'json',
        success: function(response){
            if(response.exito){
                alertify.warning('Fuera de Servicio');
            }else{
                console.error(response.msg);
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}

function establecerServicio() {
    let idChofer = $("#actualizarDestino").val();
    $.ajax({
        type:"POST",
        url:"scripts/apiservicios.php",
        data:{param:4, idChofer},
        dataType: "json",
        success: function(response){
            if(response.exito){
                if(response.encontrados>0){
                    idServicio = response[0].idServicio;
                }else{
                    alertify.error("No se encontro servicio Activo");
                    $("#actualizarDestino").prop("disabled", true);
                    $("#libre_base").prop("disabled", true);
                    $("#libre_toay").prop("disabled", true);
                    $("#libre_sRosa").prop("disabled", true);
                    $("#fuera_servicio").prop("disabled", true);
                    $("#errorServicio").removeClass("d-none");

                }
            }else{
                console.error(response.msg);
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}