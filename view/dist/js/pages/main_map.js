$("#map_osm").height("300px").width("100%");
var map_coordinates=[39.469864, -0.376361];
var use_map=true;
var leaflet_map = L.map("map_osm");

function render_map(map_content)
{
//	if(!init)	//re-renderizamos al pulsar el botón si no es el renderizado inicial (ahora lo renderizamos siempre para que al cambiar el calendario renueve los marcadores de ubicación del mapa
//	{
		if(	!$("#map_maximize").children(".fas").hasClass("fa-expand") )
			$("#map_osm").height($(window).height()*0.9).width($(window).width()*0.9);
		else
			$("#map_osm").height("300px").width("100%");
		
		leaflet_map.remove();
		leaflet_map = new L.map("map_osm");
//	}
	
	setTimeout(() => { //me ha obligado a darle un timeout de 10ms porque si no, al redimensionar la ventana carga antes el cambio de vista de coordenadas que las dimensiones de la ventana y la vista se centra en un lugar diferente

		leaflet_map.setView(map_coordinates, 12);
		
		//https://geopois.com/blog/leaflet/leaflet-tiles	
		
		var layer = new L.TileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png");
		//var layer = new L.TileLayer("https://tile.thunderforest.com/pioneer/{z}/{x}/{y}.png?apikey=e10b1ea5f4ed400f83e2ee5aeeb006f7");
		//var layer = new L.TileLayer("https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}");
		//var layer = new L.TileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png");
		//var layer = new L.TileLayer("https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png");
		
		leaflet_map.addLayer(layer);
		
		
		for(var i=0;i<map_content.length;i++)
		{
			if( map_content[i].hasOwnProperty('marker') )
				map_content[i].marker.addTo(leaflet_map);
			if( map_content[i].hasOwnProperty('polyline') )
				map_content[i].polyline.addTo(leaflet_map);
		}//console.log(map_content);
		
	
	}, 10);
}

$(document).on('click', '#map_maximize', function() { render_map(map_content); });