<?php
if (!defined('FROM_INDEX')) die();
?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?=$current_page?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google Font: Source Sans Pro -->
  <!-- link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"-->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="view/dist/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="view/dist/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="view/dist/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="view/dist/plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="view/dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="view/dist/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="view/dist/plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="view/dist/plugins/summernote/summernote-bs4.min.css">
  
<?php
	if($use_map)
	{
		echo '<link rel="stylesheet" href="view/dist/plugins/leaflet/leaflet.css">';
	}
?>
  
<?php 
	/*--LOAD CSS FROM THE CURRENT VIEW (if they exist)--*/
	$current_view_css_file='view/dist/css/pages/'.$current_view.'.css';
	if(is_file($current_view_css_file))
		echo '<link rel="stylesheet" href="'.$current_view_css_file.'">';
?>
</head>