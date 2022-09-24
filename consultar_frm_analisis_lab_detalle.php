<?php
require_once( "includes/conexion.php" );

if(isset($_POST['id'])&&$_POST['id']!=""){
	$id = $_POST['id'];
}else{
	$id = "";
}

$SQL=Seleccionar("tbl_AnalisisLaboratorioDetalle","*","id_analisis_laboratorio='".$id."'");
$dir_anx=CrearObtenerDirAnx("formularios/analisis_laboratorio/anexos");
?>
<div class="row m-t-md form-horizontal">
	 <div class="col-lg-12">
		<div class="ibox-content">
			 <?php include("includes/spinner.php"); ?>
			<div class="form-group">
				<label class="col-xs-12"><h3 class="bg-success p-xs b-r-sm"><i class="fa fa-list"></i> Detalle de análisis de laboratorio: <?php echo $id;?></h3></label>
			</div>
			<div class="table-responsive">
				<table width="100%" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Motonave</th>
							<th>Producto</th>
							<th>Humedad (%)</th>
							<th>Densidad (Kg)</th>
							<th>Anexo humedad</th>
							<th>Granos partidos (%)</th>
							<th>Anexo granos partidos</th>
							<th>Limpieza (%)</th>
							<th>Anexo limpieza</th>
							<th>Granos quemados (%)</th>
							<th>Anexo granos quemados</th>
							<th>Otros productos</th>
							<th>Otros productos (%)</th>
							<th>Anexo otros productos</th>
						</tr>
					</thead>
					<tbody>
						 <?php $i=1;
							while($row=sqlsrv_fetch_array($SQL)){?>
						<tr>
							<td><?php echo $i;?></td>
							<td><?php echo $row['transporte_puerto'];?></td>
							<td><?php echo $row['producto_puerto'];?></td>
							<td><?php echo number_format($row['porcen_humedad'],1);?></td>
							<td><?php echo number_format($row['kg_hl_densidad'],1);?></td>
							<td><a href="filedownload.php?file=<?php echo base64_encode($row['anexo_humedad_densidad']);?>&dir=<?php echo base64_encode($dir_anx);?>" target="_blank" title="Descargar archivo" class="btn-link btn-xs"><i class="fa fa-download"></i> <?php echo $row['anexo_humedad_densidad'];?></a></td>
							<td><?php echo number_format($row['porcen_granos_partidos'],1);?></td>
							<td><a href="filedownload.php?file=<?php echo base64_encode($row['anexo_granos_partidos']);?>&dir=<?php echo base64_encode($dir_anx);?>" target="_blank" title="Descargar archivo" class="btn-link btn-xs"><i class="fa fa-download"></i> <?php echo $row['anexo_granos_partidos'];?></a></td>
							<td><?php echo number_format($row['porcen_limpieza'],1);?></td>
							<td><a href="filedownload.php?file=<?php echo base64_encode($row['anexo_porcen_limpieza']);?>&dir=<?php echo base64_encode($dir_anx);?>" target="_blank" title="Descargar archivo" class="btn-link btn-xs"><i class="fa fa-download"></i> <?php echo $row['anexo_porcen_limpieza'];?></a></td>
							<td><?php echo number_format($row['porcen_granos_quemados'],1);?></td>
							<td><a href="filedownload.php?file=<?php echo base64_encode($row['anexo_granos_quemados']);?>&dir=<?php echo base64_encode($dir_anx);?>" target="_blank" title="Descargar archivo" class="btn-link btn-xs"><i class="fa fa-download"></i> <?php echo $row['anexo_granos_quemados'];?></a></td>
							<td><?php echo $row['producto_otros'];?></td>
							<td><?php echo number_format($row['porcen_otros_granos'],1);?></td>
							<td><a href="filedownload.php?file=<?php echo base64_encode($row['anexo_otros_granos']);?>&dir=<?php echo base64_encode($dir_anx);?>" target="_blank" title="Descargar archivo" class="btn-link btn-xs"><i class="fa fa-download"></i> <?php echo $row['anexo_otros_granos'];?></a></td>
						</tr>	
						<?php $i++;}?>
					</tbody>
				</table>
			</div>
		</div>
	 </div> 
</div>