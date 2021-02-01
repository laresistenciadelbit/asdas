<?php
if (!defined('FROM_INDEX')) die();

include_once('model/index.php');
$model=new Model($db_conf);

//manejamos los datos recibidos de las estaciones
$json_params = file_get_contents("php://input");
$json_data=json_decode($json_params,true); //almacena internamente si hubo errores 

if('DEBUG' && $_SERVER['REMOTE_ADDR']!="127.0.0.1" /*&& $_SERVER['REMOTE_ADDR']!='x.x.x.x'*/ )
{ //En modo depuración logueará todas las peticiones realizadas
	
	$fp_log=fopen(DEBUG_REQUESTS_FILE, "a");
		fwrite($fp_log,'============================'.PHP_EOL);
		if (strlen($json_params) > 0 && json_last_error() == JSON_ERROR_NONE )
			fwrite($fp_log,'JSON: '.preg_replace('/\R/', '', print_r($json_params, TRUE)).PHP_EOL);
		if(isset($_GET))
			fwrite($fp_log,'GET:  '.preg_replace('/\R/', '', print_r($_GET, TRUE)).PHP_EOL);
		if(isset($_POST))
			fwrite($fp_log,'POST: '.preg_replace('/\R/', '', print_r($_POST, TRUE)).PHP_EOL);
		fwrite($fp_log,'IP:   '.$_SERVER['REMOTE_ADDR'].PHP_EOL);
		fwrite($fp_log, date("d-m-Y H:i:s").PHP_EOL);
	fclose($fp_log);
}

if (strlen($json_params) > 0 && json_last_error() == JSON_ERROR_NONE ) 
{

}
else //manejamos las peticiones del usuario
{
	//manejo de peticiones de escritura de configuración
	if(isset($_GET['w']) ) //petición de escritura de configuración de la web
	{
		if( isset($_GET['pass']) /*&& isset($_GET['fm']) && ... */  )
		{
			/*if($model->save_config($_GET['pass'],$_GET['fm'],$_GET['online_threshold_minutes'],$_GET['primary_sensor'],$_GET['primary_status']))
				$simple_output="<span style='color:green;'>Configuración guardada</span>";
			else
				$simple_output="<span style='color:red;'>Error al guardar la configuración</span>";*/
		}
		else
			$simple_output="ERROR2: Parámetros incorrectos";
	}
	//recibimos peticiones por ajax para obtener los datos de una fecha concreta
	if(isset($_GET['d']) )
	{
		/*if(isset($_GET['s']) && !empty($_GET['s']) )
			$simple_output=$model->get_station($_GET['d'],$_GET['s']);
		else
			$simple_output=$model->get_all($_GET['d']);*/
	}
	
	//configuración de la vista
	if(!isset($simple_output))	//si no se hizo una petición de escritura de configuración, o si se hizo, pero ésta falló
	{
		$config=$model->get_config();
		
		if(isset($_GET['p']))
			$current_view=$_GET['p'];
		else
			$current_view="main";
		
		switch($current_view)
		{
			case 'admin':
				if('DEBUG' && file_exists(DEBUG_REQUESTS_FILE) )	//leemos el fichero de log para almacenarlo en una variable que pasaremos a la vista
				{
					$fp_log=fopen(DEBUG_REQUESTS_FILE, "r") or die("No se pudo abrir el archivo ".DEBUG_REQUESTS_FILE);
					while(!feof($fp_log)) {
					  $request_log[]=fgets($fp_log) . "<br>";
					}
					fclose($fp_log);
				}
				$current_page="Administración";
			break;
			case 'category':
				$current_station=$_GET['c'];
				$current_page=$_GET['c'];
				$current_view='main';	//actúa como main (reutiliza la misma plantilla)
			break;
			case 'contact':
				$current_page="Contacto";
			break;
			default:
				$current_page="adminlte php skeleton";
		}
	}
}
?>