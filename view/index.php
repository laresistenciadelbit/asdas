<?php
if (!defined('FROM_INDEX')) die();
?><!DOCTYPE html>
<html>

<?php include_once('view/head.php');?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include_once('view/nav.php');?>

<?php include_once('view/sidebar.php');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"><?=$current_page?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="./index.php">Inicio</a></li>
              <li class="breadcrumb-item active"><?=$current_page?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
<?php include_once('view/page/'.$current_view.'.php');?>
	
  </div>
  <!-- /.content-wrapper -->

<?php include_once('view/footer.php');?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="view/dist/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="view/dist/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="view/dist/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- LODASH-->
<script src="view/dist/plugins/lodash/lodash.min.js"></script>

<!-- ChartJS -->
<script src="view/dist/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="view/dist/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="view/dist/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="view/dist/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="view/dist/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="view/dist/plugins/moment/moment.min.js"></script>
<script src="view/dist/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="view/dist/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="view/dist/plugins/tempusdominus-bootstrap-4/js/lang/es.js"></script>
<!-- Summernote -->
<script src="view/dist/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="view/dist/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="view/dist/js/adminlte.js"></script>

<?php 
	/*--LOAD SCRIPTS FROM THE CURRENT VIEW (if they exist)--*/
	$after_footer_file='view/page_after_footer/af_'.$current_view.'.php';
	if(is_file($after_footer_file))
		include_once($after_footer_file);
?>

</body>
</html>
