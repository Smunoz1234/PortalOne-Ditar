<?php require_once("includes/conexion.php");
PermitirAcceso(306);

$sw=0;
//$ParamTec=array(
//	"'".$_SESSION['CodUser']."'"
//);
//
//$SQL_Tec=EjecutarSP("sp_ConsultarTecnicos",$ParamTec);

$SQL_Tec=Seleccionar("uvw_Sap_tbl_Recursos","*",'','NombreEmpleado');


if(isset($_GET['Tecnico'])&&$_GET['Tecnico']!=""){
	$Param=array(
		"'".base64_decode($_GET['Tecnico'])."'"
	);
	$sw=1;
}elseif(isset($_GET['Tecnico'])&&$_GET['Tecnico']==""){
	$Param=array(
		"''"
	);
	$sw=1;
}

if($sw==1){
	$SQL=EjecutarSP('sp_ConsultarDatosCalendarioTecnico',$Param);
}

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Calendario de técnicos | <?php echo NOMBRE_PORTAL;?></title>
	<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
<?php 
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_InsNotAct"))){
	echo "<script>
		$(document).ready(function() {
			Swal.fire({
                title: '¡Listo!',
                text: 'Las notas fueron agregadas exitosamente.',
                icon: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_UpdActAdd"))){
	echo "<script>
		$(document).ready(function() {
			Swal.fire({
                title: '¡Listo!',
                text: 'La actividad ha sido actualizada exitosamente.',
                icon: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_DelAct"))){
	echo "<script>
		$(document).ready(function() {
			Swal.fire({
                title: '¡Listo!',
                text: 'La actividad ha sido eliminado exitosamente.',
                icon: 'success'
            });
		});		
		</script>";
}
if(isset($_GET['a'])&&($_GET['a']==base64_encode("OK_OpenAct"))){
	echo "<script>
		$(document).ready(function() {
			Swal.fire({
                title: '¡Listo!',
                text: 'La actividad ha sido abierta nuevamente.',
                icon: 'success'
            });
		});		
		</script>";
}
?>
<script type="text/javascript">
	$(document).ready(function() {//Cargar los almacenes dependiendo del proyecto
		$("#ClienteActividad").change(function(){
			$.ajax({
				type: "POST",
				url: "ajx_cbo_sucursales_clientes_simple.php?CardCode="+document.getElementById('ClienteActividad').value,
				success: function(response){
					$('#Sucursal').html(response).fadeIn();
				}
			});
		});
	});
</script>
	<!-- InstanceEndEditable -->
</head>

<body>

<div id="wrapper">

    <?php include_once("includes/menu.php"); ?>

    <div id="page-wrapper" class="gray-bg">
        <?php include_once("includes/menu_superior.php"); ?>
        <!-- InstanceBeginEditable name="Contenido" -->
        <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-sm-8">
                    <h2>Calendario de técnicos</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Gesti&oacute;n de tareas</a>
                        </li>
						<li>
                            <a href="#">Calendarios</a>
                        </li>
                        <li class="active">
                            <strong>Calendario de técnicos</strong>
                        </li>
                    </ol>
                </div>
               <?php  //echo $Cons;?>
            </div>
         <div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">
			    <div class="ibox-content">
					 <?php include("includes/spinner.php"); ?>
				  <form action="calendario_actividades_tecnico.php" method="get" id="formFiltro" class="form-horizontal">
					  	<div class="form-group">
							<label class="col-lg-1 control-label">Técnico</label>
							<div class="col-lg-3">
								<select name="Tecnico" class="form-control m-b select2" id="Tecnico">
										<option value="">(TODOS)</option>
								  <?php while($row_Tec=sqlsrv_fetch_array($SQL_Tec)){?>
										<option value="<?php echo base64_encode($row_Tec['ID_Empleado']);?>" <?php if((isset($_GET['Tecnico']))&&(strcmp(base64_encode($row_Tec['ID_Empleado']),$_GET['Tecnico'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_Tec['NombreEmpleado'];?></option>
								  <?php }?>
								</select>
							</div>
							<div class="col-lg-1">
								<button type="submit" class="btn btn-outline btn-success pull-right"><i class="fa fa-filter"></i> Filtrar</button>
							</div>
						</div>
				 </form>
			</div>
			</div>
		  </div>
			<br>
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox-content">
						<?php include("includes/spinner.php"); ?>
						<div class="table-responsive">
							<div id="calendar"></div>
						</div>
					</div>
				</div> 
			</div>
        </div>
        <!-- InstanceEndEditable -->
        <?php include_once("includes/footer.php"); ?>

    </div>
</div>
<?php include_once("includes/pie.php"); ?>
<!-- InstanceBeginEditable name="EditRegion4" -->
<script>

    $(document).ready(function() {
		$("#formFiltro").validate({
			 submitHandler: function(form){
				 $('.ibox-content').toggleClass('sk-loading');
				 form.submit();
				}
			});
		$(".select2").select2();
		
        /* initialize the calendar
         -----------------------------------------------------------------*/
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
			defaultView: 'agendaWeek',
            editable: false,
			timeFormat: 'hh:mm a',
			eventRender: function(event, element){
				element.qtip({
					content: {
						title: event.subtitle,
						text: event.description
					},
					position: {
						target: 'mouse',
						adjust: { x: 5, y: 5 }
					}
				});
			},
            events: [
			<?php 
				if($sw==1){	
					while($row=sqlsrv_fetch_array($SQL)){
						if($row['TodoDia']==1){$AllDay="true";}else{$AllDay="false";}
						//if($row['TipoRecurso']==-2){//Para diferenciar de técnicos y empleados (actividades y agenda)
							//$URL="#";
						//}else{
							$URL="actividad.php?id=".base64_encode($row['ID_Actividad'])."&return=".base64_encode($_SERVER['QUERY_STRING'])."&pag=".base64_encode('calendario_actividades_tecnico.php')."&tl=1";
						//}
						echo "{
							id: ".$row['ID_Actividad'].",
							title:'".LSiqmlObs($row['EtiquetaActividad'])."',
							subtitle:'".$row['AsuntoActividad']."',
							description:'".LSiqmlSaltos(LSiqmlObs($row['InformacionAdicional']))."',
							start: '".$row['FechaHoraInicioActividad']."',
							end: '".$row['FechaHoraFinActividad']."',
							allDay: ".$AllDay.",
							textColor: '#ffffff',
							backgroundColor: '".$row['ColorPrioridadActividad']."',
							borderColor: '".$row['ColorPrioridadActividad']."',
							url: '".$URL."'
						},";
					}
				}
			?>
            ],
			eventClick: function(event) {
				if(event.url){
					window.open(event.url);
					return false;
				}
			}	
        });
    });
</script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>