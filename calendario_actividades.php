<?php require_once("includes/conexion.php");
PermitirAcceso(305);

$sw=0;
$Filtro="";//Filtro
$sw_suc=0;//Si se selecciono el cliente, para mostrar la lista de las sucursales al recargar la pagina
$Cliente="";
$Sucursal="";

//Clientes
if(PermitirFuncion(205)){
	$SQL_Cliente=Seleccionar("uvw_Sap_tbl_Clientes","CodigoCliente, NombreCliente");
}else{
	$Where="ID_Usuario = ".$_SESSION['CodUser'];
	$SQL_Cliente=Seleccionar("uvw_tbl_ClienteUsuario","CodigoCliente, NombreCliente",$Where);	
}

//Parametros
if(isset($_POST['ClienteActividad'])&&($_POST['ClienteActividad']!="")){
	$Cliente=$_POST['ClienteActividad'];
	$sw_suc=1;
	$sw=1;
}
if(isset($_POST['Sucursal'])&&($_POST['Sucursal']!="")){
	$Sucursal=$_POST['Sucursal'];
}
if($sw==1){
	$Cons="EXEC sp_ConsultarDatosCalendario '".$_SESSION['CodUser']."', '".$Cliente."', '".$Sucursal."'";
	//echo $Cons;
	$SQL=sqlsrv_query($conexion,$Cons);
}

?>
<!DOCTYPE html>
<html><!-- InstanceBegin template="/Templates/PlantillaPrincipal.dwt.php" codeOutsideHTMLIsLocked="false" -->

<head>
<?php include_once("includes/cabecera.php"); ?>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Calendario de clientes | <?php echo NOMBRE_PORTAL;?></title>
<!-- InstanceEndEditable -->
<!-- InstanceBeginEditable name="head" -->
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
                    <h2>Calendario de clientes</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index1.php">Inicio</a>
                        </li>
                        <li>
                            <a href="#">Servicios</a>
                        </li>
						<li>
                            <a href="#">Calendarios</a>
                        </li>
                        <li class="active">
                            <strong>Calendario de clientes</strong>
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
				  <form action="calendario_actividades.php" method="post" id="formFiltro" class="form-horizontal">
					  	<div class="form-group">
							<label class="col-lg-1 control-label">Cliente</label>
							<div class="col-lg-3">
								<select name="ClienteActividad" class="form-control m-b select2" id="ClienteActividad">
										<option value="">SELECCIONE...</option>
								  <?php while($row_Cliente=sqlsrv_fetch_array($SQL_Cliente)){?>
										<option value="<?php echo $row_Cliente['CodigoCliente'];?>" <?php if((isset($_POST['ClienteActividad']))&&(strcmp($row_Cliente['CodigoCliente'],$_POST['ClienteActividad'])==0)){ echo "selected=\"selected\"";}?>><?php echo $row_Cliente['NombreCliente'];?></option>
								  <?php }?>
								</select>
							</div>
							<label class="col-lg-1 control-label">Sucursal</label>
							<div class="col-lg-3">
							 <select id="Sucursal" name="Sucursal" class="form-control">
								 <option value="">(Todos)</option>
								 <?php 
								 if($sw_suc==1){//Mostrar el cliente seleccionado
									 if(PermitirFuncion(205)){
										$SQL_Sucursal=Seleccionar("uvw_Sap_tbl_Clientes_Sucursales","NombreSucursal","CodigoCliente='".$_POST['ClienteActividad']."'");
									 }else{
										$SQL_Sucursal=Seleccionar("uvw_tbl_SucursalesClienteUsuario","NombreSucursal","CodigoCliente='".$_POST['ClienteActividad']."' and ID_Usuario='".$_SESSION['CodUser']."'");	
									 }
									 while($row_Sucursal=sqlsrv_fetch_array($SQL_Sucursal)){?>
										<option value="<?php echo $row_Sucursal['NombreSucursal'];?>" <?php if(strcmp($row_Sucursal['NombreSucursal'],$_POST['Sucursal'])==0){ echo "selected=\"selected\"";}?>><?php echo $row_Sucursal['NombreSucursal'];?></option>
								<?php }
								 }elseif($sw_suc==2){//Si no se ha seleccionado ningun cliente
									  while($row_Sucursal=sqlsrv_fetch_array($SQL_Sucursal)){?>
										<option value="<?php echo $row_Sucursal['NombreSucursal'];?>"><?php echo $row_Sucursal['NombreSucursal'];?></option>
								<?php }								 
								 }?>
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
							borderColor: '".$row['ColorPrioridadActividad']."'
						},";
					}
				}
			?>
            ]		
        });
    });
</script>
<!-- InstanceEndEditable -->
</body>

<!-- InstanceEnd --></html>
<?php sqlsrv_close($conexion);?>