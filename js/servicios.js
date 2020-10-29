var vehiculo = { marca: "", patente: "", idVehiculos: 0 };
var chofer = { ayn: "", idChofer: 0 };
var servicio = { marca: "", patente: "", idVehiculos: 0, ayn: "", idChofer: 0, idServicio:0 };

$(document).ready(function () {
	if ($("#tabla_vehiculos")) listarVehiculos();
	if ($("#tabla_choferes")) listarChoferes();
	

	$("#tabla_vehiculos tbody").on("click", "tr", function () {
		var tr = $(this);
		$("#tabla_vehiculos tbody tr").removeClass("table-active");
		$(this).addClass("table-active");
		vehiculo.idVehiculos = tr[0].childNodes[2].childNodes[0].value;
		vehiculo.marca = tr[0].childNodes[0].innerText;
		vehiculo.patente = tr[0].childNodes[1].innerText;
	});

	$("#tabla_choferes tbody").on("click", "tr", function () {
		var tr = $(this);
		$("#tabla_choferes tbody tr").removeClass("table-active");
		$(this).addClass("table-active");
		chofer.idChofer = tr[0].childNodes[1].childNodes[0].value;
		chofer.ayn = tr[0].childNodes[0].innerText;
	});

	$("#tabla_servicios tbody").on("click", "tr", function () {
		var tr = $(this);
		$("#tabla_servicios tbody tr").removeClass("table-active");
		$(this).addClass("table-active");
		servicio.idChofer = tr[0].childNodes[4].childNodes[0].value;
		servicio.idVehiculos = tr[0].childNodes[3].childNodes[0].value;
		servicio.marca = tr[0].childNodes[0].innerText;
		servicio.patente = tr[0].childNodes[1].innerText;
		servicio.ayn = tr[0].childNodes[2].innerText;
		servicio.idServicio = tr[0].childNodes[5].childNodes[0].value;
	});

	$("#iniciarServicio").on("click", function () {
		if (chofer.idChofer !== 0 && vehiculo.idVehiculos !== 0) {
			iniciarServicio();
		}
	});

	$("#terminarServicio").on("click", function () {
		if (servicio.idChofer !== 0 && servicio.idVehiculos !== 0 && servicio.idServicio !== 0)
			terminarServicio();
	});
});

function listarVehiculos() {
	$.ajax({
		type: "POST",
		url: "scripts/apivehiculos.php",
		data: { param: 6 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				llenarTablaVehiculos(response[0]);
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response.msg);
		},
	});
}

function llenarTablaVehiculos(vehiculos) {
	vehiculos.forEach((vehiculo) => {
		insertarVehiculo(vehiculo);
	});
}

function insertarVehiculo(vehiculo) {
	$("#tabla_vehiculos tbody").append(
		"<tr>" +
			"<td>" +
			vehiculo.marca +
			"</td>" +
			"<td>" +
			vehiculo.patente +
			"</td>" +
			"<td>" +
			"<input type='hidden' value='" +
			vehiculo.idVehiculos +
			"'>" +
			"</td>" +
			"</tr>"
	);
}

function listarChoferes() {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 1 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				llenarTablaChoferes(response[0]);
				if ($("#tabla_vehiculos") && $("#tabla_choferes") && $("#tabla_servicios")) listarServicios();
			} else {
				console.log(response.txt);
			}
		},
		error: function (response) {
			console.log(response.msg);
		},
	});
}

function llenarTablaChoferes(choferes) {
	choferes.forEach((chofer) => {
		insertarChofer(chofer);
	});
}

function insertarChofer(chofer) {
	$("#tabla_choferes tbody").append(
		"<tr>" +
			"<td>" +
			chofer.ayn +
			"</td>" +
			"<td>" +
			"<input type='hidden' value='" +
			chofer.idChofer +
			"'>" +
			"</td>" +
			"</tr>"
	);
}
function listarServicios() {
	$.ajax({
		type: "POST",
		url: "scripts/apiservicios.php",
		data: { param: 1 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				llenarTablaServicios(response[0]);
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response.msg);
		},
	});
}

function llenarTablaServicios(servicios) {
	servicios.forEach((servicio) => {
		insertarServicio(servicio);
		$("#tabla_vehiculos tbody input[value='" + servicio.idVehiculos + "']")[0].parentNode.parentNode.remove();
		$("#tabla_choferes tbody input[value='" + servicio.idChofer + "']")[0].parentNode.parentNode.remove();
	});
}

function insertarServicio(servicio) {
	$("#tabla_servicios tbody").append(
		"<tr>" +
			"<td>" +
			servicio.marca +
			"</td>" +
			"<td>" +
			servicio.patente +
			"</td>" +
			"<td>" +
			servicio.ayn +
			"</td>" +
			"<td>" +
			"<input type='hidden' value='" +
			servicio.idVehiculos +
			"'>" +
			"</td>" +
			"<td>" +
			"<input type='hidden' value='" +
			servicio.idChofer +
			"'>" +
			"</td>" +
			"<td>" +
			"<input type='hidden' value='" +
			servicio.idServicio +
			"'>" +
			"</td>" +
			"</tr>"
	);
}

function iniciarServicio() {
	var otiempo = getFechaHora();
	$.ajax({
		type: "POST",
		url: "scripts/apiservicios.php",
		data: {
			param: 2,
			idVehiculo: vehiculo.idVehiculos,
			idChofer: chofer.idChofer,
			fechaEnt: otiempo.fecha,
			horaEnt: otiempo.hora,
		},
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				insertarServicio({
					marca: vehiculo.marca,
					patente: vehiculo.patente,
					ayn: chofer.ayn,
					idVehiculos: vehiculo.idVehiculos,
					idChofer: chofer.idChofer,
					idServicio: response.idServicio,
				});
				$("#tabla_vehiculos tbody tr.table-active").remove();
				$("#tabla_choferes tbody tr.table-active").remove();
				vehiculo = { marca: "", patente: "", idVehiculos: 0 };
				chofer = { ayn: "", idChofer: 0 };
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function terminarServicio() {
	var otiempo = getFechaHora();
	var idServicio = servicio.idServicio;
	$.ajax({
		type:"POST",
		url:"scripts/apiservicios.php",
		data:{param:3,
				idServicio:idServicio,
				fechaSal: otiempo.fecha,
				horaSal: otiempo.hora
			},
		dataType:"json",
		success: function(response){
			if(response.exito){
				insertarVehiculo(servicio);
				insertarChofer(servicio);
				$("#tabla_servicios tbody tr.table-active").remove();
				servicio = { marca: "", patente: "", idVehiculos: 0, ayn: "", idChofer: 0, idServicio:0 };
			}else{
				console.log(response.msg);
			}
		},
		error: function(response){
			console.log(response);
		}
	});

}
