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
	private $config;

	function __construct($db_conf) 
	{
		$this->db = new Mydb($db_conf);
		if(!$this->db)
			echo $this->db->lastErrorMsg();
		
		$this->validate=new Validation();
		
		$this->config=$this->db->load_config();	
	}

	public function __destruct()
	{ 
		$this->db->close();
	}

	function get_config()
	{
		return $this->config;
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
	
	function save_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status)
	{//if(is_null($online_threshold_minutes)) echo 'WTFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF';die();
		$valid=true;
		$ret=false;
		if(!$this->validate->str($pass))
			$valid=false;
		if(!$this->validate->onetothousand($fm) && $fm!="" )
			$valid=false;
//if($fm="") $fm=NULL;
		if(!$this->validate->onetothousand($online_threshold_minutes))
			$valid=false;
		if(!$this->validate->str($primary_sensor))
			$valid=false;
		if(!$this->validate->str($primary_status))
			$valid=false;
		
		if($valid)
			$ret=$this->db->set_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status);
		
		if($ret)
			return true;
		else
			return false;
	}
}
   
   
	

	
	
	
	
?>