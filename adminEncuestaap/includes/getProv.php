<?php
	
	include_once '../conexion.php';
	
	$idDepa = $_POST['idDepa'];
	
	$sentencia_select=$con->prepare("SELECT idProv, serieProv, provincia FROM ubprovincia WHERE idDepa = $idDepa ORDER BY provincia");
	$sentencia_select->execute();
	$resultado=$sentencia_select->fetchAll();

	$html= "<option value='0'>Seleccionar Provincia</option>";
	
	foreach($resultado as $fila){
		$html.= "<option value='".$fila['idProv']."'>".$fila['serieProv']." ".utf8_encode($fila['provincia'])."</option>";
	}
	
	
	echo $html;
	
?>		