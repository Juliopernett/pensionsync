	<?php
		if (isset($con))
		{
		// Realizar la consulta a la base de datos
		$tipo_entidad_query = mysqli_query($con, "SELECT id, descripcion AS DESCRIPCION_TIPO_ENTIDAD FROM PM_TIPO_ENTIDAD");

	?>
	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nueva entidad al sistema</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="guardar_entidad" name="guardar_entidad">
			<div id="resultados_ajax"></div>
			  <div class="form-group">
				<label for="new_nit" class="col-sm-3 control-label">Nit:</label>
				<div class="col-sm-8">
				  <input type="number" class="form-control" id="new_nit" name="new_nit" placeholder="Digite el nit de la entidad (sin .)" required
				  		 min="0" max="999999999"  oninput="validateNit(this)" maxlength="9">
				</div>

				<label for="new_dv" class="col-sm-3 control-label">DV:</label>
				<div class="col-sm-8">
				  <input type="number" class="form-control" id="new_dv" name="new_dv" placeholder="Digite el digito de verificación" required
				         min="0" max="9" maxlength="1" oninput="validateDigit(this)">
				</div>
				
				<label for="new_nombre" class="col-sm-3 control-label">Nombre:</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="new_nombre" name="new_nombre" placeholder="Digite el nombre e la entidad" required>
				</div>

				<label for="new_tipo_entidad" class="col-sm-3 control-label">Tipo entidad:</label>
				<div class="col-sm-8">
					<select class="form-control" id="new_tipo_entidad" name="new_tipo_entidad" required>
						<option value="">Selecciona un tipo entidad</option>
						<?php
						// Llenar el select con los resultados de la consulta
						while ($row = mysqli_fetch_assoc($tipo_entidad_query)) {
							echo "<option value='{$row['id']}'>{$row['DESCRIPCION_TIPO_ENTIDAD']}</option>";
						}
						?>
					</select>
				</div>

			  </div> 

	


			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
	<?php
		}
	?>

<script>
function validateDigit(input) {
    if (input.value.length > 1) {
        input.value = input.value.slice(0, 1);  // Limitar a 1 dígito
    }
}

function validateNit(input) {
    // Asegurarse de que el número no tenga más de 9 dígitos
    if (input.value.length > 9) {
        input.value = input.value.slice(0, 9);
    }
}
</script>