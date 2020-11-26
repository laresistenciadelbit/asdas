<?php
if (!defined('FROM_INDEX')) die();

include_once('model/validation.php');

if(DATABASE_TYPE=='sqlite')
	include_once('model/sqlite.php');
if(DATABASE_TYPE=='mysql')
	include_once('model/mysql.php');

class Model
{
	private $db;
	private $validate;

	function __construct() 
	{
		$this->db = new Mydb();
		if(!$this->db)
			echo $this->db->lastErrorMsg();
		
		$this->validate=new Validation();
	}

	public function __destruct()
	{ 
		$this->db->close();
	}

	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		$valid=true;
		if(!$this->validate->str($station))
			$valid=false;
		if(!$this->validate->str($sensor_name))
			$valid=false;
		if(!$this->validate->time($time))
			$valid=false;
		if(!$this->validate->value($sensor_value))
			$valid=false;
		
		if($valid)
			$this->db->insert_station_data($station,$sensor_name,$time,$sensor_value);
	}
	
	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$valid=true;
		if(!$this->validate->str($station))
			$valid=false;
		if(!$this->validate->str($status_name))
			$valid=false;
		if(!$this->validate->time($time))
			$valid=false;
		if(!$this->validate->value($status_value))
			$valid=false;
		
		if($valid)
			$this->db->insert_station_aditional_data($station,$status_name,$time,$status_value);
	}
	
	function get_all()
	{
		return $this->db->get_all_data();
	}
	
	function get_station($station)
	{
		if(!$this->validate->str($station))
			return $this->db->get_station($station);
	}

	function get_station_names()
	{
		return $this->db->get_station_names();
	}
}
   
   
	

	
	
	
	
?>