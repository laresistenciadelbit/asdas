<?php
if (!defined('FROM_INDEX')) die();

if($use_map)
	echo '<script src="view/dist/plugins/leaflet/leaflet.js"></script>
		  <script src="view/dist/js/pages/main_map.js"></script>';
?>

<script>var unsorted_data=<?=$unsorted_data?>;</script>

<script src="view/dist/js/pages/main_functions.js"></script>
<script src="view/dist/js/pages/main.js"></script>

<script>
//ejecutamos las funciones del main (4 cajas de información, 2 gráficas, mapa, calendario y 3 círulos de información)
main(false,"<?=$config['fm']?>","","2020-08-22 13:40:00",<?=$config['online_threshold_minutes']?>,"<?=$config['primary_status']?>");

//creamos un evento para escuchar el calendario
$('#calendar').on("change.datetimepicker", ({date, oldDate}) => {              
	main(true,"<?=$config['fm']?>","",moment(date).format('YYYY-MM-DD HH:mm:ss'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_status']?>");
});

//el evento al seleccionar el mes/año no funciona
$('#calendar').on("update.datetimepicker", ({change, viewDate }) => {              
	main(false,"<?=$config['fm']?>","",moment(viewDate).format('YYYY-MM'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_status']?>");
});

  
</script>
