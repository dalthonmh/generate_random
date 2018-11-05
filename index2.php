<?php 

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Generar Aleatorio</title>
	<link rel="stylesheet" href="css/estilos.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
	<div class="body-container">
		<header class="header">
			<h2 class="header-title">GENERAR ALEATORIO</h2>
		</header>
		<main class="main-box">
			<section class="box-info ingreso-datos">
				<div class="box-head">
					INGRESO DE DATOS
				</div>
				<div class="box-body">
					<form action="">
						<table>
							<tr>
								<td>Iteraciones:</td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td>Constante</td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td>Semilla</td>
								<td><input type="text"></td>
							</tr>
							<tr>
								<td></td>
								<td><input type="submit" value="Generar"></td>
							</tr>
						</table>
					</form>
				</div>
			</section>
			<section class="box-info validaciones">
				<div class="box-head">
					VALIDACIÓN DE PRUEBAS
				</div>
				<div class="box-body">
					<table class="prueba-table">
						<tr>
							<td class="prueba">Prueba de Medias</td>
							<td><span class="aceptada">Aceptada</span></td>
						</tr>
						<tr>
							<td class="prueba">Prueba de Varianza</td>
							<td><span class="aceptada">Aceptada</span></td>
						</tr>
						<tr>
							<td class="prueba">Prueba de Uniformidad</td>
							<td><span class="rechazada">Rechazada</span></td>
						</tr>
						<tr>
							<td class="prueba">Prueba de Independencia</td>
							<td><span class="aceptada">Aceptada</span></td>
						</tr>
					</table>
				</div>
			</section>
			<section class="box-info resultados">
				<div class="box-head">
					RESULTADOS
				</div>
				<div class="box-body">
					<div class="box-body-firstpart">
						<a href="#">Descargar</a>
					</div>
					<hr>
					<div class="box-body-secondpart">
						<p>Sobre la aplicación</p>
						<p>Algoritmo: Multiplicador Constante</p>
						<p>Lenguaje de Programación: php</p>
						<p>Base de Datos: mysql</p>
						<p>Codigo funte: <a href="#">source</a></p>
						<p>Versión: 1.0.0</p>
					</div>
				</div>
			</section>
		</main>
		<footer class="footer">
			<p class="info-footer">04/11/2018 06:50 pm</p>
		</footer>
	</div>
</body>
</html>