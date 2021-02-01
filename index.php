<?php

define('DEBUG', 'true'); // para recibir salida de errores
define('DEBUG_REQUESTS_FILE', 'requests.log'); //Fichero de depuración para almacenar todas las peticiones web realizadas
define('DATABASE_TYPE', 'sqlite'); // <- opciones posibles: mysql, sqlite

define('FAIL_RETURN', 'fail'); // mensaje devuelto a la estación en caso de fallo
define('CORRECT_RETURN', 'ok'); // mensaje devuelto a la estación en caso satisfactorio
define('FROM_INDEX', true);	// para saber si estamos cargando ese fichero desde esta página

date_default_timezone_set('Europe/Madrid');

if(DATABASE_TYPE=='mysql')
	$db_conf=["localhost","root","*****","*****"]; //host,user,pass,dbname
if(DATABASE_TYPE=='sqlite')
	$db_conf="DB.db";	//nombre del fichero de la base de datos

//$time_pre = microtime(true);

include_once('controller/index.php');

if(isset($simple_output))
	echo $simple_output;
else
	include_once('view/index.php');

//$exec_time = microtime(true) - $time_pre;
//echo '<br><br>tardó '.($exec_time).' segundos';  // 7.06s para mysql , 3.5s para sqlite
?>