<?php
if (!defined('FROM_INDEX')) die();

class Mydb extends SQLite3 
{
	function __construct() 
	{
		$this->open('model/ASDAS.db');
	}
	
	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		$ret = $this->exec("INSERT INTO station_sensors values ('".$station."','".$sensor_name."','".$sensor_value."','".$time."');");
		if(!$ret) {
			echo $this->lastErrorMsg();
		}
	}
	
	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$ret = $this->exec("INSERT INTO station_status values ('".$station."','".$status_name."','".$time."','".$status_value."');");
		if(!$ret) {
			echo $this->lastErrorMsg();
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
		if(!$ret) {
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
  
	function get_station($station)
	{
		$ret = $this->query("
		SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se LEFT JOIN station_status st USING(station,time)
		WHERE station='".$station."'
		UNION ALL SELECT se.station,se.sensor_name,se.time, se.sensor_value, st.status_name, st.status_value from station_sensors se 
		LEFT JOIN station_status st USING(station,time) 
		WHERE station is null and time is null;
		");
		if(!$ret) {
			echo $this->lastErrorMsg();
		}
		return $ret;
	}

	function get_station_names()
	{
		$ret = $this->query("SELECT station from station_sensors group by station;");
		if(!$ret) {
			echo $this->lastErrorMsg();
		}
		return $ret;
	}
	  
}


?>