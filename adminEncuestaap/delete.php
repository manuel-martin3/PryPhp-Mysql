<?php 

	include_once 'conexion.php';
	if(isset($_POST['id'])){
		$id=(int) $_POST['id'];
		$delete=$con->prepare('DELETE FROM tasignacionzona WHERE id=:id');
		$delete->execute(array(
			':id'=>$id
		));
		header('Location: index.php');
	}else{
		header('Location: index.php');
	}


 ?>