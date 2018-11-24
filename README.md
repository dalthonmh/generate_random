# generate_random
Programa que genera números aleatorios entre 0 y 1

Este programa usa el algoritmo multiplicador constante

IMPORTANTE: Usar un número no mayor a cien iteraciones para que en la prueba de varianza no siempre salga rechazado, ya que se hace uso de Excel desde php con PHPSpreadSheet, una librería de php que por su desarrollo en la función Statistical::CHIINV aún no soporta gran cantidad de datos.
