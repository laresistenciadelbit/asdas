<?php
if (!defined('FROM_INDEX')) die();

include_once('model/validation.php');

if(DATABASE_TYPE=='sqlite' || DATABASE_TYPE=='sqlite-demo')
	include_once('model/sqlite.php');
if(DATABASE_TYPE=='mysql')
	include_once('model/mysql.php');

class Model
{
	private $db;
	private $validate;
	private $config;

	function __construct($db_conf) 
	{
		$this->db = new Mydb($db_conf);
		if(!$this->db)
			echo $this->db->lastErrorMsg();
		
		$this->validate=new Validation();
		
		$this->config=$this->db->select_row_array("SELECT * FROM config;");	
	}

	public function __destruct()
	{ 
		$this->db->close();
	}

	function get_config()
	{
		return $this->config;
	}

	function validate_station_data(&$station,&$setting_name,&$time,&$setting_value)
	{
		$valid=true;
		if(!$this->validate->v_str($station))
			$valid=false;
		else
			$station=htmlspecialchars($station,ENT_QUOTES);
		if(!$this->validate->v_str($setting_name))
			$valid=false;
		else
			$setting_name=htmlspecialchars($setting_name,ENT_QUOTES);
		if(!$this->validate->v_time($time))
			$valid=false;
		if(!$this->validate->v_value($setting_value))
			$valid=false;
		
		return $valid;
	}
		
	function format_time_from_sim_module($time)	//Cambia el formato de fecha que obtiene el módulo Sim de la estación de telefonía al formato de la base de datos: 21/01/31,00:52:57+04 ->  2021-01-31 00:52:57	
	{
		return ( substr(date("Y"),0,2).str_replace( "/","-",str_replace( ","," ",substr($time,0,strlen($time)-3) ) ) );
	}

	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		//$station=utf8_decode($station);
		//$sensor_name=utf8_decode($sensor_name);
		$valid=$this->validate_station_data($station,$sensor_name,$time,$sensor_value);
		
		if($valid)
		{
			$time=$this->format_time_from_sim_module($time);
			return $this->db->insert("INSERT INTO station_sensors values ('".$station."','".$sensor_name."','".$time."','".$sensor_value."');");
		}
		else
			return FAIL_RETURN;
	}

	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		//$station=utf8_decode($station);
		//$status_name=utf8_decode($status_name);
		$valid=$this->validate_station_data($station,$status_name,$time,$status_value);
		
		if($valid)
		{
			$time=$this->format_time_from_sim_module($time);
			return $this->db->insert("INSERT INTO station_status values ('".$station."','".$status_name."','".$time."','".$status_value."');");
		}
		else
			return FAIL_RETURN;
	}

	function get_all($date="")
	{
		if( $this->validate->v_date($date) || $date=='' )
			return $this->get_all_data($date);
		else
			return false;
	}

	function get_station($date,$station)
	{
		if( $this->validate->v_str($station) && ( $this->validate->v_date($date) || $date=='' ) )
			return $this->get_all_data($date,utf8_decode(htmlspecialchars($station,ENT_QUOTES)));	//importante el utf8_decode en base de datos sqlite, ya que sqlite en php no admite una conexión con codificación utf8
		else
			return false;
	}
	
	function get_all_data($date,$station=NULL)
	{
		if(!is_null($date))
		{
			if( substr_count( $date,'-' )!=1 )
				$where_date=" WHERE strftime('%Y-%m-%d',time) = '".$date."' "; //$where_date=" WHERE time = DATE('".$date."') ";
			else
			{
				//$month_start=$date."/01";
				//$month_end=$date."/31"; //irrelevante si nos pasamos de días totales de ese mes
				//$where_date=" WHERE time between DATE('".$month_start."') AND DATE('".$month_end."') ";
				$where_date=" WHERE strftime('%Y-%m',time) = '".$date."' ";
			}
			$where_station_prefix=" AND ";
		}
		else
		{
			$where_date="";
			$where_station_prefix=" WHERE ";
		}
		
		if(!is_null($station))
			$where_station = $where_station_prefix." station='".$station."' ";
		else
			$where_station = "";		
		//  *sqlite no permite full outer join (lo necesitamos ya que no todas las estaciones tienen que transmitir su estado o su gps)
		//  *lo podemos emular con https://www.sqlitetutorial.net/sqlite-full-outer-join/
		
		return $this->db->select_json_array("SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							".$where_date." ".$where_station."
							UNION ALL SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							WHERE station is null and time is null;");
	}

	function get_station_names()
	{
		return $this->db->select_string_array("SELECT station from station_sensors group by station;");
	}
	
	function get_sensor_names()
	{
		return $this->db->select_string_array("SELECT sensor_name from station_sensors group by sensor_name;");
	}
	
	function get_sensor_maps()
	{
		return $this->db->select_json_array("SELECT ss.sensor_name,sm.sensor_map FROM station_sensors ss
							LEFT JOIN sensor_mapping sm USING(sensor_name)
							group by sensor_name
							;");
	}
	
	function save_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status)
	{
		$valid=true;
		$ret=false;
		if(!$this->validate->v_str($pass))
			$valid=false;
		else
			$pass=htmlspecialchars($pass);
		if(!$this->validate->v_onetothousand($fm) && $fm!="" )
			$valid=false;
		if(!$this->validate->v_onetothousand($online_threshold_minutes))
			$valid=false;
		if(!$this->validate->v_str($primary_sensor))
			$valid=false;
		else
			$primary_sensor=htmlspecialchars($primary_sensor);
		if(!$this->validate->v_str($primary_status))
			$valid=false;
		else
			$primary_status=htmlspecialchars($primary_status);
		
		if($valid)
			$ret=$this->db->update("UPDATE config set pass='".$pass."' , fm='".$fm."', online_threshold_minutes='".$online_threshold_minutes."', primary_sensor='".$primary_sensor."', primary_status='".$primary_status."';");
		
		return $ret;
	}
	
	function save_mapping($sensors,$maps)
	{
		$valid=true;
		$ret=false;
		for($i=0;$i<sizeof($sensors);$i++)
		{
			if(!$this->validate->v_str($sensors[$i]))
				$valid=false;
			else
				$sensors[$i]=htmlspecialchars($sensors[$i]);
		}		

		foreach($maps as $map)
		{
			if(!$this->validate->v_eval_operations($map) && $map!="")
			{
				$valid=false;
				break;
			}
		}

		if($valid)
		{
			for($i=0;$i<sizeof($sensors);$i++)
			{
				if($maps[$i]!="")	//si el campo no está vacío
				{
					if(!$this->db->select_simple("SELECT sensor_name FROM sensor_mapping WHERE sensor_name='".$sensors[$i]."';"))	//si no existe una fila con el mapeado del sensor la creamos
					{
						$ret=$this->db->insert("INSERT INTO sensor_mapping VALUES ('".$sensors[$i]."' , '".$maps[$i]."');");
						if(!$ret)
							break;
					}
					else
					{
						$ret=$this->db->update("UPDATE sensor_mapping set sensor_map='".$maps[$i]."' WHERE sensor_name='".$sensors[$i]."';");
						if(!$ret)
							break;
					}
				}
			}
		}
		
		return $ret;
	}
	
}
?>