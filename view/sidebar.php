  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="view/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">ASDAS</span>
	  <span style="color:#efefef;font-size:9.5px;position:absolute;left:62px;top:34px;">arduino sim data acquisition system</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <a href="./index.php?p=admin"><img src="view/dist/img/gear.png" class="img-circle elevation-2" alt="User Image"></a>
        </div>
        <div class="info">
          <a href="./index.php?p=admin" class="d-block">Administración</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item menu-open">
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
					<a href="./index.php?p=station&s='.$station.'" class="nav-link '. $active_navtreeview .'">
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