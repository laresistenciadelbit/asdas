<?php
// insert into station_sensors values ('uv','temperatura','2020-08-22 13:05:12',22.87); cada 5 min, variando +-8 grados  +2 si hora > 11 y < 18 ,  -2 si hora >1 y <6
$uv_temp=17;
$uv_co2=38.54;
$uv_batt=70;
$lat_blasco=39.472894;
$lon_blasco=-0.342833;

$lat_pont=39.480490;
$lon_pont=-0.373655;

$date=date_create("2020-12-01 08:05:12");

while( $date->format("Y-m-d H:i:s") < "2021-01-25 08:05:12" )
{
	date_add($date,date_interval_create_from_date_string("30 minutes"));
	
	$positive=0;$negative=0;
	if($date->format("H")>1&&$date->format("H")<6)
		$negative= $date->format("H")/200 + rand(36,42)/100;
	if($date->format("H")>11&&$date->format("H")<18)
		$positive= $date->format("H")/200 + rand(20,28.5)/100;

	$uv_temp+= rand(-10,10)/10 + 0.43*$positive - 0.49*$negative + rand(-50,49.5)/100;
	if($uv_temp>23)
		$uv_temp-=rand(10,28)/10 + ($uv_temp-23)*0.2;
	if($uv_temp<5)
		$uv_temp+=rand(10,28)/10 + 0.2;;
	
	echo "insert into station_sensors values ('uv','temperatura','".$date->format("Y-m-d H:i:s")."',".$uv_temp.");" ;
echo "<br>";
	$uv_co2+=rand(-10.5,10.2)/10+rand(-4.5,5)/100;
	echo "insert into station_sensors values ('uv','co2','".$date->format("Y-m-d H:i:s")."',".$uv_co2.");";
echo "<br>";
	$uv_batt+= rand(-10,9.2)/10 + $positive*1.18 - $negative*1.3 + rand(-50,51)/100;
	if($uv_batt<12)$uv_batt=14+rand(-10,9.2)/10;
	if($uv_batt>96)$uv_batt=93+rand(-10,9.2)/10;
	echo "insert into station_status values ('uv','battery','".$date->format("Y-m-d H:i:s")."',".$uv_batt.");" ;
echo "<br>";
	
	
	echo "insert into station_sensors values ('blasco ibañez','temperatura','".$date->format("Y-m-d H:i:s")."',".( $uv_temp-0.35*(sin( $date->format("H")*4 )) +rand(-10,10)/100 ).");" ;
echo "<br>";
	echo "insert into station_sensors values ('blasco ibañez','co2','".$date->format("Y-m-d H:i:s")."',".($uv_co2+1.85*(sin( $date->format("H")*4 ))+rand(-60,65)/100 ).");";
echo "<br>";
	echo "insert into station_status values ('blasco ibañez','battery','".$date->format("Y-m-d H:i:s")."',".($uv_batt-rand(40,60)/10-36.55*(sin( $date->format("H")*4 ))+rand(-200,140)/100 ).");" ;
echo "<br>";
	
	$lat_blasco+=rand(-10,66) /10000000;
	$lon_blasco+=rand(-195,15)/10000000;
echo "insert into station_status values ('blasco ibañez','lat','".$date->format("Y-m-d H:i:s")."',".$lat_blasco.");";
echo "<br>";
	echo "insert into station_status values ('blasco ibañez','lon','".$date->format("Y-m-d H:i:s")."',".$lon_blasco.");";
echo "<br>";
	
	
	echo "insert into station_sensors values ('puerto','temperatura','".$date->format("Y-m-d H:i:s")."',".( $uv_temp-4.35*(sin( $date->format("H")*4 ))-rand(1,10)/100 ).");" ;
echo "<br>";
	echo "insert into station_sensors values ('puerto','PM10','".$date->format("Y-m-d H:i:s")."',".($uv_co2+22+21.64*(tan( $date->format("H")*4 ))+rand(-10,11)/100+rand(-25,24)/100 ).");";
echo "<br>";
	echo "insert into station_status values ('puerto','battery','".$date->format("Y-m-d H:i:s")."',".($uv_batt+rand(60,120)/10+44.24*(sin( $date->format("H")*4 ))+rand(-240,200)/100 ).");" ;
echo "<br>";




	echo "insert into station_sensors values ('pont de fusta','temperatura','".$date->format("Y-m-d H:i:s")."',".( $uv_temp-2.13*(sin( $date->format("H")*4 ))+rand(1,5)/100 ).");" ;
echo "<br>";
	echo "insert into station_sensors values ('pont de fusta','PM10','".$date->format("Y-m-d H:i:s")."',".($uv_co2+25.84*(sin( $date->format("H")*4 ))+rand(-10,12)/100+rand(-10,10)/100 ).");";
echo "<br>";
	$lat_pont+=rand(-50,30)/10000000;
	$lon_pont+=rand(-40,15)  /10000000;
	echo "insert into station_status values ('pont de fusta','lat','".$date->format("Y-m-d H:i:s")."',".$lat_pont.");";
echo "<br>";
	echo "insert into station_status values ('pont de fusta','lon','".$date->format("Y-m-d H:i:s")."',".$lon_pont.");";
echo "<br>";

	
//break;
}


//echo ;

















?>