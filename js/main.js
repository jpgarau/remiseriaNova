
$(function(){
	if($('#idlocalidad').length>0){cargarLocalidad()}
    if($('#cmbDoc').length>0){cargarTipodoc()}
	if($('#cmbIva').length>0){cargarCondiva()}
	if($("#cmbTelegram").length>0) {cargarTelegram()}
	if($("#actTelegram").length>0){
		$("#actTelegram").on('click', function (){
			let telid = $("#cmbTelegram").val();
			$("#actTelegram i").addClass("fa-spin");
			actualizarTablaTelegram().then(()=>{ 
				cargarTelegram().then(()=>{
					$("#actTelegram i").removeClass("fa-spin");
					$("#cmbTelegram").val(telid);
				});
			});
		});
	}
	if(typeof relojConsola !== 'undefined'){
		clearInterval(relojConsola)};
});

// Localidades
function cargarLocalidad(){
	$.ajax({
		url:'ajax/localidades.php',
		type: 'GET',
		datatype: 'JSON',
		data: ({}),
		success: insertarLocalidad,
		error:errorAjax
	})
}

function insertarLocalidad(respuesta,e,ce){
	var listaLocalidades=$("#idlocalidad");
	
	if(ce.status != 200){
		errorAjax();
	}

	var respuesta = JSON.parse(respuesta);
    listaLocalidades.html('');
	
	for(i=0;i<respuesta.length;i++){
        var localidad = $("<option>");
        localidad.val(respuesta[i].idlocalidad);
		localidad.text(respuesta[i].localidad);
        listaLocalidades.append(localidad);
    }
}

//Tipos Documentos
function cargarTipodoc(){
	$.ajax({
		url:'ajax/tiposDoc.php',
		type: 'GET',
		datatype: 'JSON',
		data: ({}),
		success: insertarTipodoc,
		error:errorAjax
	})
}

function insertarTipodoc(respuesta,e,ce){
	if(ce.status!= 200){
		errorAjax();
	}
	var listaTipodoc=$("#cmbDoc");
	
    var respuesta = JSON.parse(respuesta);
	listaTipodoc.html('');
	for(i=0;i<respuesta.length;i++){
        var tipodoc = $("<option>");
        tipodoc.val(respuesta[i].idTipoDoc);
		tipodoc.html(respuesta[i].descripcion);
        listaTipodoc.append(tipodoc);
    }
}

// Condiciones de Iva

function cargarCondiva(){
	$.ajax({
		url:'ajax/condIvas.php',
		type: 'GET',
		datatype: 'JSON',
		data: ({}),
		success: insertarCondiva,
		error:errorAjax
	})
}

function insertarCondiva(respuesta,e,ce){
	var listaCondiva=$("#cmbIva");
	if(ce.status != 200){
		errorAjax();
	}

    var respuesta = JSON.parse(respuesta);
	listaCondiva.html("");
	for(i=0;i<respuesta.length;i++){
		var condiva = $("<option>");
		condiva.val(respuesta[i].idcondiva);
		condiva.html(respuesta[i].desccondiva);
        listaCondiva.append(condiva);
    }
}

function errorAjax() {
	console.log("Hubo un error en el Ajax");
}


function cargarTelegram(){
	return new Promise((exito)=>{
		$("#cmbTelegram").html('');
		$("#cmbTelegram").append('<option value=0>Sin Telegram</option>');
		$.ajax({
			type: 'POST',
			url: 'scripts/apitelegram.php',
			data: {param:1},
			dataType: 'json',
			success: function(response){
				if(response.exito){
					if(response.encontrados>0){
						response[0].forEach((user)=>{
							$("#cmbTelegram").append('<option value='+user.telid+'>'+user.last_name+', '+user.first_name+'</option>');
							exito(response.exito);
						})
					}
				}
			},
			error: function(response){
				console.error(response);
				exito(false);
			} 
		});
	});
}

function actualizarTablaTelegram(){
	return new Promise((exito)=>{
		$.ajax({
			type: 'POST',
			url: 'scripts/apitelegram.php',
			data: {param:2},
			dataType: 'json',
			success: function(response){
				if(response.exito){
					exito(response.exito);
				}
				else{
					exito(response.exito);
				}
			},
			error: function(response){
				exito(false);
			}
		});
	});
}

function getFechaHora(diahr=new Date()){
	var year = diahr.getFullYear().toString();
	var mes = (diahr.getMonth()+1).toString().padStart(2,0);
	var dia = diahr.getDate().toString().padStart(2,0);
	var fecha = year+"-"+mes+"-"+dia;
	var fecha2 = dia+"/"+mes+"/"+year;
	var hora = diahr.getHours().toString().padStart(2,0);
	var min = diahr.getMinutes().toString().padStart(2,0);
	var seg = diahr.getSeconds().toString().padStart(2,0);
	var time = hora+":"+min+":"+seg;
	return {"fecha":fecha,"hora":time, "fecha2":fecha2};
}

function generarInformePDF(servicios, movil, chofer, fecha) {
	let total = 0.0,
		ctaCte = 0.0,
		recaudado = 0.0,
		viajes = 0;
	var informe = new jsPDF({ orientation: "p", unit: "mm", format: "a4" });

	informe.setFontSize(10);
	informe.text(160, 5, "Fecha: " + fecha.fecha2);

	informe.setFontSize(18);
	informe.text(80, 10, "REMISERIA NOVA");

	informe.setFontSize(14);
	informe.setFontType("bold");
	informe.text(75, 20, "INFORME DE TURNO");

	informe.setFontSize(10);
	informe.text(20, 30, "Movil: " + movil);
	informe.text(40, 30, "Chofer: " + chofer);

	informe.roundedRect(10, 35, 190, 7, 1, 1);

	informe.text(12, 40, "Salió");
	informe.text(23, 40, "Libre");
	informe.text(35, 40, "Origen");
	informe.text(92, 40, "Destino");
	informe.text(148, 40, "CC");
	informe.text(163, 40, "Importe");
	informe.text(190, 40, "Total");

	informe.setFontType("normal");
	let linea = 46;

	servicios.forEach((servicio) => {
		if (servicio.idCliente > 0) {
			ctaCte += servicio.importe == null ? 0.0 : parseFloat(servicio.importe);
			informe.text(148, linea, "CC");
		}
		total += servicio.importe == null ? 0.0 : parseFloat(servicio.importe);
		informe.text(12, linea, servicio.hora.substring(0, 5));
		informe.text(23, linea, servicio.hora.substring(0, 5));
		let origen = informe.splitTextToSize(servicio.origen, 55);
		let destino = informe.splitTextToSize(
			servicio.destino == null ? "" : servicio.destino,
			55
		);
		informe.text(35, linea, origen[0]);
		informe.text(92, linea, destino[0]);

		let importe =
			servicio.importe == null ? "0.00" : servicio.importe.toString();
		informe.text(176, linea, "$ " + importe, "right");
		informe.text(199, linea, "$ " + total.toFixed(2).toString(), "right");
		viajes++;
		if (linea > 285) {
			informe.addPage();
			linea = 10;
		}
		linea += 5;
	});

	(recaudado = total - ctaCte), informe.roundedRect(140, linea, 60, 17, 1, 1);
	linea += 5;
	informe.text(142, linea, "Recaudado:");
	informe.text(199, linea, "$ " + recaudado.toFixed(2).toString(), "right");
	linea += 5;
	informe.text(142, linea, "Cuenta Corriente:");
	informe.text(199, linea, "$ " + ctaCte.toFixed(2).toString(), "right");
	linea += 5;
	informe.setFontType("bold");
	informe.text(142, linea, "Totales: " + viajes.toString() + " (viaje/s)");
	informe.text(199, linea, "$ " + total.toFixed(2).toString(), "right");
	informe.save(fecha.fecha + " -" + " turno movil " + movil + ".pdf");
}