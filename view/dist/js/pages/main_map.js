$("#map_osm").height("300px").width("100%");
var map_coordinates=[39.469891, -0.376402];
var use_map=true;
var leaflet_map = L.map("map_osm");

function render_map(init)
{	
	if(!init)	//re-renderizamos al pulsar el botón si no es el renderizado inicial
	{
		if(	$("#map_maximize").children(".fas").hasClass("fa-expand") )
			$("#map_osm").height($(window).height()*0.9).width($(window).width()*0.9);
		else
			$("#map_osm").height("300px").width("100%");
		
		leaflet_map.remove();
		leaflet_map = new L.map("map_osm");
	}
	
	leaflet_map.setView(map_coordinates, 12);			
	var layer = new L.TileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
	//var layer = new L.TileLayer("https://{s}.tiles.mapbox.com/v3/spatial.b625e395/{z}/{x}/{y}.png");
	leaflet_map.addLayer(layer);
}

$(document).on('click', '#map_maximize', function() { render_map(false); });