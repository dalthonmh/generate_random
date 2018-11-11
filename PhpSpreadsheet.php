<?php 
require 'index.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

//ruta a guardar el archivo
$ruta = "reportes/";
//creacion del libro de trabajo
$spreadsheet = new Spreadsheet();
//accedemos al objeto de la hoja
$sheet = $spreadsheet->getActiveSheet();
echo $numeros;
 ?>