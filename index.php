<?php
/*	ASDAS - Arduino Sim Data Adquisition System
-Adminlte 3.1.0 con bootstrap v4.5.3. 
-Librerías añadidas: leaflet, moment y tempusdominus(locale:es)
-Por detrás php con un modelo simple personal tipo modelo-vista-controlador
-Por delante javascript procesa todos los datos que recibe de la base de datos por peticiones ajax
*/

define('DEBUG', 'true'); // para recibir salida de errores
define('DEBUG_REQUESTS_FILE', 'requests.log'); //Fichero de depuración para almacenar todas las peticiones web realizadas
define('DATABASE_TYPE', 'sqlite-demo'); // <- opciones posibles: mysql, sqlite

define('FAIL_RETURN', 'fail'); // mensaje devuelto a la estación en caso de fallo
define('CORRECT_RETURN', 'ok'); // mensaje devuelto a la estación en caso satisfactorio
define('FROM_INDEX', true);	// para saber si estamos cargando ese fichero desde esta página

if(DATABASE_TYPE=='mysql')
	$db_conf=["localhost","root","f******","asdas"]; //host,user,pass,dbname
if(DATABASE_TYPE=='sqlite')
	$db_conf="ASDAS.db";	//nombre del fichero de la base de datos
if(DATABASE_TYPE=='sqlite-demo')
	$db_conf="DEMO.db";

//$time_pre = microtime(true);

include_once('controller/index.php');

if(isset($simple_output))
	echo $simple_output;
else
	include_once('view/index.php');

//$exec_time = microtime(true) - $time_pre;
//echo '<br><br>tardó '.($exec_time).' segundos';  // 7.06s para mysql , 3.5s para sqlite
?>