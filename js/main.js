


$(function(){
	if($('#idlocalidad')){cargarLocalidad()};
    if($('#cmbDoc')){cargarTipodoc()};
	if($('cmbIva')){cargarCondiva()};
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