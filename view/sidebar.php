  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="view/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">APS</span>
	  <span style="color:#efefef;font-size:9.5px;position:absolute;left:62px;top:34px;">adminlte php skeleton</span>
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
                Categorías
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
				<?php
				foreach($categories as $cat)
				{
					if(isset($current_cat) && $current_cat==$cat)
						$active_navtreeview="active";
					else 
						$active_navtreeview="";
					
					echo '<li class="nav-item">
					<a href="./index.php?p=cat&s='.$cat.'" class="nav-link '. $active_navtreeview .'">
					  <i class="far fa-circle nav-icon"></i>
					  <p>'.$cat.'</p>
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