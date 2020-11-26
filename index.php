<?php
define('FROM_INDEX', true);

define('DATABASE_TYPE', 'sqlite'); // <- posible options: mysql, sqlite

$stations=array("norte","este","sur");//////////////////////////////////---

include_once('controller/index.php');



include_once('view/index.php');


?>