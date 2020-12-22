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
		if(!$this->validate->v_str($station))
			$valid=false;
		else
			$station=htmlspecialchars($station);
		if(!$this->validate->v_str($sensor_name))
			$valid=false;
		else
			$sensor_name=htmlspecialchars($sensor_name);
		if(!$this->validate->v_time($time))
			$valid=false;
		if(!$this->validate->v_value($sensor_value))
			$valid=false;
		//else	(no es necesario)
		//	$sensor_value=filter_var($sensor_value, FILTER_VALIDATE_FLOAT);
		
		if($valid)
			$this->db->insert_station_data($station,$sensor_name,$time,$sensor_value);
	}
	
	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$valid=true;
		if(!$this->validate->v_str($station))
			$valid=false;
		else
			$station=htmlspecialchars($station);
		if(!$this->validate->v_str($status_name))
			$valid=false;
		else
			$status_name=htmlspecialchars($status_name);
		if(!$this->validate->v_time($time))
			$valid=false;
		if(!$this->validate->v_value($status_value))
			$valid=false;
		
		if($valid)
			$this->db->insert_station_aditional_data($station,$status_name,$time,$status_value);
	}
	
	function get_all($date="")
	{
		if( $this->validate->v_date($date) || $date=='' )
			return $this->db->get_all_data($date);
		else
			return false;
	}
	
	function get_station($date,$station)
	{
		if( $this->validate->v_str($station) && ( $this->validate->v_date($date) || $date=='' ) )
			return $this->db->get_all_data($date,htmlspecialchars($station));
		else
			return false;
	}

	function get_station_names()
	{
		return $this->db->get_station_names();
	}
	
	function save_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status)
	{//if(is_null($online_threshold_minutes)) echo 'WTFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF';die();
		$valid=true;
		$ret=false;
		if(!$this->validate->v_str($pass))
			$valid=false;
		else
			$pass=htmlspecialchars($pass);
		if(!$this->validate->v_onetothousand($fm) && $fm!="" )
			$valid=false;
//if($fm="") $fm=NULL;
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
			$ret=$this->db->set_config($pass,$fm,$online_threshold_minutes,$primary_sensor,$primary_status);
		
		return $ret;
	}
}
   
   
	

	
	
	
	
?>