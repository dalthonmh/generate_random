<?php 

$errores = '';
if (isset($_POST['submit'])) {
	$semilla = $_POST['semilla'];
	$iteraciones = (int)$_POST['iteraciones'];
	$constante = (int)$_POST['constante'];

	if (!empty($semilla)) {
		$semilla = filter_var($semilla, FILTER_SANITIZE_STRING);
	}else{
		$errores .= 'semilla inválida <br>';
	}

	if (!empty($iteraciones)) {
		$iteraciones = filter_var($iteraciones, FILTER_SANITIZE_STRING);
	}else{
		$errores .= 'iteración inválida <br>';
	}

	if (!empty($constante)) {
		$constante = filter_var($constante, FILTER_SANITIZE_STRING);
	}else{
		$errores .= 'constante inválida <br>';
	}


}else{
	$semilla = 0;
	$iteraciones = 0;
	$constante = 0;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>RandomApp</title>
	<link rel="stylesheet" href="css/styles.css">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
</head>
<body>
	<section class="grid-container">
		<div class="grid-item generacion">
			GENERACIÓN DE NÚMEROS ALEATORIOS
		</div>
		<div class="grid-item algoritmo">
			<h3>ALGORITMO</h3>
			<br>
			<div class="metodos">
				<a href="#">Multiplicador Constante</a>
			</div>
		</div>
		<div class="grid-item ingreso">
			<h4>INGRESO DE DATOS</h4>
			<br>
			<form action="index.php" method="POST">
				<table>
					<tr>
						<td>Iteraciones :</td>
						<td><input type="text" name="iteraciones" value="10"></td>
					</tr>
					<tr>
						<td>Constante :</td>
						<td><input type="text" name="constante" value="6965"></td>
					</tr>
					<tr>
						<td>Semilla :</td>
						<td><input type="text" name="semilla" value="9803"></td>
					</tr>
					<?php if(!empty($errores)): ?>
					<tr>
						<td class="error"><?php echo $errores; ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td><input type="submit" name="submit" value="Generar"></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="grid-item descarga">
			<p>Confiabilidad: <span>95%</span></p>
			<a href="reportes/file01.txt" download="aleatorios">
				Descargar Resultados
			</a>
		</div>
	</section>
	<footer>
		
	</footer>
</body>
</html>


		<!-- 		<label for="iteraciones">iteraciones</label>
				<input type="text" id="iteraciones" name="iteraciones" value="10">
				<label for="iteraciones">constante</label>
				<input type="text" id="iteraciones" name="constante" value="6965">
				<label for="semilla">semilla</label>
				<input type="text" id="semilla" name="semilla" value="9803">
				<br>
				<input type="submit" value="Generar"> -->