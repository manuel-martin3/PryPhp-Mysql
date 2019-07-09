<?php
	include_once 'conexion.php';

	$query_general=('select YEAR(NOW())sysano, NOW() as sysfecha');
	$sentencia_select=$con->prepare($query_general);
	$sentencia_select->execute();
	$resp=$sentencia_select->fetchAll();
	$mxlen=8;
	$idResp="0000000001";
	
	$ano=$resp[0]['sysano'];
	$fecha=$resp[0]['sysfecha'];
	$_POST['fec_asignacion']=$fecha;

	if(isset($_GET['id'])){
		$id=(int) $_GET['id'];
		$idditrito=0;
		$buscar_id=$con->prepare('SELECT dni, fecasignacion, campania, idDist, serieDist, iId_responsable FROM tasignacionzona WHERE id=:id ');
		$buscar_id->execute(array(
			':id'=>$id			
		));
		$resultado=$buscar_id->fetch();

		$buscarListDis_id=$con->prepare(
			'SELECT a.idDist as idDist, a.serieDist as serieDist, a.distrito as distrito, a.idProv as idProv FROM ubdistrito a 
			WHERE a.idProv= (select idProv FROM ubdistrito WHERE idDist ='.(int)$resultado['idDist'].' LIMIT 1)');  
		$buscarListDis_id->execute();		
		$resultado_listadist=$buscarListDis_id->fetchAll();
		
		$buscarProv_id=$con->prepare("SELECT idProv, serieProv, provincia, idDepa FROM ubprovincia WHERE idProv =".(int)$resultado_listadist[0]['idProv']);  
		$buscarProv_id->execute();		
		$resultado_prov=$buscarProv_id->fetch();

		$buscarDepa_id=$con->prepare("SELECT idDepa, serieDepa, departamento FROM ubdepartamento WHERE idDepa =".(int)$resultado_prov['idDepa']);  
		$buscarDepa_id->execute();		
		$resultado_depa=$buscarDepa_id->fetch();

			
	}else{
		header('Location: index.php');
	}

	if(isset($_POST['guardar'])){

		$dni=$_POST['dni'];
		$iId_responsable=$_POST['iId_responsable'];		
		$idDist=$_POST['id_Dist'];
		$serieDist=$_POST['serie_Dist'];
		$fecasignacion=$_POST['fec_asignacion'];

		$id=(int) $_GET['id'];
		
		if(!empty($dni) && !empty($iId_responsable) && !empty($idDist) && !empty($serieDist) && !empty($fecasignacion) ){	
			//if(!filter_var($correo,FILTER_VALIDATE_EMAIL)){
				//echo "<script> alert('Correo no valido');</script>";
			//}else{
				$consulta_update=$con->prepare(' UPDATE tasignacionzona SET  
					dni=:dni,
					fecasignacion=:fec_asignacion,					
					idDist =:id_Dist,
					serieDist =:serie_Dist,
					iId_responsable =:iId_responsable
					WHERE id=:id;'
				);
				
				$consulta_update->execute(array(					
					':dni' =>$dni,
					':fec_asignacion' =>$fecasignacion,					
					':id_Dist' =>$idDist,
					':serie_Dist' =>$serieDist,
					':iId_responsable' =>$iId_responsable,
					':id' =>$id
				));
				
				header('Location: index.php');
			//}
		}else{
			echo "<script> alert('Los campos estan vacios');</script>";
		}
	}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Editar Asignación</title>
	<link rel="stylesheet" href="css/estilo.css">
	<script language="javascript" src="js/jquery-3.1.1.min.js"></script>

	<script>
				$(document).ready(function(){	
							
				$("#dist").change(function () {
					
					$("#dist option:selected").each(function () {						
						idDist = $(this).val();						
						$('input[name="id_Dist"]').val(idDist);
						distselect=$('#dist option:selected').text();
						$('input[name="serie_Dist"]').val(distselect.substr(0,6));					
						
						$.post("includes/getCpob.php", { idDist: idDist }, function(data){
							$("#cpob").html(data);							
						});            
					});
				})
			});
	</script>

	<script>
		$(document).ready(function(){	
			$("#fec_asignacion").attr("disabled", true);
			$("#depa").attr("disabled", true);
			$("#prov").attr("disabled", true);
			
		});			
	</script>

	<script>
	function validarSiNumero(numero){
		if (!/^([0-9])*$/.test(numero))
		alert("El valor " + numero + " no es un número, verifique");
	}
	</script>

</head>
<body>
	<div class="contenedor">
		<h2>ASIGNACIÓN DE ZONA DE ENCUESTA</h2>
		<hr>
		<br>
		<form action="" method="post">
			<div class="form-group">
				<input type="text" name="dni" id="dni" placeholder="Ingrese N° DNI" value="<?php if($resultado) echo $resultado['dni']; ?>" class="input__text"  maxlength="<?php echo $mxlen?>" onKeypress="validarSiNumero(this.value);" >
				<input type="text" name="fec_asignacion" id="fec_asignacion" value="<?php if($resultado) echo $resultado['fecasignacion']; ?>" class="input__text">
			</div>
			<br>

			<h2>ZONA UBIGEO</h2>
			<hr>
			<br>

			<div class="form-group">
				<input type="text" name="depa" id="depa" value="<?php if($resultado_depa) echo $resultado_depa['serieDepa']." ".utf8_encode($resultado_depa['departamento']); ?>" class="input__text">
				
				<select name="dist" id="dist" class="input__text">
				<option value="0">Seleccionar Distrito</option>		
				<?php foreach($resultado_listadist as $fila):				
					echo "<option value='".$fila['idDist']."'>".$fila['serieDist']." ".utf8_encode($fila['distrito'])."</option>";
					endforeach?>			
				</select>
				<input type="hidden" id="id_Dist" name="id_Dist" value="" />
				<input type="hidden" id="serie_Dist" name="serie_Dist" value="" />
				<input type="hidden" id="iId_responsable" name="iId_responsable" value="<?php echo $idResp?>" />							
			</div>
			<div class="form-group">
				<input type="text" name="prov" id="prov" value="<?php if($resultado_prov) echo $resultado_prov['serieProv']." ".utf8_encode($resultado_prov['provincia']); ?>" class="input__text">			
				<select name="cpob" id="cpob"  class="input__text">					
				</select>

			</div>
			<div class="btn__group">
				<a href="index.php" class="btn btn__danger">Cancelar</a>
				<input type="submit" name="guardar" value="Guardar" class="btn btn__primary">
			</div>
		</form>
	</div>
</body>
</html>
