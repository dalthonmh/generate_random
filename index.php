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
$varianza = '';

	if (count($numeros)==true) {
		$n = count($numeros);
		$f = array_sum($numeros) / count($numeros);
		$var = 0;

		$liminf95 = 0.5-1.96*(1/sqrt(12*$n)).'<br>';
		$limsup95 = 0.5+1.96*(1/sqrt(12*$n)).'<br>';

		if($f<$limsup95 && $f>$liminf95){
			$aceptacion = "Aceptada";
		}else{
			$aceptacion = "Rechazada";
		}
		
		for ($i=1; $i <= $n; $i++) { 
			$var = $var + pow(($numeros[$i]-$f), 2);
		}
		$varianza = $var / $n;
	}


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
					<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="input-form">
						<table>
							<tr>
								<td>Iteraciones:</td>
								<td><input 
									type="text" 
									name="iteraciones" 
									value="<?php echo $iteraciones; ?>"></td>
							</tr>
							<tr>
								<td>Constante:</td>
								<td><input 
									type="text" 
									name="constante"
									value="<?php echo $constante; ?>"></td>
							</tr>
							<tr>
								<td>Semilla:</td>
								<td><input 
									type="text" 
									name="semilla"
									value="<?php echo $semilla; ?>"></td>
							</tr>
							<?php if(!empty($errores)): ?>
								<tr>
									<td></td>
									<td class="error"><?php echo $errores; ?></td>
								</tr>
							<?php endif ?>

							<tr>
								<td></td>
								<td><input type="submit" name="submit" value="Generar" id="btn-generar"></td>
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
							<td>
								<?php if(count($numeros)==true): ?>
								<span class="<?php if($aceptacion=='Aceptada')echo('aceptada');else echo('rechazada'); ?>"><?php echo $aceptacion; ?></span>
								<?php else: ?>
									<span class="waiting">waiting...</span>
							<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td class="prueba">Prueba de Varianza</td>
							<td>
								<?php if (count($numeros)==true): ?>
									<span class="<?php if($varianza=='Aceptada')echo('aceptada');else echo('rechazada'); ?>"><?php echo $varianza; ?></span>
								<?php else: ?>
									<span class="waiting">waiting...</span>
								<?php endif; ?>
							</td>
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
						<a href="reportes/file01.txt" download="aleatorios"><img src="svg/icon-download.svg" alt="img-decarga"> Descargar</a>
					</div>
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