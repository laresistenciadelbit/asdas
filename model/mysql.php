<?php
if (!defined('FROM_INDEX')) die();

class Mydb extends mysqli 
{
	function __construct($db_conf) {
        parent::__construct($db_conf[0], $db_conf[1], $db_conf[2], $db_conf[3]);
		if ($this->connect_errno != 0)
		{
			echo 'error al conectar con la base de datos MYSQL';
			die();
		}
    }
	
	function load_config()
	{
		$ret = $this->query("SELECT * FROM config;");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
		while($row = $ret->fetch_assoc() ){$config=$row;}
		return $config;
	}
	
	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		$ret = $this->query("INSERT INTO station_sensors values ('".$station."','".$sensor_name."','".$sensor_value."','".$time."');");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
	}
	
	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$ret = $this->query("INSERT INTO station_status values ('".$station."','".$status_name."','".$time."','".$status_value."');");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
	}
	
	function get_all_data()
	{
		//  *sqlite no permite full outer join (lo necesitamos ya que no todas las estaciones tienen que transmitir su estado o su gps)
		//  *lo podemos emular con https://www.sqlitetutorial.net/sqlite-full-outer-join/
		$ret = $this->query("SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							UNION ALL SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
							LEFT JOIN station_status st USING(station,time) 
							WHERE station is null and time is null;");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
		
		// https://stackoverflow.com/questions/20694317/json-encode-function-special-characters
		$array=array();
		while($row = $ret->fetch_assoc())
		{
			$row = array_map('utf8_encode', $row);
			array_push($array,$row);
		}
		return json_encode($array);
	}
  
	function get_station($station)
	{
		$ret = $this->query("
		SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se LEFT JOIN station_status st USING(station,time)
		WHERE station='".$station."'
		UNION ALL SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
		LEFT JOIN station_status st USING(station,time) 
		WHERE station is null and time is null;
		");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
		return $ret;
	}

	function get_station_names()
	{
		$ret = $this->query("SELECT station from station_sensors group by station;");
		if(!$ret && DEBUG) {
			echo $this->error();
		}
		
		$array=array();
		while($row = $ret->fetch_assoc())
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
		
		$ret = $this->query($sql_query);
		*/
	
		$ret = $this->query("UPDATE config set pass='".$pass."' , fm='".$fm."', online_threshold_minutes='".$online_threshold_minutes."', primary_sensor='".$primary_sensor."', primary_status='".$primary_status."';");
		if(!$ret && DEBUG ) {
			echo $this->error();
		}
		if($ret)
			return true;
		else
			return false;
	}
}


?>