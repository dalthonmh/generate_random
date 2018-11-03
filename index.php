<?php 
require('index.view.php');




/*
	ALGORITMO MULTIPLICADOR CONSTANTE
*/
$handle = fopen("reportes/file01.txt", "w+");	//modo para lectura y escritura

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



?>
