	<?php
		if (isset($con))
		{
			// Realizar la consulta a la base de datos
			$tipo_entidad_query = mysqli_query($con, "SELECT id, descripcion AS DESCRIPCION_TIPO_ENTIDAD FROM PM_TIPO_ENTIDAD");

	?>
	<!-- Modal -->
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar entidades</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="editar_entidad" name="editar_entidad">
				<div id="resultados_ajax2"></div>
				<div class="form-group">
				
				<label for="mod_nombre" class="col-sm-3 control-label">Nombre:</label>
					<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_nombre" name="mod_nombre" placeholder="Nombre" required>
					<input type="hidden" id="mod_id" name="mod_id">
					</div>
				
				<label for="mod_nit" class="col-sm-3 control-label">Nit:</label>
					<div class="col-sm-8">
						<input type="number" class="form-control" id="mod_nit" name="mod_nit" 
							placeholder="Nit sin puntos" required min="0" max="999999999" 
							oninput="validateNit(this)" maxlength="9">
					</div>

				
				<label for="mod_dv" class="col-sm-3 control-label">DV:</label>
					<div class="col-sm-8">
						<input type="number" class="form-control" id="mod_dv" name="mod_dv" 
							placeholder="Dígito de verificación" 
							required min="0" max="9" maxlength="1" oninput="validateDigit(this)">
					</div>



					<label for="mod_tipo_entidad" class="col-sm-3 control-label">Tipo entidad:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="mod_tipo_entidad" name="mod_tipo_entidad" required>
                            <option value="">Selecciona un tipo entidad</option>
                            <?php
                            // Llenar el select con los resultados de la consulta
                            while ($row = mysqli_fetch_assoc($tipo_entidad_query)) {
                                echo "<option value='{$row['id']}'>{$row['DESCRIPCION_TIPO_ENTIDAD']}</option>";
                            }
                            ?>
                        </select>
                    </div>	

				<label for="mod_estado" class="col-sm-3 control-label">Estado</label>
					<div class="col-sm-8">
						<select class="form-control" id="mod_estado" name="mod_estado">
						<option value="0">Inactivo</option>
						<option value="1">Activo</option>
						</select>
					</div>
				</div>
						 	 
			
		  </div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
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