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
	
	function insert($query)
	{
		$ret = $this->query($query);
		if(!$ret) {
			if(DEBUG)
				return $this->error;
			else
				return FAIL_RETURN;
		}
		else
			return CORRECT_RETURN;
	}
	
	function update($query)
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG ) {
			echo $this->error;
		}
		if($ret)
			return true;
		else
			return false;
	}
	
	function select_simple($query) //devuelve un valor
	{
		return $this->querySingle($query);
	}
	
	function select_string_array($query) //devuelve el array de strings, nada más.
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG) {
			echo $this->error;
		}
		
		$array=array();
		while($row = $ret->fetch_assoc())
		{	
			$row=array_map('utf8_encode', $row);
			array_push($array,reset($row));	//https://www.designcise.com/web/tutorial/how-to-get-the-first-element-of-an-array-in-php
		}
		return $array;
	}
	
	function select_row_array($query) //devuelve un array de una fila con el nombre de la columna como índice
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG) {
			echo $this->error;
		}
		while($row = $ret->fetch_assoc() ){$arr=$row;}
		return $arr;	
	}
	
	function select_json_array($query) //devuelve un array en formato json de todo el contenido
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG) {
			echo $this->error;
		}
		$array=array();
		while($row = $ret->fetch_assoc())
		{
			$row = array_map('utf8_encode', $row); // https://stackoverflow.com/questions/20694317/json-encode-function-special-characters
			array_push($array,$row);
		}
		return json_encode($array);
	}
	function day_date_function(){return "DATE_FORMAT(time,'%Y-%m-%d')";}
	function month_date_function() {return "DATE_FORMAT(time,'%Y-%m')";}
}
?>