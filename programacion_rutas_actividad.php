<?php
require_once( "includes/conexion.php" );
PermitirAcceso(312);
//require_once("includes/conexion_hn.php");
if(isset($_GET['id'])&&$_GET['id']!=""){
	$id=base64_decode($_GET['id']);
	$idEvento=base64_decode($_GET['idEvento']);	
}else{
	$id="";
	$idEvento="";
}

$type_act= isset($_GET['tl']) ? $_GET['tl'] : 1;

if($type_act==1){
	$Where="DocEntry='".$id."' and IdEvento='".$idEvento."'";
}else{
	$Where="ID_Actividad='".$id."' and IdEvento='".$idEvento."'";
}

//Actividades
$SQL_Actividades = Seleccionar('uvw_tbl_Actividades_Rutas','*',$Where);
$row=sql_fetch_array($SQL_Actividades);

//Asunto actividad
$SQL_AsuntoActividad=Seleccionar('uvw_Sap_tbl_AsuntosActividad','*',"Id_TipoActividad=3",'DE_AsuntoActividad');

//Empleados
$SQL_EmpleadoActividad=Seleccionar('uvw_Sap_tbl_Empleados','*',"IdUsuarioSAP=0",'NombreEmpleado');

//Turno técnico
$SQL_TurnoTecnicos=Seleccionar('uvw_Sap_tbl_TurnoTecnicos','*');

//Tipos de Estado actividad
$SQL_TiposEstadoActividad=Seleccionar('uvw_tbl_TipoEstadoServicio','*');

//Estado actividad
$SQL_EstadoActividad=Seleccionar('uvw_tbl_EstadoActividad','*');

//Materiales
$ParamMateriales=array(
	"'".$row['ID_LlamadaServicio']."'"
);

$SQL_Materiales=EjecutarSP("sp_ConsultarDatosCalendarioRutasMateriales",$ParamMateriales);

//Historico actividades
$ParamHistAct=array(
	"'".$row['ID_CodigoCliente']."'",
	"'".$row['NombreSucursal']."'",
	"'".FormatoFecha($row['FechaFinActividad'])."'"
);

$SQL_HistAct=EjecutarSP("sp_ConsultarDatosCalendarioRutasHistAct",$ParamHistAct);

if($type_act==1){
	//Anexos
	$SQL_AnexoActividad=Seleccionar('uvw_Sap_tbl_DocumentosSAP_Anexos','*',"AbsEntry='".$row['IdAnexoActividad']."'");
}


?>
<form id="frmActividad" method="post">
<div class="modal-content">
  <div class="modal-header">
    <h5 class="modal-title"><?php echo $row['EtiquetaActividad'];?></h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
  </div>
  <div class="modal-body">
	<div class="pt-3 pr-3 pl-3 pb-1 mb-2 bg-primary text-white"><h5><i class="fas fa-calendar-alt"></i> Datos de programación</h5></div>
   	<div class="form-group row">
		<label class="col-lg-1 col-form-label">Fecha inicio</label>
		<div class="col-lg-2 input-group">
			<?php /*?><div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-alt d-block"></i></span>
			</div><?php */?>
			<input name="FechaInicio" type="text" required="required" class="form-control" id="FechaInicio" value="<?php echo $row['FechaInicioActividad'];?>" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>>
		</div>
		<div class="col-lg-2 input-group">
			<input name="HoraInicio" id="HoraInicio" type="text" class="form-control" value="<?php echo $row['HoraInicioActividad'];?>" required="required" onChange="ValidarHoras();" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>>
			<?php /*?><div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1"><i class="fas fa-clock d-block"></i></span>
			</div><?php */?>
		</div>
		<label class="col-lg-2 col-form-label">Fecha inicio ejecución</label>
		<div class="col-lg-2 input-group">
			<input name="FechaInicioEjecucion" type="text" class="form-control" id="FechaInicioEjecucion" value="<?php echo $row['CDU_FechaInicioEjecucionActividad'];?>" readonly="readonly">
		</div>
		<div class="col-lg-2">
			<input name="HoraInicioEjecucion" type="text" class="form-control" id="HoraInicioEjecucion" value="<?php echo $row['CDU_HoraInicioEjecucionActividad'];?>" readonly="readonly">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-1 col-form-label">Fecha fin</label>
		<div class="col-lg-2 input-group">
			<?php /*?><div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-alt d-block"></i></span>
			</div><?php */?>
			<input name="FechaFin" type="text" required="required" class="form-control" id="FechaFin" value="<?php echo $row['FechaFinActividad'];?>" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>>
		</div>
		<div class="col-lg-2 input-group">
			<input name="HoraFin" id="HoraFin" type="text" class="form-control" value="<?php echo $row['HoraFinActividad'];?>" required="required" onChange="ValidarHoras();" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>>
			<?php /*?><div class="input-group-prepend">
				<span class="input-group-text" id="basic-addon1"><i class="fas fa-clock d-block"></i></span>
			</div><?php */?>
		</div>
		<label class="col-lg-2 col-form-label">Fecha fin ejecución</label>
		<div class="col-lg-2 input-group">
			<input name="FechaFinEjecucion" type="text" class="form-control" id="FechaFinEjecucion" value="<?php echo $row['CDU_FechaFinEjecucionActividad'];?>" readonly="readonly">
		</div>
		<div class="col-lg-2">
			<input name="HoraFinEjecucion" type="text" class="form-control" id="HoraFinEjecucion" value="<?php echo $row['CDU_HoraFinEjecucionActividad'];?>" readonly="readonly">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-1 col-form-label">Tipo estado actividad</label>
		<div class="col-lg-4">
			<select name="TipoEstadoActividad" class="form-control m-b" id="TipoEstadoActividad" required="required" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "disabled='disabled'";}?>>
				<option value="">Seleccione...</option>
			  <?php while($row_TiposEstadoActividad=sqlsrv_fetch_array($SQL_TiposEstadoActividad)){?>
					<option value="<?php echo $row_TiposEstadoActividad['ID_TipoEstadoServicio'];?>" data-color="<?php echo $row_TiposEstadoActividad['ColorEstadoServicio'];?>" <?php if((isset($row['IdTipoEstadoActividad']))&&(strcmp($row_TiposEstadoActividad['ID_TipoEstadoServicio'],$row['IdTipoEstadoActividad'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_TiposEstadoActividad['DE_TipoEstadoServicio'];?></option>
			  <?php }?>
			</select>
		</div>
		<label class="col-lg-2 col-form-label">Estado actividad</label>
		<div class="col-lg-3">
			<select name="EstadoActividad" class="form-control m-b" id="EstadoActividad" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "disabled='disabled'";}?>>
			  <?php while($row_EstadoActividad=sqlsrv_fetch_array($SQL_EstadoActividad)){?>
					<option value="<?php echo $row_EstadoActividad['Cod_Estado'];?>" <?php if((isset($row['IdEstadoActividad']))&&(strcmp($row_EstadoActividad['Cod_Estado'],$row['IdEstadoActividad'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_EstadoActividad['NombreEstado'];?></option>
			  <?php }?>
			</select>
		</div>
	</div>
	<div class="pt-3 pr-3 pl-3 pb-1 mb-2 bg-primary text-white"><h5><i class="fas fa-info-circle"></i> Información de la actividad</h5></div>
	<ul class="nav nav-tabs" id="myTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#tab-1"><i class="fas fa-tasks"></i> Programación</a>
	  	</li>
	  	<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tab-2"><i class="fas fa-tools"></i> Materiales</a>
	  	</li>
	  	<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tab-3"><i class="fas fa-history"></i> Historico de actividades</a>
	  	</li>
		<?php if($type_act==1){?>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tab-4"><i class="fas fa-paperclip"></i> Anexos</a>
	  	</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#tab-5" <?php if($row['LatitudGPS']!=""&&$row['LongitudGPS']!=""){?>onClick="initMap();"<?php }?>><i class="fas fa-map-marker-alt"></i> Ubicación GPS</a>
	  	</li>
		<?php }?>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade show active" id="tab-1">
			<br>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label">Titulo de actividad</label>
				<div class="col-lg-7">
					<input name="TituloActividad" type="text" class="form-control" id="TituloActividad" value="<?php echo $row['TituloActividad'];?>" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>>
				</div>
				<label class="col-lg-1 col-form-label">Asunto</label>
				<div class="col-lg-3">
					<select name="AsuntoActividad" class="form-control m-b" id="AsuntoActividad" required="required" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "disabled='disabled'";}?>>
						<?php if($type_act==0){?><option value="">Seleccione...</option><?php }?>
						<?php while($row_AsuntoActividad=sqlsrv_fetch_array($SQL_AsuntoActividad)){?>
							<option value="<?php echo $row_AsuntoActividad['ID_AsuntoActividad'];?>" <?php if((isset($row['ID_AsuntoActividad']))&&(strcmp($row_AsuntoActividad['ID_AsuntoActividad'],$row['ID_AsuntoActividad'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_AsuntoActividad['DE_AsuntoActividad'];?></option>
					  <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label"><?php if(($type_act==1)&&($row['DocEntry']!=0)){?><a href="actividad.php?id=<?php echo base64_encode($row['DocEntry']);?>&tl=1" target="_blank" title="Consultar actividad" class="btn-xs btn-success fas fa-search"></a> <?php }?>ID Actividad</label>
				<div class="col-lg-3">
					<input name="IdActividad" type="text" class="form-control" disabled id="IdActividad" value="<?php echo $row['DocEntry'];?>">
				</div>
				<label class="col-lg-1 col-form-label"><?php if(($type_act==1)&&($row['ID_LlamadaServicio']!=0)){?><a href="llamada_servicio.php?id=<?php echo base64_encode($row['DocEntryLlamadaServicio']);?>&tl=1" target="_blank" title="Consultar Llamada de servicio" class="btn-xs btn-success fas fa-search"></a> <?php }?>Llamada de servicio</label>
				<div class="col-lg-3">
					<input name="LlamadaServicio" type="text" class="form-control" disabled id="LlamadaServicio" value="<?php echo $row['ID_LlamadaServicio'];?>">
				</div>
				<label class="col-lg-1 col-form-label">Estado llamada</label>
				<div class="col-lg-3">
					<input name="EstadoLlamada" type="text" class="form-control" disabled id="EstadoLlamada" value="<?php echo $row['DeEstadoLlamada'];?>">
				</div>				
			</div>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label">Asignado a</label>
				<div class="col-lg-3">
					<select name="EmpleadoActividad" class="form-control select2" style="width: 100%" required id="EmpleadoActividad" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "disabled='disabled'";}?>>
							<option value="">(Sin asignar)</option>
					  <?php while($row_EmpleadoActividad=sqlsrv_fetch_array($SQL_EmpleadoActividad)){?>
							<option value="<?php echo $row_EmpleadoActividad['ID_Empleado'];?>" <?php if((isset($row['ID_EmpleadoActividad']))&&(strcmp($row_EmpleadoActividad['ID_Empleado'],$row['ID_EmpleadoActividad'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_EmpleadoActividad['NombreEmpleado'];?></option>
					  <?php }?>
					</select>
				</div>
				<label class="col-lg-1 col-form-label">Turno técnico</label>
				<div class="col-lg-3">
					<select name="TurnoTecnico" class="form-control" id="TurnoTecnico" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "disabled='disabled'";}?>>
							<option value="">Seleccione...</option>
					  <?php while($row_TurnoTecnicos=sqlsrv_fetch_array($SQL_TurnoTecnicos)){?>
							<option value="<?php echo $row_TurnoTecnicos['CodigoTurno'];?>" <?php if((isset($row['CDU_IdTurnoTecnico']))&&(strcmp($row_TurnoTecnicos['CodigoTurno'],$row['CDU_IdTurnoTecnico'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_TurnoTecnicos['NombreTurno'];?></option>
					  <?php }?>
					</select>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label">Comentarios</label>
				<div class="col-lg-5">
					<textarea name="Comentarios" rows="2" maxlength="1000" class="form-control" id="Comentarios" type="text" <?php if(($type_act==1)&&($row['IdEstadoActividad']=='Y')){ echo "readonly='readonly'";}?>><?php echo $row['ComentariosActividad'];?></textarea>
				</div>
				<label class="col-lg-1 col-form-label">Notas de la actividad</label>
				<div class="col-lg-5">
					<textarea name="Notas" rows="2" maxlength="1000" class="form-control" id="Notas" type="text" disabled><?php echo $row['NotasActividad'];?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label">Servicios</label>
				<div class="col-lg-5">
					<textarea name="Servicios" rows="2" maxlength="1000" class="form-control" id="Servicios" type="text" disabled><?php echo $row['CDU_Servicios'];?></textarea>
				</div>
				<label class="col-lg-1 col-form-label">Áreas</label>
				<div class="col-lg-5">
					<textarea name="Areas" rows="2" maxlength="1000" class="form-control" id="Areas" type="text" disabled><?php echo $row['CDU_Areas'];?></textarea>
				</div>
			</div>
			<div class="form-group row">
				<label class="col-lg-1 col-form-label">Comentarios <span class="text-muted">Llamada de servicio</span></label>
				<div class="col-lg-5">
					<textarea name="Diagnostico" rows="2" maxlength="1000" class="form-control" id="Diagnostico" type="text" disabled><?php echo $row['ComentarioLlamada'];?></textarea>
				</div>				
			</div>
		</div>
		<div class="tab-pane fade" id="tab-2">
			<br>
			<div class="table-responsive">
				<table class="table table-striped table-bordered table-hover table-sm" >
					<thead>
					<tr>
						<th>Código artículo</th>
						<th>Nombre artículo</th>
						<th>Cantidad</th>
						<th>Metodo de aplicación</th>
					</tr>
					</thead>
					<tbody>
					<?php while($row_Materiales=sqlsrv_fetch_array($SQL_Materiales)){?>
						 <tr>
							 <td><?php echo $row_Materiales['ItemCode'];?></td>
							 <td><?php echo $row_Materiales['ItemName'];?></td>
							 <td><?php echo number_format($row_Materiales['Cantidad'],2);?></td>
							 <td><?php echo $row_Materiales['MetodoAplicacion'];?></td>
						</tr>
					<?php }?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="tab-pane fade" id="tab-3">
			<br>
			<?php while($row_HistAct=sqlsrv_fetch_array($SQL_HistAct)){?>
			<div class="media mb-3">
				<div class="text-center">
					<i class="far fa-clock fa-2x text-primary"></i>
					<div class="text-muted small text-nowrap mt-2"><?php echo $row_HistAct['FechaActividad'];?></div>
				</div>
				<div class="media-body bg-lighter rounded py-2 px-3 ml-3">
					<div class="font-weight-semibold mb-1"><?php echo $row_HistAct['NombreEmpleado'];?></div>
					<?php echo $row_HistAct['DetallesActividad'];?><br>
					<?php echo $row_HistAct['ComentariosActividad'];?>
				</div>
			</div>
			<?php }?>
		</div>
		<?php if($type_act==1){?>
		<div class="tab-pane fade" id="tab-4">
			<?php
				if($row['IdAnexoActividad']!=0){
					while($row_AnexoActividad=sqlsrv_fetch_array($SQL_AnexoActividad)){
						$Icon=IconAttach($row_AnexoActividad['FileExt'],2);
			?>
					<div class="col-md-6 col-lg-4 col-xl-4 p-1">
						<div class="project-attachment ui-bordered p-2">
							<div class="project-attachment-file display-4">
								<i class="<?php echo $Icon;?>"></i>
							</div>
							<div class="media-body ml-3">
								<strong class="project-attachment-filename"><?php echo $row_AnexoActividad['NombreArchivo'];?></strong>
								<div class="text-muted small"><?php echo $row_AnexoActividad['Fecha'];?></div>
								<div>
									<a href="attachdownload.php?file=<?php echo base64_encode($row_AnexoActividad['AbsEntry']);?>&line=<?php echo base64_encode($row_AnexoActividad['Line']);?>" target="_blank">Descargar</a>
								</div>
							</div>
						</div>
					</div>
			<?php }?>
			<?php }else{ echo "<br><p>Sin anexos.</p>"; }?>			
		</div>
		<div class="tab-pane fade" id="tab-5">
			<div class="card card-body">
				<div class="google-map mapGoogle" id="map"><?php if($row['LatitudGPS']==""||$row['LongitudGPS']==""){echo "<br><p>No hay datos para mostrar.</p>";}?></div>
			</div>						
		</div>
		<?php }?>
	</div>
	<div class="pt-3 pr-3 pl-3 pb-1 mb-2 bg-primary text-white"><h5><i class="fas fa-users"></i> Información del cliente</h5></div>
	<div class="form-group row">
		<label class="col-lg-1 col-form-label"><?php if(($type_act==1)&&($row['ID_LlamadaServicio']!=0)){?><a href="socios_negocios.php?id=<?php echo base64_encode($row['ID_CodigoCliente']);?>&tl=1" target="_blank" title="Consultar cliente" class="btn-xs btn-success fas fa-search"></a> <?php }?>Nombre cliente</label>
		<div class="col-lg-4">
			<input name="NombreCliente" type="text" class="form-control" disabled id="NombreCliente" value="<?php echo $row['NombreCliente'];?>">
		</div>
		<label class="col-lg-1 col-form-label">Nombre sucursal</label>
		<div class="col-lg-6">
			<input name="NombreSucursal" type="text" class="form-control" disabled id="NombreSucursal" value="<?php echo $row['NombreSucursal'];?>">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-lg-1 col-form-label">Contacto</label>
		<div class="col-lg-4">
			<input name="Contacto" type="text" class="form-control" disabled id="Contacto" value="<?php echo $row['NombreContacto'];?>">
		</div>
		<label class="col-lg-1 col-form-label">Dirección</label>
		<div class="col-lg-6">
			<input name="DireccionActividad" type="text" class="form-control" disabled id="DireccionActividad" value="<?php echo $row['DireccionActividad'];?>">
		</div>
	</div>
	<div class="form-group row">		
		<label class="col-lg-1 col-form-label">Teléfono</label>
		<div class="col-lg-4">
			<input name="Telefono" type="text" class="form-control" disabled id="Telefono" value="<?php echo $row['Telefono1'];?>">
		</div>
		<label class="col-lg-1 col-form-label">Email</label>
		<div class="col-lg-3">
			<input name="Email" type="text" class="form-control" disabled id="Email" value="<?php echo $row['CorreoElectronico'];?>">
		</div>
	</div>
	
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-secondary md-btn-flat" data-dismiss="modal">Cerrar</button>
    <?php if($row['IdEstadoActividad']!='Y'){?><button type="submit" class="btn btn-primary md-btn-flat"><i class="fas fa-save"></i> Guardar</button><?php }?>
  </div>
</div>
</form>
<script>
	 $(document).ready(function(){
		  $("#frmActividad").validate({
			 submitHandler: function(form, event){
				event.preventDefault()
				blockUI();
				$.ajax({
					type: "GET",
					url: "includes/procedimientos.php?type=31&id_actividad=<?php echo $row['ID_Actividad'];?>&id_evento=<?php echo $row['IdEvento'];?>&docentry=<?php echo $row['DocEntry'];?>&id_asuntoactividad="+$("#AsuntoActividad").val()+"&titulo_actividad="+$("#TituloActividad").val()+"&id_empleadoactividad="+$("#EmpleadoActividad").val()+"&fechainicio="+$("#FechaInicio").val()+"&horainicio="+$("#HoraInicio").val()+"&fechafin="+$("#FechaFin").val()+"&horafin="+$("#HoraFin").val()+"&comentarios_actividad="+$("#Comentarios").val()+"&estado="+$("#EstadoActividad").val()+"&id_tipoestadoact="+$("#TipoEstadoActividad").val()+"&llamada_servicio=<?php echo $row['ID_LlamadaServicio'];?>&metodo=2&fechainicio_ejecucion="+$("#FechaInicioEjecucion").val()+"&horainicio_ejecucion="+$("#HoraInicioEjecucion").val()+"&fechafin_ejecucion="+$("#FechaFinEjecucion").val()+"&horafin_ejecucion="+$("#HoraFinEjecucion").val()+"&turno_tecnico="+$("#TurnoTecnico").val()+"&sptype=2",		
					success: function(response){
						if(response=="OK"){
							$("#btnGuardar").prop('disabled', false);
							$("#btnPendientes").prop('disabled', false);
							var event = calendar.getEventById('<?php echo $id; ?>')
							event.setExtendedProp('manualChange', '1')
							event.setProp('backgroundColor', $("#TipoEstadoActividad").find(':selected').data('color'))
							event.setProp('borderColor', $("#TipoEstadoActividad").find(':selected').data('color'))
							event.setDates($("#FechaInicio").val()+' '+$("#HoraInicio").val(), $("#FechaFin").val()+' '+$("#HoraFin").val())
							event.setResources([$("#EmpleadoActividad").val()])
							if($("#EstadoActividad").val()=='Y'){
								event.setProp('classNames', ['event-striped'])
							}
							$('#ModalAct').modal("hide");
							event.setExtendedProp('manualChange', '0')
							blockUI(false);
							mostrarNotify('Se ha editado una actividad')
						}else{
							 Swal.fire({
								title: '¡Advertencia!',
								text: 'No se pudo insertar la actividad en la ruta',
								icon: 'warning',
							});
							console.log("Error:",response)
						}				
					}
				});		
			}
		});
		 
 <?php if($row['IdEstadoActividad']!='Y'){?>		 
		 $('#FechaInicio').flatpickr({
			 dateFormat: "Y-m-d",
			 static : true,
			 allowInput: true
		 });
		 $('#HoraInicio').flatpickr({
			 enableTime: true,
			 noCalendar: true,
			 dateFormat: "H:i",
			 time_24hr: true,
			 static : true,
			 allowInput: true
		 });
		
		 $('#FechaFin').flatpickr({
			 dateFormat: "Y-m-d",
			 static : true,
			 allowInput: true
		 });
		 $('#HoraFin').flatpickr({
			 enableTime: true,
			 noCalendar: true,
			 dateFormat: "H:i",
			 time_24hr: true,
			 static : true,
			 allowInput: true
		 });
		
 <?php }?>
 		$('#EmpleadoActividad').select2({
			dropdownParent: $('#ModalAct')
		 });
		 
	 });
function ValidarHoras(){
	var HInicio = document.getElementById("HoraInicio").value;
	var HFin = document.getElementById("HoraFin").value;
	
	if(!validarRangoHoras(HInicio,HFin)){
		 Swal.fire({
			title: '¡Advertencia!',
			text: 'Tiempo no válido. Ingrese una duración positiva.',
			icon: 'warning',
		});
		return false;
	}
}
</script>
<?php if($type_act==1){
	if($row['LatitudGPS']!=""&&$row['LongitudGPS']!=""){?>
<script>
var map;

function initMap(){
	var pos = {
		lat: <?php echo $row['LatitudGPS'];?>, 
		lng: <?php echo $row['LongitudGPS'];?>
	};
	
	map = new google.maps.Map(document.getElementById('map'), {
		center: pos,
		zoom: 16
	});
	
	const iconBase = "https://maps.google.com/mapfiles/kml/paddle/";
	
	var start = new google.maps.LatLng(<?php echo $row['LatitudGPS'];?>,<?php echo $row['LongitudGPS'];?>);
	
	var markerStart = new google.maps.Marker({
		map: map,
		draggable: false,
		animation: google.maps.Animation.DROP,
		position: start,
		icon: iconBase + 'go.png'
	});
	
	<?php if($row['LatitudGPSFin']!=""&&$row['LongitudGPSFin']!=""){ ?>
		var end = new google.maps.LatLng(<?php echo $row['LatitudGPSFin'];?>,<?php echo $row['LongitudGPSFin'];?>);	

		var markerEnd = new google.maps.Marker({
			map: map,
			draggable: false,
			animation: google.maps.Animation.DROP,
			position: end,
			icon: iconBase + 'red-square.png'
		});	
	<?php }?>
}
</script>
<?php }
}?>