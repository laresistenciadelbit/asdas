<?php

/*	ASDAS - Arduino Sim Data Adquisition System
-Adminlte 3.1.0 con bootstrap v4.5.3. 
-Librerías añadidas: leaflet, moment y tempusdominus(locale:es)
-Por detrás php con un modelo simple personal tipo modelo-vista-controlador
-Por delante javascript procesa todos los datos que recibe de la base de datos a través de php
*/

define('FROM_INDEX', true);

define('DATABASE_TYPE', 'sqlite'); // <- posible options: mysql, sqlite

include_once('controller/index.php');

include_once('view/index.php');


?>