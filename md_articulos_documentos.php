<?php  
require_once("includes/conexion.php");

$ParamCons=array(
	"'".$_POST['DocType']."'",
	"'".$_POST['DocEntry']."'",
	$_POST['Todos']
);
$SQL=EjecutarSP('usp_InformeVentas_DetalleArticulos',$ParamCons);

?>
<div class="form-group">
	<div class="ibox-content">
		<div class="table-responsive">
		<table class="table table-bordered table-hover" >
			<thead>
			<tr>
				<th>#</th>
				<th>Código artículo</th>
				<th>Nombre de artículo</th>
				<th>Unidad de medida</th>
				<th>Cantidad</th>
				<th>Precio</th>
				<th>Total</th>
				<th>Clase de artículo</th>
				<th>Grupo de artículo</th>
			</tr>
			</thead>
			<tbody>
			<?php 
				$i=1;
				$SubGrupo="";
				$SubTotal=0;
				$Total=0;
				$sw_Cambio=0;
				while($row=sqlsrv_fetch_array($SQL)){ 
				if($i==1){
					$SubGrupo=$row['DE_ItemType'];
				}
				?>
				<?php 
					if((($SubGrupo!=$row['DE_ItemType'])&&$i>1)||($i==1)){
						if($i>1){
				?>
				<tr>
					<td colspan="6" class="text-success font-bold"><span class="pull-right">SubTotal <?php echo $SubGrupo;?></span></td>
					<td class="text-success font-bold"><?php echo "$".number_format($SubTotal,2);?></td>
					<td colspan="2" class="text-success font-bold">&nbsp;</td>
				</tr>
				<?php 
						}
						$SubGrupo=$row['DE_ItemType'];
						$SubTotal=0;
						
				?>
				<tr>
					<td colspan="9" class="bg-muted text-success font-bold"><?php echo $row['DE_ItemType'];?></td>
				</tr>
				<?php }
				?>
				 <tr>
					<td><?php echo $i;?></td>
					<td><a href="articulos.php?id=<?php echo base64_encode($row['ItemCode']);?>&tl=1" target="_blank"><?php echo $row['ItemCode'];?></a></td>
					<td><?php echo $row['ItemName'];?></td>
					<td><?php echo $row['Unidad'];?></td>
					<td><?php echo number_format($row['Cantidad'],2);?></td>
					<td><?php echo "$".number_format($row['Precio'],2);?></td>
					<td class="text-navy"><?php echo "$".number_format($row['LineTotal'],2);?></td>
					<td><?php echo $row['DE_ItemType'];?></td>
					<td><?php echo $row['ItmsGrpNam'];?></td>
				</tr>
			<?php $i++;
					$SubTotal+=$row['LineTotal'];
					$Total+=$row['LineTotal'];
				}
			?>
				<tr>
					<td colspan="6" class="text-success font-bold"><span class="pull-right">SubTotal <?php echo $SubGrupo;?></span></td>
					<td class="text-success font-bold"><?php echo "$".number_format($SubTotal,2);?></td>
					<td colspan="2" class="text-success font-bold">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" class="text-danger font-bold"><span class="pull-right">TOTAL</span></td>
					<td class="text-danger font-bold"><?php echo "$".number_format($Total,2);?></td>
					<td colspan="2" class="text-danger font-bold">&nbsp;</td>
				</tr>
			</tbody>
		</table>
  		</div>
	</div>
</div>	
<script>
 $(document).ready(function(){
		 
	var table = $('.dataTables-artDocs').DataTable({
		pageLength: 10,
		dom: '<"html5buttons"B>lTfgitp',
		orderCellsTop: false,
		ordering: false,
		rowGroup: {
			dataSrc: 5
		},
		language: {
			"decimal":        "",
			"emptyTable":     "No se encontraron resultados.",
			"info":           "Mostrando _START_ - _END_ de _TOTAL_ registros",
			"infoEmpty":      "Mostrando 0 - 0 de 0 registros",
			"infoFiltered":   "(filtrando de _MAX_ registros)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     "Mostrar _MENU_ registros",
			"loadingRecords": "Cargando...",
			"processing":     "Procesando...",
			"search":         "Filtrar:",
			"zeroRecords":    "Ningún registro encontrado",
			"paginate": {
				"first":      "Primero",
				"last":       "Último",
				"next":       "Siguiente",
				"previous":   "Anterior"
			},
			"aria": {
				"sortAscending":  ": Activar para ordenar la columna ascendente",
				"sortDescending": ": Activar para ordenar la columna descendente"
			}
		},
		buttons: []

	});
 });
</script>