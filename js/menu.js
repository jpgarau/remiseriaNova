$(document).ready(function () {
	if(typeof relojConsola !== 'undefined'){
		clearInterval(relojConsola)};

	$("#vehiculos").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/vehiculos.php");
	});
	$("#propietarios").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/propietarios.php", (res, status, xhr)=>{
			if(status==='error'){
				function redirigir(){
					window.location.replace("http://localhost/remiseria");
				}
				$("#contenedor").html("<div class='container'><div class='alert alert-danger m-4'><strong>Error</strong> en las credenciales. Redirigiendo...<div class='spinner-border spinner-border-sm float-right' role='status' aria-hidden='true'></div></div></div>");
				setTimeout(redirigir, 3000);
				(res, status, xhr);}
		});
	});
	$("#clientes").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/clientes.php");
	});
	$("#choferes").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/choferes.php");
	});
	$("#informes").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/informes.php");
	});
	$("#consola").click(function (e) {
		if(screen.width>1280){
			e.preventDefault();
			$("#contenedor").removeClass("menu-principal");
			$("#navbarGestion a").removeClass("active");
			$(this).addClass("active");
			$("#contenedor").load("vista/consola.php");
		}
	});
	$("#servicios").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/servicios.php");
	});
	$("#ctacte").click(function (e) {
		e.preventDefault();
		if(typeof relojConsola !== 'undefined'){
			clearInterval(relojConsola)};
		$("#contenedor").removeClass("menu-principal");
		$("#navbarGestion a").removeClass("active");
		$(this).addClass("active");
		$("#contenedor").load("vista/ctacte.php");
	});
});
