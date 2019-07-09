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

	if(isset($_POST['guardar'])){
		$dni=$_POST['dni'];
		$iId_responsable=$_POST['iId_responsable'];
		$campania=$_POST['campania'];
		$idDist=$_POST['id_Dist'];
		$serieDist=$_POST['serie_Dist'];
		$fecasignacion=$_POST['fec_asignacion'];

		
		if(!empty($dni) && !empty($iId_responsable) && !empty($campania) && !empty($idDist) && !empty($serieDist) && !empty($fecasignacion) ){
			//if(!filter_var($correo,FILTER_VALIDATE_EMAIL)){
			//	echo "<script> alert('Correo no valido');</script>";
			//}else{
				
				$consulta_insert=$con->prepare('INSERT INTO tasignacionzona(dni, fecasignacion, campania, idDist, serieDist, iId_responsable) VALUES(:dni,:fec_asignacion,:campania,:id_Dist,:serie_Dist,:iId_responsable)');
				$consulta_insert->execute(array(
					':dni' =>$dni,
					':fec_asignacion' =>$fecasignacion,
					':campania' =>$campania,
					':id_Dist' =>$idDist,
					':serie_Dist' =>$serieDist,
					':iId_responsable' =>$iId_responsable,
				));	
				
				echo "<script> alert('Registro se guardó correctamente');</script>";
				//header('Location: index.php');
			//}
		}else{
			echo "<script> alert('Los campos estan vacios');</script>";
		}
	}

	$sentencia_select=$con->prepare('SELECT idDepa, serieDepa ,departamento FROM ubdepartamento ORDER BY idDepa asc');
	$sentencia_select->execute();
	$resultado=$sentencia_select->fetchAll();


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Nueva Asignación</title>
	<link rel="stylesheet" href="css/estilo.css">

	<script language="javascript" src="js/jquery-3.1.1.min.js"></script>
		
		<script language="javascript">
			$(document).ready(function(){
					
				$("#depa").change(function () {
					debugger;
					$('#dist').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');
					$('#cpob').find('option').remove().end().append('<option value="whatever"></option>').val('whatever');

					$("#depa option:selected").each(function () {
						idDepa = $(this).val();

						$.post("includes/getProv.php", { idDepa: idDepa }, function(data){
							$("#prov").html(data);																					
						});            
					});
				})
			});
			
			$(document).ready(function(){	
							
				$("#prov").change(function () {
					debugger;
					$("#prov option:selected").each(function () {
						
						idProv = $(this).val();
						$.post("includes/getDist.php", { idProv: idProv }, function(data){
							$("#dist").html(data);							
						});            
					});
				})
			});

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
				<input type="text" name="dni" placeholder="Ingrese N° DNI" class="input__text" maxlength="<?php echo $mxlen?>" onKeypress="validarSiNumero(this.value);" >
				<input type="hidden" id="campania" name="campania" value="<?php echo $ano; ?>" />
				<input type="hidden" id="id_Dist" name="id_Dist" value="" />
				<input type="hidden" id="serie_Dist" name="serie_Dist" value="" />
				<input type="hidden" id="iId_responsable" name="iId_responsable" value="<?php echo $idResp?>" />			
				<input type="text" name="fec_asignacion" id="fec_asignacion" class="input__text" value="<?php echo date("d/m/Y h:m:s", strtotime($fecha)); ?>" >
			</div>
			<br>

			<h2>ZONA UBIGEO</h2>
			<hr>
			<br>
			<div class="form-group">				
								
				<select name="depa" id="depa" class="input__text">
				<option value="0">Seleccionar Departamento</option>		
				<?php foreach($resultado as $fila):
					 echo "<option value='".$fila['idDepa']."'>".$fila['serieDepa']." ".utf8_encode($fila['departamento'])."</option>";
					endforeach?>			
				</select>
				
				<select name="dist" id="dist"  class="input__text">					
				</select>
				
			</div>		

			<div class="form-group">	
				<select name="prov" id="prov"  class="input__text">				
				</select>

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
