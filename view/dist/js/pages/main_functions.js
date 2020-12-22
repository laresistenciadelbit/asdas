//daysInMonth -> calcula el total de números del mes -> lo usaremos tanto en la gráfica de estados como en la de sensores si elegimos la vista mensual	
function daysInMonth (month, year) { return new Date(year, month+1, 0).getDate(); }	//https://stackoverflow.com/questions/1184334/get-number-days-in-a-specified-month-using-javascript	


function generate_chart(x_axis_chart)
{
  return {
    labels  : x_axis_chart,
    datasets: [],	
	addToChart: function (c_name,c_val,type) // (https://stackoverflow.com/questions/14234646/adding-elements-to-object , (https://medium.com/javascript-in-plain-english/javascript-basics-objects-object-manipulation-82fc9d39db06)
	{
		var a1=Math.floor(Math.random() * 256);	var b1=Math.floor(Math.random() * 256);	var c1=Math.floor(Math.random() * 256);
		var a2=Math.floor(Math.random() * 256);	var b2=Math.floor(Math.random() * 256);	var c2=Math.floor(Math.random() * 256);
		var element;
		
		if (type=="area")
		{
			element={
			label               : c_name,
			backgroundColor     : 'rgba('+a1+','+b1+','+c1+', 1)',
			borderColor         : 'rgba('+a1+','+b1+','+c1+', 1)',
			pointRadius         : true,
			pointColor          : 'rgba('+a2+','+b2+','+c2+', 1)',
			pointStrokeColor    : '#c1c7d1',
			pointHighlightFill  : '#fff',
			pointHighlightStroke: 'rgba('+a2+','+b2+','+c2+',1)',
			data                : c_val
			}
		}else{
			element={
			label               : c_name,
			fill                : false,
			borderWidth         : 2,
			lineTension         : 0,
			spanGaps            : true,
			borderColor         : 'rgba('+a1+','+b1+','+c1+', 1)',
			pointRadius         : 3,
			pointHoverRadius    : 7,
			pointColor          : 'rgba('+a2+','+b2+','+c2+', 1)',
			pointBackgroundColor: 'rgba('+a1+','+b1+','+c1+', 1)',
			data                : c_val
			}
		}
		this.datasets.push(element);
	},
  }	
}

//date_pad(n) -> convierte la fecha al formato de la base de datos (ej: 2020-08-03 en vez de 2020-8-3 )
//https://stackoverflow.com/questions/3605214/javascript-add-leading-zeroes-to-date
function date_pad(n) { return String("0" + n).slice(-2); }

//devuelve la fecha con la hora de inicio de ese día y del fin de ese día
function calculate_day_start_time(cdate,cday){	return moment( cdate.getFullYear().toString()+'-'+date_pad(cdate.getMonth()+1)+'-'+date_pad(cday) ).format('YYYY-MM-DD HH:mm:ss'); }
function calculate_day_end_time(cdate,cday)  {	return moment( cdate.getFullYear().toString()+'-'+date_pad(cdate.getMonth()+1)+'-'+date_pad(cday) ).add(1, 'days').subtract(1, 'seconds').format('YYYY-MM-DD HH:mm:ss'); }

//type y status_type es lo que va a definir si obtenemos los datos del sensor o del estado de la estación
function filter_monthly_data(type,total_elements,date_now,data,status_type)
{
	var avg_output=new Array(total_elements);
	//generamos un vector con los días del mes actual
	var days_in_this_month=new Array( daysInMonth(date_now.getMonth(),date_now.getFullYear()) ).fill(1).map( (_, i) => i+1 );	//generamos un vector del día 1 al día final del mes
	
	for(var i=0;i<total_elements;i++)
	{
		avg_output[i] = new Array(days_in_this_month.length); //vamos redimensionando el array mientras lo rellenamos
		for(var j=0;j<days_in_this_month.length;j++)
		{
			//calculamos los valores para obtener los datos entre un día y el siguiente
			var start_day_str = calculate_day_start_time(date_now,days_in_this_month[j]);//moment( date_now.getFullYear().toString()+'-'+date_pad(date_now.getMonth()+1)+'-'+date_pad(days_in_this_month[j]) ).format('YYYY-MM-DD HH:mm:ss');
			var end_day_str   = calculate_day_end_time  (date_now,days_in_this_month[j]);//moment( date_now.getFullYear().toString()+'-'+date_pad(date_now.getMonth()+1)+'-'+date_pad(days_in_this_month[j]) ).add(1, 'days').subtract(1, 'seconds').format('YYYY-MM-DD HH:mm:ss');
			//tomamos los datos desde el principio al final del día
			var current_day_data=_.filter(data[i], function(o) {if( o.time>start_day_str && o.time<end_day_str ) return o; } );
			//var current_day_data=_.filter(data[i], function(o) {if( (o.time>start_day_str && o.time<end_day_str) || (o.time>start_day_str && o.time<end_day_str && type=='status_value' && o.status_name==status_type ) ) return o; } );
			
			//si sacamos el estado filtramos por tipo de estado
			if(type=='status_value'){
				current_day_data=_.filter(current_day_data, function(o) {if( o.status_name==status_type ) return o; } );
			}
			
			//filgramos para tomar solo los valores de los sensores
			var sensor_values_current_day=_.groupBy(current_day_data,type);
			//tomamos solo los números, los transformamos a formato numérico y calculamos su media en ese día
			var tmp_avg=_.mean(Object.keys(sensor_values_current_day).map(Number)).toFixed(2);
			if(  !isNaN(tmp_avg) )
				avg_output[i][j]=tmp_avg;
		}
	}
	return {x:days_in_this_month , y:avg_output};
}

function filter_daily_data(total_elements,fm,date_now,sensor_data)
{
	var avg_output=new Array(total_elements);
	var mode_minutes;
	
	if(fm)	//si le hemos pasado la frecuencia de muestreo (cada cuantos minutos va a tomar un valor) usamos este dato
	{
		mode_minutes=fm;
	}
	else	//si no, sacamos la moda de espacio de tiempo entre cada medida para tomarla como referencia
	{
		var data_times_arr=Object.keys(_.groupBy(sensor_data[0],'time'));
		var data_times_numeric_arr=new Array(data_times_arr.length);
		for(var i=0;i<data_times_arr.length-1;i++)
			data_times_numeric_arr[i]=Math.round( (new Date(data_times_arr[i+1])).getTime()/1000/60 - (new Date(data_times_arr[i])).getTime()/1000/60 );//tomamos todo el tiempo en milisegundos para restarlo, después lo convertimos a minutos
		//calculamos la moda con los valores obtenidos (https://stackoverflow.com/questions/49731282/the-most-frequent-item-of-an-array-using-lodash)
		mode_minutes = _.head(_(data_times_numeric_arr).countBy().entries().maxBy(_.last));
	}
	//muestras en los minutos del día divididos entre el intervalo entre mediciones (a una medición por minuto tenemos 1 muestra por minuto, que son 1440 muestras en un día)

	var samples = (60*24) / mode_minutes;
	//recogemos valores de cada muestra desde el día anterior (suele ser cada minuto,2minutos,5minutos,15minutos por eso lo llamamos minutes_from_last_day
	var minutes_from_last_day=new Array(samples);
	var date_aux=moment(date_now);
	
	for(var i=(samples)-1;i>=0;i--)
	{
		minutes_from_last_day[i]=date_aux.format('HH:mm');
		date_aux=date_aux.subtract(mode_minutes,'minutes');
	}

	for(var i=0;i<total_elements;i++)
	{
		avg_output[i] = new Array(minutes_from_last_day.length); //vamos redimensionando el array mientras lo rellenamos
		for(var j=0;j<minutes_from_last_day.length;j++)
		{
			//calculamos los valores para obtener los datos entre una muestra y la siguiente
			var start_min = moment( date_now.getFullYear().toString()+'-'+date_pad(date_now.getMonth()+1)+'-'+date_pad(date_now.getDate())+' '+minutes_from_last_day[j] ).format('YYYY-MM-DD HH:mm:ss');
			var end_min   = moment( date_now.getFullYear().toString()+'-'+date_pad(date_now.getMonth()+1)+'-'+date_pad(date_now.getDate())+' '+minutes_from_last_day[j] ).add(mode_minutes, 'minutes').subtract(1, 'seconds').format('YYYY-MM-DD HH:mm:ss');
			//tomamos los datos entre el comienzo de esta muestra y la anterior
			var current_sample_data=_.filter(sensor_data[i], function(o) {if(o.time>start_min && o.time<end_min ) return o; } );
			//filgramos para tomar solo los valores de los sensores
			var sensor_values_current_sample=_.groupBy(current_sample_data,'sensor_value');
			//tomamos solo los números, los transformamos a formato numérico y calculamos su media en esa muestra
			var tmp_avg=_.mean(Object.keys(sensor_values_current_sample).map(Number));
			if(  !isNaN(tmp_avg) )
				avg_output[i][j]=tmp_avg;
		}
	}
	return {x:minutes_from_last_day , y:avg_output};
}