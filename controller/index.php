<?php
if (!defined('FROM_INDEX')) die();

include_once('model/index.php');
$model=new Model($db_conf);

//manejamos los datos recibidos de las estaciones
$json_params = file_get_contents("php://input");
$json_data=json_decode($json_params,true); //almacena internamente si hubo errores 


if('DEBUG' && $_SERVER['REMOTE_ADDR']!="127.0.0.1" && $_SERVER['REMOTE_ADDR']!='81.202.88.64' )
{ //En modo depuración logueará todas las peticiones realizadas
	date_default_timezone_set('Europe/Madrid');
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

if (strlen($json_params) > 0 && json_last_error() == JSON_ERROR_NONE && isset($_GET['pw']) && $_GET['pw']==STATION_PASSWD )
{
	if(isset($json_data['sensor_name']))
		$simple_output=$model->insert_station_data($json_data['station_id'],$json_data['sensor_name'],$json_data['time'],$json_data['sensor_val']);
	else
	{
		if(isset($json_data['status_name']))
			$simple_output=$model->insert_station_aditional_data($json_data['station_id'],$json_data['status_name'],$json_data['time'],$json_data['status_val']);
		else
			$simple_output="ERROR1: Parámetros incorrectos";
	}
}
else //manejamos las peticiones del usuario
{
	//manejo de peticiones de escritura de configuración
	if(isset($_GET['w']) ) //petición de escritura de configuración de la web
	{
		if( isset($_GET['pass']) && isset($_GET['fm']) && isset($_GET['online_threshold_minutes']) && isset($_GET['primary_sensor']) && isset($_GET['primary_status'])  )
		{
			if($model->save_config($_GET['pass'],$_GET['fm'],$_GET['online_threshold_minutes'],$_GET['primary_sensor'],$_GET['primary_status']))
				$simple_output="<span style='color:green;'>Configuración guardada</span>";
			else
				$simple_output="<span style='color:red;'>Error al guardar la configuración</span>";
		}
		else
			$simple_output="ERROR2: Parámetros incorrectos";
	}
	//recibimos peticiones por ajax para obtener los datos de una fecha concreta
	if(isset($_GET['d']) )
	{
		if(isset($_GET['s']) && !empty($_GET['s']) )
			$simple_output=$model->get_station($_GET['d'],$_GET['s']);
		else
			$simple_output=$model->get_all($_GET['d']);
	}
	
	//configuración de la vista
	if(!isset($simple_output))	//si no se hizo una petición de escritura de configuración, o si se hizo, pero ésta falló
	{
		$stations=$model->get_station_names();
		$config=$model->get_config();
		
		if(isset($_GET['p'])) //if(isset($_GET['p']) && $_GET['p']!='station')
			$current_view=$_GET['p'];
		else
			$current_view="main";
		
		$current_station=''; //la ponemos vacía porque si entra en el main la introducirá en javascript
		$use_map=false;	// <- activa el script del api de mapas de osm según en que sección (por defectro falso, luego se activa en las secciones que lo queramos)	
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
			case 'station':
				$current_station=$_GET['s'];
				$current_page=$_GET['s'];
				$use_map=true;
				$current_view='main';	//actúa como main (reutiliza la misma plantilla)
			break;
			case 'contact':
				$current_page="Contacto";
			break;
			default:
				$current_page="Arduino Sim Data Adquisition System";
				//$current_view='main';
				$use_map=true;
				//$unsorted_data=$model->get_all(); // <- ahora la pedimos por ajax con javascript
		}
	}
}
?>