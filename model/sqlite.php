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
	
	function insert($query)
	{
		$ret = $this->exec($query);
		if(!$ret) {
			if(DEBUG)
				return $this->lastErrorMsg();
			else
				return FAIL_RETURN;
		}
		else
			return CORRECT_RETURN;
	}
	
	function update($query)
	{
		$ret = $this->exec($query);
		if(!$ret && DEBUG ) {
			echo $this->lastErrorMsg();
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
			echo $this->lastErrorMsg();
		}
		
		$array=array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC))
		{
			//$row=array_map('utf8_encode', $row);
			array_push($array,reset($row));	//https://www.designcise.com/web/tutorial/how-to-get-the-first-element-of-an-array-in-php
		}
		return $array;
	}
	
	function select_row_array($query) //devuelve un array de una fila con el nombre de la columna como índice
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG) {
			echo $this->lastErrorMsg();
		}
		while($row = $ret->fetchArray(SQLITE3_ASSOC) ){$arr=$row;}
		return $arr;	
	}
	
	function select_json_array($query) //devuelve un array en formato json de todo el contenido
	{
		$ret = $this->query($query);
		if(!$ret && DEBUG) {
			echo $this->lastErrorMsg();
		}
		$array=array();
		while($row = $ret->fetchArray(SQLITE3_ASSOC))
		{
			//$row = array_map('utf8_encode', $row); // https://stackoverflow.com/questions/20694317/json-encode-function-special-characters
			array_push($array,$row);
		}
		return json_encode($array);
	}
}
?>