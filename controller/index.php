<?php
if (!defined('FROM_INDEX')) die();

include_once('model/index.php');
$model=new Model();

function isValidJSON($str) {
   json_decode($str);
   return json_last_error() == JSON_ERROR_NONE;
}

//manejamos los datos recibidos de las estaciones

$json_params = file_get_contents("php://input");
if (strlen($json_params) > 0 && isValidJSON($json_params))
{
	if(isset($json_params['sensor_name']))
		$model->insert_station_data($json_params['station_id'],$json_params['sensor_name'],$json_params['time'],$json_params['sensor_val']);
	else
		if(isset($json_params['status_name']))
			$model->insert_station_data($json_params['station_id'],$json_params['status_name'],$json_params['time'],$json_params['status_val']);
}
else //manejamos las peticiones del usuario
{
	$stations=$model->get_station_names();
	$config=$model->get_config();
	
	$use_map=false;	// <- activa el script del api de mapas de osm (por defectro falso, luego se reescribe si es necesario)

	if(isset($_GET['s']))
	{
		$current_station=$_GET['s'];
		$current_page=$_GET['s'];
		$current_view='sensor';
		$use_map=true;
		
	}
	else
	{
		if(isset($_GET['c']))
		{
			$current_page="Contacto";
			$current_view='contact';
		}
		else
		{
			$current_page="Página principal";
			$current_view='main';
			$use_map=true;
			$unsorted_data=$model->get_all();
			
			// https://stackoverflow.com/questions/20694317/json-encode-function-special-characters
		/*	//mysql data to one array
			$array=array();
			while($row = $result->fetch_array(MYSQL_ASSOC))
			{
				# Converting each column to UTF8
				$row = array_map('utf8_encode', $row);
				array_push($array,$row);
			}
			json_encode($array);
		*/
			
		/*	// sqlite data to one array
				$array=array();
				while($row = $unsorted_data->fetchArray(SQLITE3_ASSOC))
				{
					$row = array_map('utf8_encode', $row);
					array_push($array,$row);
				}
				echo json_encode($array);
		*/
			
		/*		//array en bruto (sqlite)
					while ($row = $unsorted_data->fetchArray(SQLITE3_ASSOC)) {
						//var_dump(json_encode(array_values($row)));
						//var_dump(json_encode($row));
						//var_dump($row);
						
						var_dump(json_encode(array_map('utf8_encode',$row)));
					}
		*/
	//	die();
		}
	}

}


?>