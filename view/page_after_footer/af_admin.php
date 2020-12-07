<?php
if (!defined('FROM_INDEX')) die();


?>

<script>
$("#admin-form").submit(function(event){
	event.preventDefault(); //prevent default action 
	var post_url = $(this).attr("action"); //get form action url
	var form_data = $(this).serialize(); //Encode form elements for submission
	
	$.get( post_url, form_data, function( response ) {
	  $("#result").html( response );
	  $("#result").fadeIn(0).delay(1000).fadeOut(2000);
	});
});

</script>
