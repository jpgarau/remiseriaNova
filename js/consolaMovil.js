var idServicio = 0;
var movil = 0;
var chofer = '';
var locaciones = {1:'Base',2:'Toay', 3:'Santa Rosa'};
$(function () {
    establecerServicio();

    // $("#actualizarDestino").on("click", async function(){
    //     if( await verificarServicio()){
    //         actualizarDestino();
    //     }else{
    //         noHayServicio();
    //     }
    // });
    $("#libre_base").on("click", async function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        if( await verificarServicio()){
            libre(1);
        }else{
            noHayServicio();
        }
    });
    $("#libre_toay").on("click", async function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        if( await verificarServicio()){
            libre(2);
        }else{
            noHayServicio();
        }
    });
    $("#libre_sRosa").on("click", async function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        if( await verificarServicio()){
            libre(3);
        }else{
            noHayServicio();
        }
    });
    $("#fuera_servicio").on("click", async function(){
        $(".btn").removeClass("active");
        $(this).addClass("active");
        if( await verificarServicio()){
            fueraServicio();
        }else{
            noHayServicio();
        }
    });
    $("#resumen_turno").on("click", async function(){
        if( await verificarServicio()){
            resumenTurno();
        }else{
            noHayServicio();
        }
    })
});

// function actualizarDestino(){
//     let idChofer = $("#actualizarDestino").val();
//     $("#origenMovil").html("");
//     $("#origenMovil").addClass("text-info");
//     $("#origenMovil").append("Buscando...<div class='spinner-border' role='status' aria-hidden='true'></div>");
//     $.ajax({
//         type:"POST",
//         url:"scripts/apiviajes.php",
//         data:{param:8, idChofer},
//         dataType: 'json',
//         success: function(response){
//             if(response.exito){
//                 if(response.encontrados>0){
//                     $("#origenMovil").html("");
//                     $("#origenMovil").removeClass("text-info");
//                     $("#origenMovil").append(response[0].origen);
//                     $(".btn").removeClass("active");
//                 }else{
//                     $("#origenMovil").html("");
//                     $("#origenMovil").removeClass("text-info");
//                     $("#origenMovil").append("No hay viajes disponibles");
//                     $(".btn").removeClass("active");
//                 }
//             }else{
//                 console.error(response.msg);
//             }
//         },
//         error: function(response){
//             console.error(response);
//         }
//     });
// }

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

function resumenTurno(){
    let fecha = getFechaHora();
    $.ajax({
		type: "POST",
		url: "scripts/apiviajes.php",
		data: { param: 1, idServicio },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
                if(response.encontrados>0){
                    generarInformePDF(response[0], movil, chofer, fecha);
                }else{
                    alertify.error('No se encontraron viajes para este servicio.');
                }
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function establecerServicio() {
    let idChofer = $("#resumen_turno").val();
    $.ajax({
        type:"POST",
        url:"scripts/apiservicios.php",
        data:{param:4, idChofer},
        dataType: "json",
        success: function(response){
            if(response.exito){
                if(response.encontrados>0){
                    idServicio = response[0].idServicio;
                    movil = response[0].idVehiculo;
                    chofer = response[0].ayn;
                }else{
                    noHayServicio();
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

function verificarServicio(){
    return new Promise((exito)=>{
        $.ajax({
            type:'POST',
            url:'scripts/apiservicios.php',
            data:{param:5, idServicio},
            dataType: 'json',
            success: function(response){
                exito(response.exito);
            },
            error: function(response){
                console.error(response);
                exito(false);
            }
        });
    });
}

function noHayServicio(){
    alertify.error("No se encontro servicio Activo");
    // $("#actualizarDestino").prop("disabled", true);
    $("#libre_base").prop("disabled", true);
    $("#libre_toay").prop("disabled", true);
    $("#libre_sRosa").prop("disabled", true);
    $("#fuera_servicio").prop("disabled", true);
    $("#resumen_turno").prop("disabled", true);
    $("#errorServicio").removeClass("d-none");
}