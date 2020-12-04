<?php
if (!defined('FROM_INDEX')) die();
?>

<section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="registered-stations"></h3>
				
                <p>Estaciones registradas</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="online-stations"></h3>

                <p>Estaciones online</p>
              </div>
              <div class="icon">
                <i class="ion ion-wifi"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="registered-sensors"></h3>

                <p>Sensores registrados</p>
              </div>
              <div class="icon">
                <i class="ion ion-thermometer"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="low-battery"></h3>

                <p>Baterías en nivel crítico</p>
              </div>
              <div class="icon">
                <i class="ion ion-battery-low"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title" id="sensor-chart-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Valor medio de Sensores
                </h3>
                <div class="card-tools">
					  <button type="button"
							  class="btn btn-sm"
							  data-card-widget="collapse"
							  title="Collapse">
						<i class="fas fa-minus"></i>
					  </button>
					  <button
							  type="button"
							  class="btn btn-sm"
							  data-card-widget="maximize"
							  title="maximize">
						<i class="fas fa-expand"></i>
					  </button>
					</div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="sensors-chart-canvas" height="300" style="height: 300px;"></canvas>                         
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="280" style="height: 300px;"></canvas>                         
                  </div>  
                </div>
              </div><!-- /.card-body -->
            </div>
			
			<!-- STATS GRAPH -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Gráfico de estado de <?=$config['primary_status']?>
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
				  <button type="button" class="btn bg-info btn-sm" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <input id="online-stations-knob" type="text" class="knob" data-readonly="true" value="" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Estaciones online</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input id="battery-knob" type="text" class="knob" data-readonly="true"         value="" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Valor medio total de batería</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input id="sensor-avg-knob" type="text" class="knob" data-readonly="true"      value="" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Valor medio total de sensores pricipales</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
			
          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- MAP -->
			
			<div class="card bg-gradient-primary" style="background:#125;">
				<div class="card-header border-0">
					<h3 class="card-title">
					  <i class="fas fa-map-marker-alt mr-1"></i>
					  Mapa de estaciones
					</h3>
					<div class="card-tools">
					  <button type="button"
							  class="btn btn-primary btn-sm"
							  data-card-widget="collapse"
							  title="Collapse">
						<i class="fas fa-minus"></i>
					  </button>
					  <button 
							  id="map_maximize"
							  type="button"
							  class="btn btn-primary btn-sm"
							  data-card-widget="maximize"
							  title="maximize">
						<i class="fas fa-expand"></i>
					  </button>
					</div>
				</div>
				<div class="card-body">
					<!-- OSM MAP -->
<?php //https://wiki.openstreetmap.org/wiki/Deploying_your_own_Slippy_Map
		//https://leafletjs.com/
if($use_map)
	echo '					<div id="map_osm" style=""></div>'.PHP_EOL;
?>
				</div>
			</div>

            <!-- Calendar -->
            <div class="card bg-gradient-success">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- colapse and close button -->
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->