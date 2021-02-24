<?php
if (!defined('FROM_INDEX')) die();


?>

<script>
$("#save_config_form").submit(function(event){
	<?php
		if($config['pass']!="")
			echo 'var pwd=prompt("Introduce la contraseña del área de administración:");
				  $(".pass_control").val(pwd);';
	?>
	event.preventDefault(); //prevent default action 
	var post_url = $(this).attr("action"); //get form action url
	var form_data = $(this).serialize(); //Encode form elements for submission
	
	$.get( post_url, form_data, function( response ) {
	  $("#result_save_config").html( response );
	  $("#result_save_config").fadeIn(0).delay(1000).fadeOut(2000);
	});
	
	if( $("#pass").val()!="" )	//recargamos la página si se ha cambiado la contraseña para así pedirla si se realizan cambios en la configuración o el mapeado de sensores
	{
		setTimeout(function() {
			window.location.href="index.php?p=admin";//location.reload(); recargamos, pero sin las variables enviadas salvo la de la página
		},1000);
	}
});
$("#save_mapping_form").submit(function(event){
	<?php
		if($config['pass']!="")
			echo 'var pwd=prompt("Introduce la contraseña del área de administración:");
				  $(".pass_control").val(pwd);';
	?>
	event.preventDefault(); //prevent default action 
	var post_url = $(this).attr("action"); //get form action url
	var form_data = $(this).serialize(); //Encode form elements for submission
	
	$.get( post_url, form_data, function( response ) {
	  $("#result_save_mapping").html( response );
	  $("#result_save_mapping").fadeIn(0).delay(1000).fadeOut(2000);
	});
});

</script>
