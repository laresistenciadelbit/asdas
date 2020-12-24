<?php
if (!defined('FROM_INDEX')) die();

class Validation
{
	function v_str($s)
	{
		if(strlen($s<40))
			return true;
		else 
			return false;
	}
	
	function v_time($t)
	{
		if( strlen($t)==20 && preg_match("#^[0-9\/\,\+\:]+$#", $t) )	//formato: "20/09/20,04:23:58+08" el 08 es el timezone en cuartos de hora, así que hay que dividirlo entre 4 para que sea el equivalente a 1 hora, así obtendríamos nuestro +2 oficial (pero hay que recordar que la hora ya está con el +2 incluído, así que tal vez debiéramos restarle el +2 a la hora para sumarselo en el servidor al huso horario configurado en él)
			return true;
		else 
			return false;
	}
	
	function v_date($d)
	{
		if( strlen($d)<=10 && preg_match("#^[0-9\-]+$#", $d) )	//formato: "2020/09/20" O "2020/09"
			return true;
		else 
			return false;
	}

	function v_value($v)
	{
		if ( filter_var($v, FILTER_VALIDATE_FLOAT) )
			return true;
		else 
			return false;
	}
	
	function v_onetothousand($n)
	{
		if( $n>0 && $n<=1000 )
			return true;
		else 
			return false;
	}
}