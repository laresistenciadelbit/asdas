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

	function validate_station_data($station,$setting_name,$time,$setting_value)
	{
		$valid=true;
		if(!$this->validate->v_str($station))
			$valid=false;
		else
			$station=htmlspecialchars($station);
		if(!$this->validate->v_str($setting_name))
			$valid=false;
		else
			$setting_name=htmlspecialchars($setting_name);
		if(!$this->validate->v_time($time))
			$valid=false;
		if(!$this->validate->v_value($setting_value))
			$valid=false;
		
		return $valid;
	}

	function insert_station_data($station,$sensor_name,$time,$sensor_value)
	{
		$valid=validate_station_data($station,$sensor_name,$time,$sensor_value);
		
		if($valid)
			return $this->db->insert_station_data($station,$sensor_name,$time,$sensor_value);
		else
			return FAIL_RETURN;
	}

	function insert_station_aditional_data($station,$status_name,$time,$status_value)
	{
		$valid=validate_station_data($station,$status_name,$time,$status_value);
		
		if($valid)
			return $this->db->insert_station_aditional_data($station,$status_name,$time,$status_value);
		else
			return FAIL_RETURN;
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
			return $this->db->get_all_data($date,utf8_decode(htmlspecialchars($station)));	//importante el utf8_decode en base de datos sqlite, ya que sqlite en php no admite una conexión con codificación utf8
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