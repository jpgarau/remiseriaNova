var idServicio;
$(function () {
    establecerServicio();
    $("#actualizarDestino").on("click", function(){
        actualizarDestino();
    });
    $("#libre_base").on("click", function(){
        libreBase();
    });
    $("#libre_toay").on("click", function(){
        console.log("libre en Toay");
    });
    $("#libre_sRosa").on("click", function(){
        console.log("libre en Santa Rosa");
    });
    $("#fuera_servicio").on("click", function(){
        console.log("Fuera de Servicio");
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

function libreBase(){
    $.ajax({
        type:"POST",
        url:"scripts/apipila.php",
        data:{param:3, idServicio},
        dataType: 'json',
        success: function(response){
            if(response.exito){
                if(response.encontrados>0){
                    $("#origenMovil").html("");
                    $("#origenMovil").removeClass("text-info");
                    $("#origenMovil").append(response[0].origen);
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

function establecerServicio() {
    let idChofer = $("#actualizarDestino").val();
    $.ajax({
        type:"POST",
        url:"scripts/apiservicios.php",
        data:{},
        dataType: "json",
        success: function(response){
            if(response.exito){

            }else{
                console.error(response.msg);
            }
        },
        error: function(response){
            console.error(response);
        }
    });
}