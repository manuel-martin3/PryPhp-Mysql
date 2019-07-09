<?php

	include_once '../conexion.php';
	
	$idProv = $_POST['idProv'];
	
	$sentencia_select=$con->prepare("SELECT idDist, serieDist ,distrito FROM ubdistrito WHERE idProv = $idProv ORDER BY distrito");
	$sentencia_select->execute();
	$resultado=$sentencia_select->fetchAll();
	
	$html= "<option value='0'>Seleccionar Distrito</option>";
	foreach($resultado as $fila){
		$html.= "<option value='".$fila['idDist']."'>".$fila['serieDist']." ".utf8_encode($fila['distrito'])."</option>";
	}
	
	echo $html;
	
?>