<?php
if (!defined('FROM_INDEX')) die();
?>

<div id="cabecera">	
	<h1 id="logo">
		<img alt="Logo UV" src="view/dist/img/logoUV.svg"></a>
	</h1>
</div>
		
<div id="content-persona">
					<div class="persona-izq">
	
						<img id="persona-foto" class="persona-foto" src="view/dist/img/foto.jpg" alt="foto Noel F. Peral">
						<div class="clear"></div>
	
						<div class="persona-dire name">
							<span id="persona-nom" class="nom">NOEL FERNÁNDEZ PERAL</span><br><span class="afiliacio">Alumno de la Universidad</span>
							<br>	
						</div>	
						<div class="persona-dire"><a id="persona-email" class="mail" href="mailto:noferpe@alumni.uv.es" title="noferpe@alumni.uv.es">noferpe@alumni.uv.es</a>
							<br>
							<a id="persona-webpage" class="homepage" href="https://asdas.diab.website">asdas.diab.website</a>
							<br>
							<a id="persona-blog" class="blog" href="https://laresistenciadelbit.diab.website">laresistenciadelbit.diab.website</a>
							<br>
						</div>	
						<div id="persona-dept" class="persona-dire dept">Área de conocimiento: INGENIERÍA TELEMÁTICA
							<br>Departamento: Informática
						</div>	
						<div class="persona-dire"><span id="persona-localitzacio" class="location">Departament d'Informàtica
E.T.S. d'Enginyeria</span>
							<br><span id="persona-tel" class="phone">(1234) 12345</span><br>	
						</div>	
					</div> <!-- persona-izq -->
				
					<div class="persona-dcha">	
						<div class="bio-titol" onclick="javascript:menuMovil('asig')">Asignaturas de referencia al tfg</div>
						<a href="javascript:menuMovil('asig')"><span class="caja-flecha arasig" style="display: none;"><i class="fa fa-angle-right"></i></span><span class="caja-flecha adasig" style="display: block;"><i class="fa fa-angle-down"></i></span></a>	
						<div class="msasig bio-info" style="display: block;">
							<table id="persona-asignatures" class="asig"><tbody>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34897 - Administració i manteniment de sistemes</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34898 - Gestió de projectes</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34886 - Enginyeria del programari</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34792 - Circuits electrònics</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34895 - Desenvolupament d''aplicacions web </td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34894 - Bases de dades i sistemes d''informació</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34804 - Sistemes electrònics digitals II</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34796 - Programació</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								<tr class="asignatura"><td class="nombre-asignatura" width="70%">34806 - Ampliació d'informàtica</td><td class="tipo-asignatura">Laboratorio, Práctica, Teoría</td></tr>
								
							</tbody></table>
						</div>
						<br>
						<div class="m05"></div>	
						<div class="bio-titol" onclick="javascript:menuMovil('tuto')">Conocimientos, frameworks y tecnologías aplicadas</div>
						<a href="javascript:menuMovil('tuto')"><span class="caja-flecha artuto" style="display: none;"><i class="fa fa-angle-right"></i></span><span class="caja-flecha adtuto" style="display: block;"><i class="fa fa-angle-down"></i></span></a>	
						<div class="mstuto bio-info" style="display: block;">
							<table id="persona-tutories" class="tuto"><tbody>
							<tr class="titulo"><td>Lenguajes</td></tr>
								<tr><td class="dia">C, Php, Javascript, Sql, Json, Html, Css </td></tr>
								<tr class="titulo"><td>Librerías y frameworks</td></tr>
								<tr><td class="dia">Jquery, Ajax, Bootstrap, Adminlte </td></tr>
								<tr class="titulo"><td>Hardware</td></tr>
								<tr><td class="dia">Arduino pro micro, Sim800</td></tr>
							</tbody></table>
						</div>
						<div class="m05"></div>
					</div> <!-- persona-dcha -->	
					<div class="clear"></div>
					<script type="text/javascript">
						function menuMovil(n) {
							$(".ms" + n).toggle();
							$(".ar" + n).toggle();
							$(".ad" + n).toggle();
						}
					</script>
</div>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>