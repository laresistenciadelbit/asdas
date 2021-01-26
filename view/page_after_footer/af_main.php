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
var current_station='<?=$current_station?>';

//ejecutamos las funciones del main (4 cajas de información, 2 gráficas, mapa, calendario y 3 círulos de información)
//main(true,"<?=$config['fm']?>","","2020-11-30",<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>",current_station);
main(true,"<?=$config['fm']?>","",moment(new Date()).format('YYYY-MM-DD'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>",current_station);

//creamos un evento para escuchar el día del calendario
$('#calendar').on("change.datetimepicker", ({date, oldDate}) => {
	main(true,"<?=$config['fm']?>","",moment(date).format('YYYY-MM-DD'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>",current_station);
});

//creamos un evento para escuchar el mes del calendario
$('#calendar').on("update.datetimepicker", ({change, viewDate }) => {
	main(false,"<?=$config['fm']?>",moment(viewDate).format('YYYY-MM'),"",<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>",current_station);
});


//actualizamos cada minuto si en el calendario está seleccionado el día actual y estamos en una estación concreta
if(current_station!="")
{
	var picked_date;
	$(document).ready(function(){
		var myVar = setInterval(myTimer, 60000);
		function myTimer() 
		{
			picked_date=$("#calendar").find(".day.active").attr("data-day");
			if (typeof picked_date !== 'undefined')//console.log(moment(new Date()).format('DD/MM/YYYY') +" "+ picked_date);
				if( moment(new Date()).format('DD/MM/YYYY') == picked_date )
					main(true,"<?=$config['fm']?>","",moment(new Date()).format('YYYY-MM-DD'),<?=$config['online_threshold_minutes']?>,"<?=$config['primary_sensor']?>","<?=$config['primary_status']?>",current_station);
		}
	})
}

</script>
