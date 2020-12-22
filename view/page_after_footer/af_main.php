<?php
if (!defined('FROM_INDEX')) die();

if($use_map)
	echo '<script src="view/dist/plugins/leaflet/leaflet.js"></script>
		  <script src="view/dist/js/pages/main_map.js"></script>
		  <script>let map_content;</script>';
?>

<script>//var unsorted_data=<?/*=$unsorted_data*/?>;

</script>

<script src="view/dist/js/pages/main_functions.js"></script>
<script src="view/dist/js/pages/main.js"></script>

<script>
//ejecutamos las funciones del main (4 cajas de información, 2 gráficas, mapa, calendario y 3 círulos de información)
main(true,"<?=$config['fm']?>","","2020-11-30"/*moment(date).format('YYYY-MM-DD')*/,<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>");

//creamos un evento para escuchar el día del calendario
$('#calendar').on("change.datetimepicker", ({date, oldDate}) => {
	main(true,"<?=$config['fm']?>","",moment(date).format('YYYY-MM-DD'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>");
});

//creamos un evento para escuchar el mes del calendario
$('#calendar').on("update.datetimepicker", ({change, viewDate }) => {
	main(false,"<?=$config['fm']?>",moment(viewDate).format('YYYY-MM'),"",<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>");
});
</script>
