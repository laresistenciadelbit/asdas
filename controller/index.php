<?php
if (!defined('FROM_INDEX')) die();

include_once('model/index.php');
$model=new Model($db_conf);

//manejamos los datos recibidos de las estaciones
$json_params = file_get_contents("php://input");
json_decode($json_params); //almacena internamente si hubo errores 

if (strlen($json_params) > 0 && json_last_error() == JSON_ERROR_NONE )
{
	if(isset($json_params['sensor_name']))
		$simple_output=$model->insert_station_data($json_params['station_id'],$json_params['sensor_name'],$json_params['time'],$json_params['sensor_val']);
	else
		if(isset($json_params['status_name']))
			$simple_output=$model->insert_station_aditional_data($json_params['station_id'],$json_params['status_name'],$json_params['time'],$json_params['status_val']);
}
else //manejamos las peticiones del usuario
{
	//manejo de peticiones de escritura de configuración
	if(isset($_GET['w']) ) //petición de escritura de configuración de la web
		if( isset($_GET['pass']) && isset($_GET['fm']) && isset($_GET['online_threshold_minutes']) && isset($_GET['primary_sensor']) && isset($_GET['primary_status'])  )
		{
			if($model->save_config($_GET['pass'],$_GET['fm'],$_GET['online_threshold_minutes'],$_GET['primary_sensor'],$_GET['primary_status']))
				$simple_output="<span style='color:green;'>Configuración guardada</span>";
			else
				$simple_output="<span style='color:red;'>Error al guardar la configuración</span>";
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