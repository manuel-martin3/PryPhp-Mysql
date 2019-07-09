<?php
	
	include_once '../conexion.php';
	
	$idDist = $_POST['idDist'];
	
	$sentencia_select=$con->prepare("SELECT idCPob, serieCPob ,centropoblado FROM ubcentropoblado WHERE idDist = $idDist ORDER BY centropoblado");
	$sentencia_select->execute();
	$resultado=$sentencia_select->fetchAll();

	$html= "<option value='0'>Seleccionar Cent. Poblado</option>";
	
	foreach($resultado as $fila){
		$html.= "<option value='".$fila['idCPob']."'>".$fila['serieCPob']." ".utf8_encode($fila['centropoblado'])."</option>";
	}
	
	
	echo $html;
	
?>		