<?php
if (!defined('FROM_INDEX')) die();

class Mydb extends SQLite3 
{
	function __construct($db_conf) 
	{
		$this->open('model/db/'.$db_conf);
		
		if(DATABASE_TYPE=='sqlite-demo')
			$this->demo_data_offset();
	}
	
	function demo_data_offset()	//Aumentará un día si no hay datos hoy, ya que es una base de datos de demostración. (NECESARIO que time no sea clave primaria para que permita modificar coincidiendo con otras fechas durante la ejecución)
	{
		do{
			$ret = $this->querySingle( "SELECT count(station) from station_sensors where time >= '".date("Y-m-d")." 00:00:00'" );
			if( !$ret )
			{
				$this->exec(" UPDATE station_sensors SET time=DateTime(time, 'LocalTime', '+1 Day') ");
				$this->exec(" UPDATE station_status  SET time=DateTime(time, 'LocalTime', '+1 Day') ");
			}
		}while(!$ret);
	}

	function load_config()
	{
		$ret = $this->query("SELECT * FROM config;");
		if(!$ret && DEBUG) {
			echo $this->lastErrorMsg();
		}
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ){$config=$row;}
		return $config;
	}
	
	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		$ret = $this->exec("INSERT INTO station_sensors values ('".$station."','".$sensor_name."','".$time."','".$sensor_value."');");
		if(!$ret) {
			if(DEBUG)
				return $this->lastErrorMsg();
			else
				return FAIL_RETURN;
		}
		else
			return CORRECT_RETURN;
	}
	
	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$ret = $this->exec("INSERT INTO station_status values ('".$station."','".$status_name."','".$time."','".$status_value."');");
		if(!$ret) {
			if(DEBUG)
				return $this->lastErrorMsg();
			else
				return FAIL_RETURN;
		}
		else
			return CORRECT_RETURN;
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
		$ret = $this->query("SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							".$where_date." ".$where_station."
							UNION ALL SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							WHERE station is null and time is null;");
		if(!$ret && DEBUG) {
			echo $this->lastErrorMsg();
		}
		
		// https://stackoverflow.com/questions/20694317/json-encode-function-special-characters
		$array=array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC))
		{
			$row = array_map('utf8_encode', $row);
			array_push($array,$row);
		}
		return json_encode($array);
	}

	function get_station_names()
	{
		$ret = $this->query("SELECT station from station_sensors group by station;");
		if(!$ret && DEBUG) {
			echo $this->lastErrorMsg();
		}
		
		$array=array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC))
		{	
			$row=array_map('utf8_encode', $row);
			array_push($array,$row['station']);
		}
		return $array;
	}

	function set_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status)
	{
	
		/*	// <- si quieremos que al escribir la configuración se guarde todo menos la contraseña si está vacía
		$sql_query="UPDATE config set ";
		if($pass!='')
			$sql_query=$sql_query."pass='".$pass."' ,";
		$sql_query=$sql_query." fm='".$fm."', online_threshold_minutes='".$online_threshold_minutes."', primary_sensor='".$primary_sensor."', primary_status='".$primary_status."';");
		
		$ret = $this->exec($sql_query);
		*/
	
		$ret = $this->exec("UPDATE config set pass='".$pass."' , fm='".$fm."', online_threshold_minutes='".$online_threshold_minutes."', primary_sensor='".$primary_sensor."', primary_status='".$primary_status."';");
		if(!$ret && DEBUG ) {
			echo $this->lastErrorMsg();
		}
		if($ret)
			return true;
		else
			return false;
	}
}
?>