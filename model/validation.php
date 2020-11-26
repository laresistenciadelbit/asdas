<?php
if (!defined('FROM_INDEX')) die();


class Validation
{
	function str($s)
	{
		if(strlen($s<40))
			return true;
		else 
			return false;
	}
	
	function time($t)
	{
		if(strlen($s==20))	//formato: "20/09/20,04:23:58+08" el 08 es el timezone en cuartos de hora, así que hay que dividirlo entre 4 para que sea el equivalente a 1 hora, así obtendríamos nuestro +2 oficial (pero hay que recordar que la hora ya está con el +2 incluído, así que tal vez debiéramos restarle el +2 a la hora para sumarselo en el servidor al huso horario configurado en él)
			return true;
		else 
			return false;
	}

	function value($v)
	{
		if ( filter_var($v, FILTER_VALIDATE_FLOAT) )
			return true;
		else 
			return false;
	}
}