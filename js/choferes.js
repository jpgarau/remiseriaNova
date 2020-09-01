var dniChoferes = new Array();
var cuitChoferes = new Array();
var dniPersonas = new Array();
var dniActual;
var cuitActual;
var idChoferActual=0;

$(document).ready(function () {
	cargarCuitChoferes();
	cargarDniChoferes();
	cargarDniPersonas();
	listar();
	// $("#adm_choferes").modal({backdrop:'static', show:false});


	//Modal Agregar Chofer
	$("#addchofer").on("click", function () {
		cuitActual = null;
		$("#adm_choferes .modal-title").text("Agregar Chofer");
		$("#adm_choferes .modal-body input#apynom").val("");

		$("#adm_choferes .modal-body input#direccion").val("");
		$("#adm_choferes .modal-body #idlocalidad").val(
			$("#idlocalidad option").val()
		);
		$("#adm_choferes .modal-body input#telefono").val("");
		$("#adm_choferes .modal-body #cmbDoc").val($("#cmbDoc option").val());
		$("#adm_choferes .modal-body input#numdni").val("");
		$("#adm_choferes .modal-body input#numdni").prop("disabled", false);
		$("#adm_choferes .modal-body input#email").val("");
		$("#adm_choferes .modal-body input#fecnac").val("");
		$("#adm_choferes .modal-body #cmbIva").val($("#cmbIva option").val());
		$("#adm_choferes .modal-body input#cuit").val(null);
		$("#adm_choferes .modal-body input#comis").val("");
		$("#adm_choferes .modal-body input#licencia").val("");
		$("#adm_choferes .modal-body #observaciones").val("");
		$("#adm_choferes .modal-body input#idchofer").val("Agregar");
		$("#adm_choferes").modal("show");
	});

	// Listener cambios en el combo select de IVA
	$("#cmbIva").on("change", function () {
		if ($(this).val() != 1) {
			$("#cmbDoc").val(4);
			$("#cuit").prop({ minlength: "11", required: "true", maxlength: "13" });
		} else {
			$("#cmbDoc").val(1);
			$("#cuit").prop("required", false);
			$("#cuit").removeClass("is-valid is-invalid");
		}
	});

	// Listener campo CUIT
	$("#cuit").on("input", function () {
		validaCuit($(this).val());
	});

	//Listener campo DNI
	$("#numdni").on("input", function () {
		validarNumdni($(this).val());
	});


	// Modal de Editar Chofer
	$("#tabla_choferes tbody").on("click", ".btn_editar", function (event) {
		$("#adm_choferes .modal-title").text("Modificar Chofer");
		var boton = $(event.currentTarget);
		var idchofer = boton[0].parentNode.parentNode.childNodes[6].value;
		$.ajax({
			type: "POST",
			url: "scripts/apichoferes.php",
			data: { param: 5, idchofer: idchofer },
			dataType: "json",
			success: function (response) {
				var cuit = response[0].cuit;

				cuitActual = cuit;
				if (cuit != null) {
					cuit = cuit.replace(/([0-9]{2})([0-9]{8})([0-9])/, "$1-$2-$3");
				}
				$("#adm_choferes .modal-body input#apynom").val(response[0].ayn);
				$("#adm_choferes .modal-body input#direccion").val(
					response[0].domicilio
				);
				$("#adm_choferes .modal-body #idlocalidad").val(
					response[0].idlocalidad
				);
				$("#adm_choferes .modal-body input#telefono").val(response[0].telefono);
				$("#adm_choferes .modal-body #cmbDoc").val(response[0].tipodoc);
				$("#adm_choferes .modal-body input#numdni").val(response[0].nrodoc);
				dniActual = response[0].nrodoc;
				$("#adm_choferes .modal-body input#email").val(response[0].email);
				$("#adm_choferes .modal-body input#fecnac").val(response[0].nacido);
				$("#adm_choferes .modal-body #cmbIva").val(response[0].iva);
				$("#adm_choferes .modal-body input#cuit").val(cuit);
				$("#adm_choferes .modal-body input#comis").val(response[0].comision);
				$("#adm_choferes .modal-body input#licencia").val(
					response[0].nrolicencia
				);
				$("#adm_choferes .modal-body #observaciones").val(response[0].observa);
				$("#adm_choferes .modal-body input#idchofer").val(idchofer);
				$("#adm_choferes").modal("show");
			},
			error: function (response) {
				console.log(response);
			},
		});
	});

	// Listener botones borrar del listado
	$("#tabla_choferes tbody").on("click", ".btn_borrar", function () {
		confirmarBorrado(this);
	});

	//Listener boton guardar del modal de Agregar o Editar chofer
	$("#guardar").on("click", function () {
		var apynom = $("#apynom").val();
		var direccion = $("#direccion").val();
		var idlocalidad = $("#idlocalidad").val();
		var telefono = $("#telefono").val();
		var cmbDoc = $("#cmbDoc").val();
		var numdni = $("#numdni").val();
		var email = $("#email").val();
		var fecnac = $("#fecnac").val();
		var cmbIva = $("#cmbIva").val();
		var cuit = $("#cuit").val();
		cuit = cuit.toString().replace(/[-_]/g, "");
		var comis = $("#comis").val();
		var licencia = $("#licencia").val();
		var observaciones = $("#observaciones").val();
		var idchofer = $("#idchofer").val();
		var valido = 0;
		valido = validarApynom(apynom) + validaCuit(cuit);
		if (valido == 0) {
			if (idchofer == "Agregar") {
				agregarChofer(
					apynom,
					direccion,
					idlocalidad,
					telefono,
					cmbDoc,
					numdni,
					email,
					fecnac,
					cmbIva,
					cuit,
					comis,
					licencia,
					observaciones
				);
			} else {
				if ($("#numdni").prop("disabled")) {
					vincularChofer(idchofer);
					$("#numdni").prop("disabled",false);
				}
				actualizarChofer(
					apynom,
					direccion,
					idlocalidad,
					telefono,
					cmbDoc,
					numdni,
					email,
					fecnac,
					cmbIva,
					cuit,
					comis,
					licencia,
					observaciones,
					idchofer
				);
			}
			$("#adm_choferes").modal("hide");
		}
	});

	// resetear campos al cerrar el modal
	$("#adm_choferes").on("hide.bs.modal", function () {
		$("#apynom").removeClass("is-invalid is-valid");
		$("#adm_choferes #cuit").removeClass("is-invalid is-valid");
		$("#adm_choferes #cuit").prop("required", false);
		$("#adm_choferes #numdni").removeClass("is-invalid is-valid");
		$("#numdni").prop("disabled",false);
	});
	
	// darle el focus al campo apellido y nombre al inciar el modal
	$("#adm_choferes").on("shown.bs.modal", function(){
		$("#apynom").focus();
	});

	// listener del buscador
	$("#buscador").on("input", function () {
		ocultarTR(this.value);
	});
});

// funcion del buscador
function ocultarTR(buscar) {
	var registros = $("tbody tr");
	var expresion = new RegExp(buscar, "i");

	for (let i = 0; i < registros.length; i++) {
		$(registros[i]).hide();
		if (
			registros[i].childNodes[0].textContent
				.replace(/\s/g, "")
				.search(expresion) != -1 ||
			buscar == ""
		) {
			$(registros[i]).show();
		}
	}
}

//funcion de listar los choferes de la tabla
function listar() {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 1 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				llenarTabla(response[0]); //llenar la tabla con los datos obtenidos
			} else {
				alertify.error("Error");
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

// funcion para recorrer el array de choferes obtenidos
function llenarTabla(respuesta) {
	respuesta.forEach((renglon) => {
		cargarFila(renglon);
	});
}

// funcion para insertar un chofer en el listado de choferes
function cargarFila(objeto) {
	$("#tabla_choferes").append(
		"<tr>" +
			"<td>" +
			objeto.ayn +
			"</td>" +
			"<td>" +
			objeto.telefono +
			"</td>" +
			"<td>" +
			objeto.domicilio +
			"</td>" +
			"<td>" +
			objeto.email +
			"</td>" +
			"<td>" +
			"<button class='btn btn-dark btn_editar' title='Modificar' data-toggle='modal'><i class='fas fa-edit'></i></button>" +
			"</td>" +
			"<td>" +
			"<button class='btn btn-danger btn_borrar' title='Eliminar'><i class='fas fa-trash-alt'></i></button>" +
			"</td>" +
			"<input type='hidden' value='" +
			objeto.idChofer +
			"'>" +
			"</tr>"
	);
}


function agregarChofer(
	apynom,
	direccion,
	idlocalidad,
	telefono,
	cmbDoc,
	numdni,
	email,
	fecnac,
	cmbIva,
	cuit,
	comis,
	licencia,
	observaciones
) {
	if (cuit!=""){
		cuit = parseInt(cuit.toString().replace(/[-_]/g, ""));
	}
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: {
			param: 2,
			ayn: apynom,
			domicilio: direccion,
			idlocalidad: idlocalidad,
			telefono: telefono,
			tipodoc: cmbDoc,
			nrodoc: numdni,
			email: email,
			nacido: fecnac,
			iva: cmbIva,
			cuit: cuit,
			comision: comis,
			nrolicencia: licencia,
			observa: observaciones,
		},
		dataType: "json",
		success: function (response) {
			if (response.exito == true) {
				var ochofer = {
					ayn: apynom,
					domicilio: direccion,
					telefono: telefono,
					email: email,
					idChofer: response.idPersona,
				};
				cargarFila(ochofer);
				alertify.success("Agregado " + apynom);
			} else {
				alertify.error("Hubo un error");
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function actualizarChofer(
	apynom,
	direccion,
	idlocalidad,
	telefono,
	cmbDoc,
	numdni,
	email,
	fecnac,
	cmbIva,
	cuit,
	comis,
	licencia,
	observaciones,
	idchofer
) {
	if (cuit!=""){
		cuit = parseInt(cuit.toString().replace(/[-_]/g, ""));
	}
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: {
			param: 3,
			ayn: apynom,
			domicilio: direccion,
			idlocalidad: idlocalidad,
			telefono: telefono,
			tipodoc: cmbDoc,
			nrodoc: numdni,
			email: email,
			nacido: fecnac,
			iva: cmbIva,
			cuit: cuit,
			comision: comis,
			nrolicencia: licencia,
			observa: observaciones,
			idchofer: idchofer,
		},
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				alertify.success("Actualizado con Exito");
				actualizarFila({
					apynom: apynom,
					domicilio: direccion,
					telefono: telefono,
					email: email,
					idchofer: idchofer,
				});
			} else {
				alertify.error("Hubo un error");
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function actualizarFila(objeto) {
	var input = $("input[value='" + objeto.idchofer + "']");
	if(input[1]){
		input[1].parentNode.childNodes[0].innerText = objeto.apynom;
		input[1].parentNode.childNodes[1].innerText = objeto.telefono;
		input[1].parentNode.childNodes[2].innerText = objeto.domicilio;
		input[1].parentNode.childNodes[3].innerText = objeto.email;
	}else{
		listar();
	}
}

function confirmarBorrado(botonBorrar) {
	var idchofer = botonBorrar.parentNode.parentNode.childNodes[6].value;
	var trBorrar = botonBorrar.parentNode.parentNode;
	alertify.confirm(
		"Eliminar",
		"Esta seguro que desea eliminarlo?",
		function () {
			borrarChofer(idchofer, trBorrar);
		},
		function () {
			alertify.error("Cancelado");
		}
	);
}

function borrarChofer(idchofer, trBorrar) {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 4, idchofer: idchofer },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				eliminarFila(trBorrar);
				alertify.success("Eliminado");
			} else {
				alertify.error("Hubo un error");
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function eliminarFila(trBorrar) {
	$(trBorrar).remove();
}

function validarApynom(apynom) {
	if (apynom == "") {
		$("#apynom").addClass("is-invalid");
		alertify.error("Apellido y Nombre no puede esta vacio");
		$("#apynom").on("input", function () {
			if ($(this).val() == "") {
				$(this).addClass("is-invalid");
			} else {
				$(this).addClass("is-valid");
				$(this).removeClass("is-invalid");
			}
		});
		return 1;
	} else {
		return 0;
	}
}

function validaCuit(cuit) {
	var cuit = cuit.toString().replace(/[-_]/g, "");
	if ($("#cmbIva").val() == 1 && cuit == "") {
		$("#cuit").removeClass("is-valid is-invalid");
		$("#cuit").prop("required", false);
		return 0;
	} else {
		if (
			cuit.length != 11 ||
			!validarCuit(cuit) ||
			cuitChoferes.includes(cuit)
		) {
			if (cuit.length == 11) {
				if (validarCuit(cuit)) {
					if (cuitChoferes.includes(cuit)) {
						if (cuit == cuitActual && $("#idchofer").val() != "Agregar") {
							$("#cuit").removeClass("is-invalid");
							$("#cuit").addClass("is-valid");
							return 0;
						} else {
							$("#errorCuit").text(
								"El número de CUIT ya esta asignado en otro chofer"
							);
							$("#cuit").removeClass("is-valid");
							$("#cuit").addClass("is-invalid");
						}
					} else {
						$("#cuit").removeClass("is-invalid");
						$("#cuit").addClass("is-valid");
					}
				} else {
					$("#errorCuit").text("El número de CUIT es invalido, compruebelo");
					$("#cuit").removeClass("is-valid");
					$("#cuit").addClass("is-invalid");
				}
			} else {
				$("#errorCuit").text(
					"El número de CUIT tiene 11 posiciones separadas con - . Por ejemplo:44-44444444-1"
				);
				$("#cuit").removeClass("is-valid");
				$("#cuit").addClass("is-invalid");
			}
			return 1;
		} else {
			$("#cuit").removeClass("is-invalid");
			$("#cuit").addClass("is-valid");
			return 0;
		}
	}
}

function validarCuit(cuit) {
	if (typeof cuit == "undefined" || cuit == "") {
		return true;
	}
	if (cuit.length != 11) {
		return false;
	} else {
		var mult = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
		var total = 0;
		for (var i = 0; i < mult.length; i++) {
			total += parseInt(cuit[i]) * mult[i];
		}
		var mod = total % 11;
		var digito = mod == 0 ? 0 : mod == 1 ? 9 : 11 - mod;
		return digito == parseInt(cuit[10]);
	}
}

function cargarCuitChoferes() {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 6 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				response[0].forEach((linea) => {
					cuitChoferes.push(linea.cuit);
				});
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function validarNumdni(numdni) {
	if (numdni == "" || typeof numdni == "undefined") {
		$("#numdni").removeClass("is-valid is-invalid");
		return 0;
	}
	if (numdni.length < 7) {
		$("#numdni").removeClass("is-valid");
		$("#numdni").addClass("is-invalid");
		$("#errorNumDni").text("El DNI no puede ser inferior a 7 caracteres");
		return 1;
	}
	// if ($(".modal-title").text().slice(0, 7) == "Agregar") {
	if ($("#idchofer").val() == "Agregar") {
		if (dniChoferes.includes(numdni)) {
			$("#numdni").removeClass("is-valid");
			$("#numdni").addClass("is-invalid");
			$("#errorNumDni").text("Este DNI pertenece a otro chofer. Verificar");
			return 1;
		} else if (dniPersonas.includes(numdni)) {
			alertify.confirm(
				"Existente",
				"Este DNI ya esta cargado. Desea traer los datos guardados?",
				function () {
					// alertify.success("Cargando...");
					$.ajax({
						type: "POST",
						url: "scripts/apipersonas.php",
						data: { param: 6, nrodoc: numdni },
						dataType: "json",
						success: function (response) {
							var cuit = response[0].cuit;
							cuitActual = cuit;
							if (cuit != null) {
								cuit = cuit.replace(/([0-9]{2})([0-9]{8})([0-9])/, "$1-$2-$3");
							}
							$("#adm_choferes .modal-body input#apynom").val(response[0].ayn);
							$("#adm_choferes .modal-body input#direccion").val(
								response[0].domicilio
							);
							$("#adm_choferes .modal-body #idlocalidad").val(
								response[0].idlocalidad
							);
							$("#adm_choferes .modal-body input#telefono").val(
								response[0].telefono
							);
							$("#adm_choferes .modal-body #cmbDoc").val(response[0].tipodoc);
							$("#adm_choferes .modal-body input#email").val(response[0].email);
							$("#adm_choferes .modal-body input#fecnac").val(
								response[0].nacido
							);
							$("#adm_choferes .modal-body #cmbIva").val(response[0].iva);
							$("#adm_choferes .modal-body input#cuit").val(cuit);
							$("#idchofer").val(response[0].idPersonas);
							$("#numdni").prop("disabled", "yes");
						},
						error: function (response) {
							console.log(response);
						},
					});
				},
				function () {
					alertify.error("No se cargaran los datos.");
				}
			);
		}
	} else {
		if (dniChoferes.includes(numdni) && numdni !== dniActual) {
			$("#numdni").removeClass("is-valid");
			$("#numdni").addClass("is-invalid");
			$("#errorNumDni").text("Este DNI pertenece a otro chofer. Verificar");
			return 1;
		}else if(dniPersonas.includes(numdni) && numdni !==dniActual){
			alertify.confirm(
				"Existente",
				"Este DNI ya esta cargado. Desea traer los datos guardados?",
				function () {
					idChoferActual = $("#adm_choferes .modal-body #idchofer").val();
					
					$.ajax({
						type: "POST",
						url: "scripts/apipersonas.php",
						data: { param: 6, nrodoc: numdni },
						dataType: "json",
						success: function (response) {
							var cuit = response[0].cuit;
							cuitActual = cuit;
							if (cuit != null) {
								cuit = cuit.replace(/([0-9]{2})([0-9]{8})([0-9])/, "$1-$2-$3");
							}
							$("#adm_choferes .modal-body input#apynom").val(response[0].ayn);
							$("#adm_choferes .modal-body input#direccion").val(
								response[0].domicilio
							);
							$("#adm_choferes .modal-body #idlocalidad").val(
								response[0].idlocalidad
							);
							$("#adm_choferes .modal-body input#telefono").val(
								response[0].telefono
							);
							$("#adm_choferes .modal-body #cmbDoc").val(response[0].tipodoc);
							$("#adm_choferes .modal-body input#email").val(response[0].email);
							$("#adm_choferes .modal-body input#fecnac").val(
								response[0].nacido
							);
							$("#adm_choferes .modal-body #cmbIva").val(response[0].iva);
							$("#adm_choferes .modal-body input#cuit").val(cuit);
							$("#idchofer").val(response[0].idPersonas);
							$("#numdni").prop("disabled", "yes");
						
						},
						error: function (response) {
							console.log(response);
						},
					});
				},
				function () {
					alertify.error("No se cargaran los datos.");
				}
			);
		}
	}
	$("#numdni").removeClass("is-invalid");
	$("#numdni").addClass("is-valid");
	return 0;
}

function cargarDniChoferes() {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 7 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				response[0].forEach((renglon) => {
					dniChoferes.push(renglon.nrodoc);
				});
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function cargarDniPersonas() {
	$.ajax({
		type: "POST",
		url: "scripts/apichoferes.php",
		data: { param: 8 },
		dataType: "json",
		success: function (response) {
			if (response.exito) {
				response[0].forEach((renglon) => {
					dniPersonas.push(renglon.nrodoc);
				});
			} else {
				console.log(response.msg);
			}
		},
		error: function (response) {
			console.log(response);
		},
	});
}

function vincularChofer(idchofer) {
	if(idChoferActual===0){
		$.ajax({
			type: "POST",
			url: "scripts/apichoferes.php",
			data: { param: 9, idchofer: idchofer },
			dataType: "json",
			success: function (response) {
				if (response.exito) {
					var ochofer = {
						ayn: "",
						domicilio: "",
						telefono: "",
						email: "",
						idChofer: idchofer,
					};
					cargarFila(ochofer);
				} else {
					alertify.error("Hubo un error");
					console.log(response.msg);
				}
			},
			error: function (response) {
				console.log(response);
			},
		});
	}else{
		$.ajax({
			type: "POST",
			url: "scripts/apichoferes.php",
			data: {param:10, idchofer, idChoferActual},
			dataType: "json",
			success: function(response){
				if(response.exito){
					$("#tabla_choferes input[value='" + idChoferActual + "']").val(idchofer);
					idChoferActual=0;
				}else{
					console.log(response.msg);
				}
			},
			error: function(response){
				console.log(response);
			}
		});
	}
}
