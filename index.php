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
/*------------------------------------*/
/*	------------PRUEBAS------------	*/
/*------------------------------------*/

/*-----PhpSpreadsheet-----*/
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Calculation\Statistical;
/*-----PhpSpreadsheet-----*/

/*Prueba de varianza*/
/*
	Esta prueba se realiza con cualquier rango de valores, lo que hace es hallar la media de los aleatorios para luego mediante la distribucion normal con un nivel de confianza de 95% se hallen los limites superiores e inferiores.
*/
$aceptacion = '';
$varianza = '';
$chiquad = '';

	if (count($numeros)==true) {
		$n = count($numeros);
		$f = array_sum($numeros) / count($numeros);
		$var = 0;

		
		$liInfMed = 0.5-1.96*(1/sqrt(12*$n)).'<br>';
		$liSupMed = 0.5+1.96*(1/sqrt(12*$n)).'<br>';

		if($f<$liSupMed && $f>$liInfMed){
			$estadomedia = true; // aceptada
		}else{
			$estadomedia = false; //rechazada
		}
		$liSupMed = round( $liSupMed, 4);
		$liInfMed = round( $liInfMed, 4);
		$f = round( $f, 4);
		/*PRUEBA DE MEDIAS*/
		/*
			Nota: esta prueba es valida solo para una cantidad menor de 100 numeros aleatorios
		*/
		for ($i=1; $i <= $n; $i++) { 
			$var = $var + pow(($numeros[$i]-$f), 2);
		}
		$varianza = $var / ($n-1);

		// echo stats_variance($numeros);
		// $nmenosuno = $n-1;

		//ruta a guardar el archivo
		$ruta = "reportes/";
		//creacion del libro de trabajo
		$spreadsheet = new Spreadsheet();
		//accedemos al objeto de la hoja
		$sheet = $spreadsheet->getActiveSheet();
		
		
		
		$sheet->setCellValue('A1','FORMULA');
		$sheet->setCellValue('B1','VALOR');
		$sheet->setCellValue('A2','a/2');
		$sheet->setCellValue('A3','1-(a/2)');
		$sheet->setCellValue('A4','LI');
		$sheet->setCellValue('A5','LS');
		$sheet->setCellValue('A6','var');
		$sheet->setCellValue('A7','tablaXa,n');

		$sheet->setCellValue('B2',
			Statistical::CHIINV(0.025,($n-1))
		);
		// $sheet->setCellValue('B2',"=CHISQ.INV(0.025;99)");
		$sheet->setCellValue('B3',
			Statistical::CHIINV(0.975,($n-1))
		);
		$sheet->setCellValue('B4',"=B2/(12*$n)");
		$sheet->setCellValue('B5',"=B3/(12*$n)");

		$sheet->setCellValue('B6',$varianza);

		/*dato para prueba chi-cuadrado*/
		$m = sqrt($n);
		$mint = intval($m);

		$sheet->setCellValue('B7',
			Statistical::CHIINV(0.05,($mint-1))
		);
		/*fin dato pueba*/
		$writer = new Xlsx($spreadsheet);

		try{
			$writer->save($ruta.'file02.xlsx');
		}
		catch(Exception $e){
			echo $e->getMessage();
		}
		$liInfvar = $sheet->getCell('B4')->getCalculatedValue();
		$liSupvar = $sheet->getCell('B5')->getCalculatedValue();

		if ($varianza<$liInfvar && $varianza>$liSupvar) {
			$estadovarianza=true; // aceptada
		}
		else{
			$estadovarianza=false; // rechazada
		}
		$liSupvar = round( $liSupvar, 4);
		$liInfvar = round( $liInfvar, 4);
		$varianza = round( $varianza, 4);
		// echo($liInfvar.' '.$varianza.' '.$liSupvar);
		
		/*-------PRUEBA DE UNIFORMIDAD-------*/
		/*
			prueba de chi cuadrada
		*/

		$m = sqrt($n);
		$mint = intval($m);
		$fobservada[0][0]=0; //Oi Frecuencia observada
		$cont = 0;

		$inicio= 0.0;
		$mparcial = 1/$m;
		$inc = 1; //incrementador
		for ($i=0; $i <$mint ; $i++) { 
			$final = $inc*$mparcial;
			if ($i == $mint-1) {
				$final = 1.0;
			}
			/*condicion */
			for ($j=1; $j <= $n; $j++) { 
				if (($numeros[$j]>$inicio) && ($numeros[$j]<$final)) {
					$fobservada[$i][$cont]=$numeros[$j];
					$cont++;
				}
			}
			/*fin condición*/
			$inicio = $final;
			$inc++;
		}
		/*fin agregacion intervalos y frecuencia observada*/
		$fobscount = count($fobservada);
		$fesperada = $n/$m; //Ei Frecuencia esperada
		$chiparcial = 0.00;

		for ($i=0; $i < $fobscount; $i++) { 
			// echo count($fobservada[$i])."<br>";
			$chiparcial = $chiparcial + pow(($fesperada-count($fobservada[$i])), 2)/$fesperada;
			// $chiparcial = $chiparcial + (Ei-Oi)^2/Ei
		}
		// var_dump($fobservada);
		// echo "<br>".$chiparcial;
		$limchiq= $sheet->getCell('B7')->getCalculatedValue();
		
		if ($chiparcial<$limchiq) {
			$estadochiquad=true; // aceptada
		}
		else{
			$estadochiquad=false; // rechazada
		}
		$chiparcial = round($chiparcial,4);
		$limchiq = round($limchiq,4);

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
			<h2 class="header-title">GENERADOR DE NÚMEROS ALEATORIOS</h2>
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
						<thead>
							<tr>
								<th>Prueba</th>
								<th>Estado</th>
								<th>L. Inferior</th>
								<th>Valor</th>
								<th>L. Superior</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($numeros)==true): ?>
							<tr>
								<td class="prueba">Prueba de Medias</td>
								<td>
									<span class="<?php if($estadomedia==true)echo('aceptada');else echo('rechazada'); ?>">
										<?php if($estadomedia==true)echo('aceptada');else echo('rechazada'); ?>
									</span>
								</td>
								
								<td><?php echo $liInfMed; ?></td>
								<td><?php echo $f; ?></td>
								<td><?php echo $liSupMed; ?></td>
								
							</tr>
							<tr>
								<td class="prueba">Prueba de Varianza</td>
								<td>
									<span class="<?php if($estadovarianza==true)echo('aceptada');else echo('rechazada'); ?>">
										<?php if($estadovarianza==true)echo('aceptada');else echo('rechazada'); ?>
									</span>
								</td>
								<td><?php echo $liInfvar; ?></td>
								<td><?php echo $varianza; ?></td>
								<td><?php echo $liSupvar; ?></td>
							</tr>
							<tr>
								<td class="prueba">Prueba de Uniformidad</td>
								<td>
									<span class="<?php if($estadochiquad==true)echo('aceptada');else echo('rechazada'); ?>">
										<?php if($estadochiquad==true)echo('aceptada');else echo('rechazada'); ?>
									</span>
								</td>
								<td></td>
								<td><?php echo $chiparcial; ?></td>
								<td><?php echo $limchiq; ?></td>
								
							</tr>
						<?php else: ?>
							<tr>
								<td class="prueba">Prueba de Medias</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="prueba">Prueba de Varianza</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="prueba">Prueba de Uniformidad</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</section>
			<section class="box-info resultados">
				<div class="box-head">
					RESULTADOS
				</div>
				<div class="box-body">
					<div class="box-body-firstpart">
						<a href="reportes/file01.txt" 
							class="<?php if(count($numeros))echo('active');else echo('not-active'); ?>"
							 
							download="aleatorios">
							
							<img src="svg/<?php if(count($numeros))echo('icon-download');else echo('icon-download-disabled'); ?>.svg" alt="img-decarga"> Descargar</a>
					</div>
					<div class="box-body-secondpart">
						<p>Sobre la aplicación</p>
						<p>Autor: Dalthon Mamani Hualpa</p>
						<p>Curso: Simulación de Sistemas</p>
						<p>Algoritmo: Multiplicador Constante</p>
						<p>Lenguaje de Programación: php</p>
						<p>Codigo funte: <a href="https://github.com/D4ITON/generate_random" target="_blank">source</a></p>
						<p>Versión: 1.2.1</p>
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