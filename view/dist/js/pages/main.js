/*
	function main(daily_charts,fm,to_month,to_date,online_threshold_minutes)
	\	daily_charts			 -> boolean -> verdadero si el gráfico es de un día y no de un mes
	\	fm						 -> numeric -> frecuencia de muestreo: cada cuantos minutos leemos un dato para dibujarlo en la gráfica (si no, hará la mediana de el tiempo entre muestras que recibe el primer sensor que obtenga.
	\	to_month				 -> string  -> fecha de la que tomaremos el mes para mostrarlo en la gráfica (si no, será el mes actual)
	\	to_date					 -> string  -> fecha de la que tomaremos el día para mostrarlo en la gráfica (si no, será el día actual)
	\	online_threshold_minutes -> numeric -> minutos de margen para declarar si una estación está online o no (es decir, si recibimos datos cada 5 minutos, lo suyo es ponerle el límite ligeramente mayor de 5 mintuos.
*/
function main(daily_charts,fm,to_month,to_date,online_threshold_minutes,primary_sensor,primary_status,current_station,sensor_maps) {
  'use strict'

	var unsorted_data;
	/* -- Date management -- */ 
	var date_now;	//día y hora actual, a menos que seleccionemos una diferente
	var ajax_date;
	if(to_month!='')
	{
		date_now=new Date(to_month);
		date_now=moment(date_now).add(date_now.getTimezoneOffset(), 'minutes').toDate(); //arreglo de tiempo sumado ya que el constructor de Date() no es consistente con el formato YYYY-MM
		ajax_date=to_month;//moment(date_now).format('YYYY-MM');
	}
	else
	{
		if(to_date!='')
		{
			date_now=new Date(to_date);
			date_now=moment(date_now).add(date_now.getTimezoneOffset(), 'minutes').toDate(); //arreglo de tiempo sumado ya que el constructor de Date() no es consistente con el formato YYYY-MM-DD
		}
		else
			date_now=new Date();
		
		ajax_date=moment(date_now).format('YYYY-MM-DD');
	}
	
	
	// <!!!>The Calender (Tempus Dominus) //necesario antes del get de ajax, sino, literalmente ejecutaría los listeners del calendario, ya que el get se ejecuta de forma asínrona
	$('#calendar').datetimepicker({
		locale: moment.locale('es'), //hay que ejecutar el js del locale deseado (https://tempusdominus.github.io/bootstrap-4/Options/#locale)
		format: "YYYY-MM-DD",
		date: moment(),
		inline: true
	});
  
	
	//ponemos gif de carga y realizamos petición de datos por ajax
	$(".loading").show();
	
	$.get( "index.php", { d: ajax_date, s: current_station } ).done(
		function( unsorted_data ) {
//console.log(unsorted_data);
		unsorted_data=JSON.parse( unsorted_data );	
		if(unsorted_data.length==0)
		{
			$("#boxes").hide();
			$(".card-with-data").hide();
			$(".loading").fadeOut(800);
			alert("No hay datos recogidos para esa fecha");
			return;
		}
		else
		{
			$("#boxes").fadeIn(800);
			$(".card-with-data").fadeIn(800);
		}

	//mapeamos todos los valores necesarios
//console.log(sensor_maps);
	var x;
	for(var i=0;i<sensor_maps.length;i++)
	{
		if( sensor_maps[i].sensor_map != null && sensor_maps[i].sensor_map != "" )
		{
			for(var j=0;j<unsorted_data.length;j++)
			{
				if(unsorted_data[j].sensor_name==sensor_maps[i].sensor_name)
				{
					x=unsorted_data[j].sensor_value;	//usaremos x en la fórmula
//if(j==1)console.log(unsorted_data[j].sensor_value);
					unsorted_data[j].sensor_value=eval(sensor_maps[i].sensor_map);
//if(j==1)console.log(unsorted_data[j].sensor_value);
				}
			}
		}
	}

	//destruímos las gráficas antes de regenerarlas
	Chart.helpers.each(Chart.instances, function(instance){	//recogemos las posibles instancias que haya de gráficas y las destruímos (solo las 2 que vamos a rehacer)
		if(instance.canvas.id=="sensors-chart-canvas" || instance.canvas.id=="line-chart")
			instance.destroy();
	})
			
			
	if( primary_sensor=="" || primary_sensor===null )	//si no le hemos pasado un sensor principal cogemos el primero que encontremos
		primary_sensor=unsorted_data[0].sensor_name;
	if( primary_status=="" || primary_status===null || primary_status=="_ALL_" )	//si no le hemos pasado un sensor principal, cogemos el primero que encontremos para el knob
		var primary_status_for_knob=unsorted_data[0].status_name;
	else
		var primary_status_for_knob=primary_status;
//main-status	primary_status_for_knob
	
	if( primary_status=="") //if( primary_status=="" && primary_status!="_ALL_" )	//si no le hemos pasado un sensor principal y no está en una estación concreta, usamos el primero que encontremos
		primary_status=unsorted_data[0].status_name;
	
	var date_online;	//(online en los últimos x minutos (configurable))
	if(online_threshold_minutes>0)
		date_online=moment(date_now).subtract(online_threshold_minutes, 'minutes').toDate();
	else
		date_online= moment(date_now).subtract(15, 'minutes').toDate();	
	
	var date_online_str = moment(date_online).format('YYYY-MM-DD HH:mm:ss'); //date_online.getFullYear()+"-"+date_pad(date_online.getMonth())+"-"+date_pad(date_online.getDate())+" "+date_pad(date_online.getHours())+":"+date_pad(date_online.getMinutes())+":"+date_pad(date_online.getSeconds()) ;

	var station_names=Object.keys(_.chain(unsorted_data).groupBy('station').value())//nombre de estaciones (se usa para caja de estádistica, para el gráfico de estado, y para el mapa)
	var data_by_station=Object.values(_.groupBy(unsorted_data,'station'));//[0][1];	//array de datos ordenado por estaciones (se usa para el gráfico de estado y para el mapa)

	var status_name_list=Object.keys(_.chain(unsorted_data).groupBy('status_name').value());
	var data_by_status=Object.values(_.groupBy(unsorted_data,'status_name'));
	/*----Database management of data----*/

	/* 4 boxes widget */
		//total registered stations
			var total_stations=station_names.length;
		//total online stations 
			var online_stations=_.filter(unsorted_data, function(o) { 
			 if(o.time>date_online_str) return o; } );
			 online_stations=_.groupBy(online_stations,'station');
			 online_stations=Object.keys(online_stations).length;
		//total registered sensors
			var sensors=Object.keys(_.chain(unsorted_data).groupBy('sensor_name').value()).length;
		//total critical level batteries
			var battery_low_buffer=_.filter(unsorted_data,{status_name: "battery"});
			
			battery_low_buffer=_.groupBy(battery_low_buffer,'station');
			
			battery_low_buffer=Object.values(battery_low_buffer);
			
			var battery_low_count=0;
			//var i;
			var BL=[];
			for(var i=0; i<battery_low_buffer.length; i++)
			{
				/*BL[i]=_.findLast(battery_low_buffer[i], function(o) {if(o.time>"2020-08-19 13:40:00") return o; } );*/ // <- con función
				 BL[i]=_.findLast(battery_low_buffer[i], o => o.time>date_online_str)  ; // <- modo acortado
				
				if (typeof BL[i] !== 'undefined')	//si no la encontró no la utiliza
				{
					if( +(BL[i].status_value) < 16)
						battery_low_count++;
				}
			}

	/* sensor graph */
	
	//for the graph we will use "sensor_names","sensor_filtered_data.x" and "sensor_filtered_data.y"
	
	var sensor_names=Object.keys(_.chain(unsorted_data).groupBy('sensor_name').value())
	var sensor_data=Object.values(_.chain(unsorted_data).groupBy('sensor_name').value());

	if(daily_charts)	//charts with daily data
	{
		var sensor_chart_title=moment(date_now).format("LLLL"); //título con el día completo
	
		var sensor_filtered_data=filter_daily_data(sensor_data.length,fm,date_now,sensor_data);
	}
	else	//charts with monthly data
	{
		var sensor_chart_title=moment(date_now).format("MMMM"); //título con el mes
		sensor_chart_title=sensor_chart_title.charAt(0).toUpperCase() + sensor_chart_title.slice(1); 

		var sensor_filtered_data=filter_monthly_data(sensor_data.length,date_now,sensor_data);
	}	
	$("#sensor-chart-title").html( "<i class='fas fa-chart-pie mr-1'></i> Valor medio de sensores en "+sensor_chart_title );
	

	/* status chart */
		if(primary_status=="_ALL_")
			var data_to_filter_in_status=data_by_status;
		else
			var data_to_filter_in_status=data_by_station;

		if(daily_charts)
			var status_filtered_data=filter_daily_data(data_to_filter_in_status.length,fm,date_now,data_to_filter_in_status,primary_status,'status_value');
		else
			var status_filtered_data=filter_monthly_data(data_to_filter_in_status.length,date_now,data_to_filter_in_status,primary_status,'status_value');

	/* 3 knobs */
		//online stations (stations online/offline)
		  $("#online-stations-knob").val(online_stations);
		  
		//avg batery level
		  //obtener la media de todas las baterías.
		  var battery_mean=_.filter(unsorted_data,{status_name: primary_status_for_knob});
		  battery_mean=_.meanBy(battery_mean,(a) => +(a.status_value) );	//+() obligatorio para convertirlo en float !!!
		  $("#battery-knob").val(battery_mean.toFixed(2));
		  $(".main-status").html(primary_status_for_knob);

		//avg main sensor
		  //obtener la media de todos los sensores principales
		  var sensor_mean=_.filter(unsorted_data,{sensor_name: primary_sensor});
		  var sensor_mean=_.meanBy(sensor_mean,(a) => +(a.sensor_value) );
		  $("#sensor-avg-knob").val(sensor_mean.toFixed(2));
		  $(".main-sensor").html(primary_sensor);



//////////////////////////////////////////////////////////////////////////	
  
  /*--4 boxes widget--*/
	if(current_station=="")
	{
		$("#registered-stations").text(total_stations);
		$("#online-stations").text(online_stations);
		$("#registered-sensors").text(sensors);
		$("#low-battery").text(battery_low_count);
	}
	else
	{
		//$(".container-xl").css({"margin-right":"unset"});
		$("#boxes").hide();
	}

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.card-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  })
  $('.connectedSortable .card-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move')

/* jQueryKnob */
$('.knob').knob()
/* OSM MAP  */
if(use_map)
{
	//render_map(/*true*/);
	
	//obtenemos coordenadas
	
	var station_locations=new Array(data_by_station.length);
	
	for(var i=0;i<data_by_station.length;i++)
	{
		station_locations[i]=new Array();
		var station_by_time=_.groupBy(data_by_station[i],"time");
		var station_by_time_values=Object.values(station_by_time);
		var date_aux=Object.entries(station_by_time)[Object.entries(station_by_time).length - 1][0];	//tomamos la última fecha del vector
//console.log(" "+i+" : ");	
//console.log(station_by_time);	

		if(daily_charts)
		{
			var start_day_str = calculate_day_start_time( date_now,date_now.getDate() );
			var end_day_str   = calculate_day_end_time  ( date_now,date_now.getDate() );
		} else {
			var start_day_str = calculate_day_start_time( date_now,1 );
			var end_day_str   = calculate_day_end_time  ( date_now, daysInMonth( date_now.getMonth(),date_now.getFullYear() ) );
		}
//		station_by_time=_.filter(station_by_time, function(o) { console.log(o); if( Object.values(o[0])>start_day_str && Object.values(o[0])<end_day_str ) return o; } );

//console.log(station_by_time);
		for(var j=0;j<Object.keys(station_by_time).length;j++)
		{
//			console.log( "start time: "+start_day_str+" / object time: "+ Object.keys(station_by_time)[j]  );
			if( !(start_day_str < Object.keys(station_by_time)[j] && end_day_str > Object.keys(station_by_time)[j]) )
				station_by_time_values[j]='';//delete station_by_time[j];
		}
//console.log(station_by_time);		
//console.log(station_by_time_values);		
		for(var j=0;j<station_by_time_values.length;j++)
		{
			var lat_found=false;
			var lon_found=false;
			var lat_aux;
			var lon_aux;
			
			for(var k=0; k<station_by_time_values[j].length && (!lat_found || !lon_found); k++)
			{
				if(typeof station_by_time_values[j][k] !== "undefined")
				{
					if(station_by_time_values[j][k].status_name=="lat")
					{
						lat_found=true;
						lat_aux=station_by_time_values[j][k].status_value;
						date_aux=station_by_time_values[j][k].time;
					}
					if(station_by_time_values[j][k].status_name=="lon")
					{
						lon_found=true;
						lon_aux=station_by_time_values[j][k].status_value;
						date_aux=station_by_time_values[j][k].time;
					}
				}
			}
			
			if(lat_found && lon_found){	//metemos la fecha en un array junto con la ubicación
				station_locations[i][station_locations[i].length]=[date_aux,lat_aux,lon_aux];
			}
		}
	}
	
	map_content=new Array(station_locations.length); //almacenaremos marcadores y rutas aquí
//console.log(station_locations);
	//dibujamos marcadores y rutas con las coordenadas obtenidas
	for(var i=0;i<station_locations.length;i++)
	{
		map_content[i]=new Object();
		if(station_locations[i].length > 0)
		{
			//https://leafletjs.com/reference-1.7.1.html#marker
			var popup_marker_message="<center><b>"+station_names[i]+"</b><br>"+station_locations[i][station_locations[i].length-1][0]+"</center>";
			map_content[i].marker=new L.Marker([ station_locations[i][station_locations[i].length-1][1], station_locations[i][station_locations[i].length-1][2] ], {title:station_names[i]}, {opacity:0.8} ).bindPopup(popup_marker_message).openPopup();//.addTo(leaflet_map);	//tomamos el último array [station_locations[i].length-1] de cada estación, ya que es el elemento con la última actualización de ubicación
			
			//date: station_locations[i][0]
			//name: station_names[i]
			
			if(station_locations[i].length>1)
			{
				var point_list=new Array();
				for(var j=0;j<station_locations[i].length;j++)
				{
					point_list[j]=new L.LatLng(station_locations[i][j][1],station_locations[i][j][2]);
				}
				var randcolor = '#'+Math.random().toString(16).substr(2,6); //https://stackoverflow.com/questions/1484506/random-color-generator
				map_content[i].polyline=new L.Polyline( point_list,{color:randcolor,weight:3,opacity:0.7,smoothFactor:1} ).addTo(leaflet_map);
			}
		}
	}
	
	render_map(map_content);
}


/* Chart.js Charts */

// <!!!>Sensors chart
	///////usamos "sensor_names[]", "sensor_filtered_data.x" y "sensor_filtered_data.y[]"
  var sensorsChartCanvas = document.getElementById('sensors-chart-canvas').getContext('2d');
  var sensorsChartData = generate_chart(sensor_filtered_data.x);
  
  //añadimos los datos a la gráfica de sensores
  for(i=0;i<sensor_names.length;i++){
	  sensorsChartData.addToChart(sensor_names[i],sensor_filtered_data.y[i],"area");
  }
  
  var sensorsChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: true
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : false,
        }
      }],
      yAxes: [{
        gridLines : {
          display : false,
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  var sensorsChart = new Chart(sensorsChartCanvas, { 
      type: 'line', 
      data: sensorsChartData, 
      options: sensorsChartOptions
    }
  )


 // <!!!> Stats graph chart
  var statsGraphChartCanvas = $('#line-chart').get(0).getContext('2d');
  var statsGraphChartData = generate_chart(status_filtered_data.x);

  //añadimos los datos a la gráfica de estado
	if(current_station=="")
	{
		for(i=0;i<station_names.length;i++)
			statsGraphChartData.addToChart(station_names[i],status_filtered_data.y[i],"line");
	} else {
		for(i=0;i<status_name_list.length;i++)
			statsGraphChartData.addToChart(status_name_list[i],status_filtered_data.y[i],"line");
	}
  
  var statsGraphChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: true,
	  labels: {
		fontColor: '#efefef' //https://www.chartjs.org/docs/latest/general/fonts.html
      }
    },
    scales: {
      xAxes: [{
        ticks : {
          fontColor: '#efefef',
        },
        gridLines : {
          display : false,
          color: '#efefef',
          drawBorder: false,
        }
      }],
      yAxes: [{
        ticks : {
          stepSize: 5000,
          fontColor: '#efefef',
        },
        gridLines : {
          display : true,
          color: '#efefef',
          drawBorder: false,
        }
      }]
    }
  }

  // This will get the first returned node in the jQuery collection.
  var statsGraphChart = new Chart(statsGraphChartCanvas, { 
      type: 'line', 
      data: statsGraphChartData, 
      options: statsGraphChartOptions
    }
  )
			$(".loading").fadeOut(800);//.hide();
		}
	);
	
	

}//)