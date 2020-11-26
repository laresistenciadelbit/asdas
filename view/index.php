<?php
if (!defined('FROM_INDEX')) die();
?><!DOCTYPE html>
<html>

<?php include_once('view/head.php');?>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<?php include_once('view/nav.php');?>

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="view/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">ASDAS</span>
	  <span style="color:#efefef;font-size:9.5px;position:absolute;left:60px;top:31px;">arduino sim data adquisition system</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="view/dist/img/admin.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Administrador</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Estaciones
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
				<?php
				foreach($stations as $station)
				{
					if(isset($current_station) && $current_station==$station)
						$active_navtreeview="active";
					else 
						$active_navtreeview="";
					
					echo '<li class="nav-item">
					<a href="./index.php?s='.$station.'" class="nav-link '. $active_navtreeview .'">
					  <i class="far fa-circle nav-icon"></i>
					  <p>'.$station.'</p>
					</a>
					</li>';
				}
				?>

            </ul>
          </li>
 
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

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
<?php include_once('view/content_'.$current_view.'.php');?>
	
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
<script src="view/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="view/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="view/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- LODASH-->
<script src="view/plugins/lodash/lodash.min.js"></script>

<!-- ChartJS -->
<script src="view/plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="view/plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="view/plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="view/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="view/plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="view/plugins/moment/moment.min.js"></script>
<script src="view/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="view/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="view/plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="view/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="view/dist/js/adminlte.js"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="view/dist/js/pages/dashboard_mod.js"></script>
<!-- AdminLTE for demo purposes -->
<!--script src="view/dist/js/demo.js"></script-->
</body>
</html>
