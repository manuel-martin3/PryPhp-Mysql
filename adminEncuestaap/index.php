<?php
	include_once 'conexion.php';


	$query_asignacion=('SELECT a.id as id, a.dni as dni, a.fecasignacion as fecasignacion, 
		a.campania as campania ,a.idDist as idDist, a.serieDist as serieDist, 
		b.distrito as distrito ,a.iId_responsable as iId_responsable
		FROM tasignacionzona a 
		inner join ubdistrito b on a.idDist=b.idDist'
	); 

	$sentencia_select=$con->prepare($query_asignacion.' ORDER BY a.campania DESC limit 100');
	$sentencia_select->execute();
	$resultado=$sentencia_select->fetchAll();
	
	// metodo buscar
	if(isset($_POST['btn_buscar'])){
		$buscar_text=$_POST['buscar'];
		$select_buscar=$con->prepare(
			 $query_asignacion.' WHERE a.dni LIKE :campo OR b.distrito LIKE :campo;'
		);

		$select_buscar->execute(array(
			':campo' =>"%".trim($buscar_text)."%"
		));
		
		$resultado=$select_buscar->fetchAll();		
	}

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Inicio</title>
	
	<link rel="stylesheet" href="css/estilo.css">	
	<script language="javascript" src="js/jquery-3.1.1.min.js"></script>
	
	<script>
				
		function filtro() {
		// Declare variables 
		var input, filter, table, tr, td, i, j, visible;
		input = document.getElementById("buscar");
		filter = input.value.toUpperCase();
		table = document.getElementById("tabla");
		tr = table.getElementsByTagName("tr");
		

			// Loop through all table rows, and hide those who don't match the search query
			for (i = 1; i < tr.length; i++) {
				visible = false;
				/* Obtenemos todas las celdas de la fila, no sólo la primera */
				td = tr[i].getElementsByTagName("td");
				for (j = 0; j < td.length; j++) {
					if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
						visible = true;
					}
				}
				
				if (visible === true) {
					tr[i].style.display = "";
				} else {
					tr[i].style.display = "none";					
				}
				
			}
		}		
				
	</script>

	<script>
	
		$(document).ready( function () {
			$('#tabla').DataTable();
		} );
			
	</script>

	<script>
		function validar(id) {								
			if(confirm("¿Estas seguro de eliminar el registro?")){
					$.post("delete.php", { id: id }, function(){							
						location.reload();
						alert('Registro se elimino correctamente');																
					});  
			}else{
				return false;
			}				
		}
		
	</script>
</head>
<body>
	<div class="contenedor">
		<h2>MÓDULO DE ADMINISTRACIÓN</h2>
		<div class="barra__buscador">
			<form action="" class="formulario" method="post">
				<input type="text" name="buscar" id="buscar" placeholder="buscar DNI o Distrito" 
					value="<?php 
					if(isset($buscar_text)) echo $buscar_text; 
						?>" class="input__text" onkeypress="filtro();">

				<input type="submit" class="btn" name="btn_buscar" id="btn_buscar"  value="Buscar">
					<a href="insert.php" name="nuevo" class="btn btn__nuevo">Nuevo</a>
			</form>
		</div>
		<table id="tabla" name="tabla" class="table">
			
			<tr class="head">
				<td>Id</td>
				<td>DNI</td>
				<td>F.Asignada</td>
				<td>Distrito</td>
				<td>Ubigeo</td>
				<td>Id Dist</td>
				<td colspan="2">Acción</td>
			</tr>
			<?php foreach($resultado as $fila):
					$newDate = date("d/m/Y", strtotime($fila['fecasignacion']));
					?>
				<tr>					
					<td><?php echo $fila['id']; ?></td>
					<td><?php echo trim($fila['dni']); ?></td>
					<td><?php echo trim(utf8_encode($newDate)); ?></td>
					<td><?php echo $fila['distrito']; ?></td>
					<td><?php echo $fila['serieDist']; ?></td>
					<td><?php echo trim($fila['idDist']); ?></td>
					<td>
						<a href="update.php?id=<?php echo $fila['id']; ?>" class="btn__update" >Editar</a>
					</td>
					<td>						
						<a id="del" name="del" href="#" onclick="javascript:validar('<?php echo $fila['id']; ?>')" class="btn__delete">Eliminar</a>					
					</td>					
				</tr>
			<?php endforeach ?>
			
		</table>		
		<div id="pagination-container" class="light-theme simple-pagination"></div>
	</div>
	
</body>
</html>