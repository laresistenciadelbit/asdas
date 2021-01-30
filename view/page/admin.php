<?php
if (!defined('FROM_INDEX')) die();
?>

<?php
if( $config['pass']=='' || ( isset($_GET['pass']) && $_GET['pass']==htmlspecialchars_decode($config['pass']) ) )
{ ?>

<!-- Main content -->
    <section class="content config-margin">
      <div class="row">
        <div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Configuración</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
				<form id="admin-form" action="index.php">
				
				  <div class="form-group">
					<label for="pass">Aplicar contraseña al área de administración</label>
					<input type="text" name="pass" id="pass" class="form-control" value="">
				  </div>
				  <div class="form-group">
					<label for="fm">Tiempo de muestreo entre valores para la gráfica (minutos)</label>
					<p>*Por defecto lo autocalcula usando la moda de intervalos de tiempo del primer sensor que reciba</p>
					<p>*Cuanto más pequeño sea el valor, si no hay datos en ese intervalo tendremos líneas discontinuas</p>
					<p>*Cuanto más grande sea el valor, calculará la media de los valores que no se hayan tomado entre ese intervalo</p>
					<input type="text" name="fm" id="fm" class="form-control" value="<?=$config['fm']?>">
				  </div>
				  <div class="form-group">
					<label for="primary_status">Nombre del sensor principal</label>
					<p>*Se usará para mostrar estadísticas del sensor que elijamos como principal</p>
					<input type="text" name="primary_sensor" id="primary_sensor" class="form-control" value="<?=$config['primary_sensor']?>">
				  </div>
				  <div class="form-group">
					<label for="primary_status">Nombre del  estado principal</label>
					<p>*Se usará para mostrar los datos del tipo de estado que elijamos como principal</p>
					<input type="text" name="primary_status" id="primary_status" class="form-control" value="<?=$config['primary_status']?>">
					
					<!--select id="inputStatus" class="form-control custom-select">
					  <option disabled>Select one</option>
					  <option>On Hold</option>
					  <option>Canceled</option>
					  <option selected>Success</option>
					</select-->
				  </div>
				  <div class="form-group">
					<label for="online_threshold_minutes">Tiempo límite para considerar una estación online (minutos)</label>
					<input type="text" name="online_threshold_minutes" id="online_threshold_minutes" class="form-control" value="<?=$config['online_threshold_minutes']?>">
				  </div>
				  
					<input type="submit" value="Guardar cambios" class="btn btn-success float-right">
					
					<div id="result"></div>
					
					<input type="hidden" name="w" id="w" class="form-control" value="write_config">
				</form>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
		
		
		
		
<?php if('DEBUG' && file_exists(DEBUG_REQUESTS_FILE) ) 
{?>
		<div class="col-md-6">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Log de peticiones web</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
				<?php
					$div_log_block_start='<div style="padding: 10px; border: dotted gray 2px; border-radius:15px; margin-bottom: 10px;">';
					echo $div_log_block_start;
					for($i=sizeof($request_log)-1;$i>=0;$i--)	//lo mostramos en orden inverso
					{
						if( $request_log[$i] == "============================".PHP_EOL."<br>")
						{
							if($i>0)
								echo "</div>".$div_log_block_start;
							else
								echo "</div>";
						}
						else
						{
							if($i==sizeof($request_log)-1)
								echo str_replace("<br>", '', $request_log[$i]);
							else
								echo $request_log[$i];
						}
					}
				?>
				
				
            </div>
          </div>
        </div>
<?php
}?>		
		
	  </div>
	</section>
	
	
<?php
}
else
{
	$err_msg="";
	if( isset($_GET['pass']) && $_GET['pass']!=$config['pass'] )
		$err_msg="<br><b style='color:red;text-align:center;'>Contraseña errónea</b><br>";
	
	?>
	<section class="admin-login">
		<div class="row">
			<form action="index.php">
				<div class="form-group">
							
							<label for="pass">Contraseña de acceso a la administración</label>
							<input type="pass" id="pass" name="pass" class="form-control">
							<input type="hidden" id="p" name="p" value="admin" class="form-control">
							<?=$err_msg?>
				</div>
				<input type="submit" value="Acceder" class="btn btn-success float-right">
			</form>
		</div>
	</section>
	<?php
}
?>