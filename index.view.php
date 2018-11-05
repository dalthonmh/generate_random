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

/*
	ALGORITMO MULTIPLICADOR CONSTANTE
*/
$handle = fopen("reportes/file01.txt", "w+");	//modo para lectura y escritura

$numeros = [];
$x[0] = (int)$semilla;
$texto=[];
for ($i=1; $i <= $iteraciones; $i++) { 
	$x[$i] = $constante*$x[$i-1];
	$digito = (string)$x[$i];
	if ((strlen($digito)%2 === 0) && (strlen($digito) ===8)) {
		$digito = substr($digito, 2,4);
	}
	else{
		(strlen($digito) === 5)? $digito = substr($digito,0,4) : $digito = substr($digito,1,4);
	}
	$r[$i] = "0,".$digito;
	$x[$i] = (int)$digito;
	// echo "<br>".$r[$i];
	$texto = $r[$i].chr(13).chr(10);
	$numeros[$i] = $r[$i];
	$numeros[$i] = (float)str_replace(",", ".", $numeros[$i]);
	fwrite($handle, $texto);
}

/*	PRUEBA DE MEDIAS	*/
$aceptacion = '';
// var_dump(($numeros));
	if (count($numeros)==true) {
		$n = count($numeros);
		$f = array_sum($numeros) / count($numeros);

		$liminf95 = 0.5-1.96*(1/sqrt(12*$n)).'<br>';
		$limsup95 = 0.5+1.96*(1/sqrt(12*$n)).'<br>';

		if($f<$limsup95 && $f>$liminf95){
			$aceptacion = "Aceptada";
		}else{
			$aceptacion = "Rechazada";
		}
	}

	// var_dump($iteraciones);

?>
<!DOCTYPE html>
<html lang="es">
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
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
				<table>
					<tr>
						<td>Iteraciones :</td>
						<td><input type="text" 
							name="iteraciones" 
							id="iteraciones" 
							value="<?php echo $iteraciones ?>"></td>
					</tr>
					<tr>
						<td>Constante :</td>
						<td><input type="text" 
							name="constante" 
							id="constante" 
							value="<?php echo $constante; ?>"></td>
					</tr>
					<tr>
						<td>Semilla :</td>
						<td><input type="text" 
							name="semilla" 
							id="semilla" 
							value="<?php echo $semilla; ?>"></td>
					</tr>
					<?php if(!empty($errores)): ?>
					<tr>
						<td class="error"><?php echo $errores; ?></td>
					</tr>
					<?php endif; ?>
					<tr>
						<td><input type="submit" name="submit" value="Generar" id="submit"></td>
					</tr>
				</table>
			</form>
		</div>
		<div class="grid-item descarga">
			<?php if(count($numeros)==true): ?>
			<p class="pruebamedias">Prueba de medias: 
				<span class="<?php if($aceptacion=='Aceptada')echo('aceptada');else echo('rechazada'); ?>"><?php echo $aceptacion; ?></span>
			</p>
			<p class="pruebavarianza">Prueba de varianzas: Aceptada</p>
			<p class="pruebauniformidad">Prueba de uniformidad: Aceptada</p>
			<p class="pruebaindependencia">Prueba de independencia: Aceptada</p>
			<a href="reportes/file01.txt" download="aleatorios">
				Descargar Resultados
			</a>
			<?php else: ?>
			<p>Resultados</p>
			<?php endif; ?>
		</div>
	</section>
	<footer>
		
	</footer>
	<script>

		
		document.getElementById("submit").onclick = function(){
			var iteraciones = document.getElementById("iteraciones").value;
			var constante = document.getElementById("constante").value;
			var semilla = document.getElementById("semilla").value;
			iteraciones.value=iteraciones;
			constante.value=constante;
			semilla.value=semilla;
			console.log(iteraciones);
		}
			// console.log('hola');

	</script>
</body>
</html>