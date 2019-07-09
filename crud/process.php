<?php
   
    session_start();

    require 'config.php';
    $db_host=HOST;
    $db_user=USER_NAME;
    $db_pass=PASS;
    $db_name=DB_NAME;
    
    $mysqli = new mysqli($db_host,$db_user, $db_pass, $db_name) or die(mysqli_error($mysqli));

    $id=0;
    $update = false;
    $name='';
    $location='';

    if (!empty($_POST))
    {
        if(isset($_POST['save'])){
            
            $name=$_POST['name'];
            $location=$_POST['location'];
            
            if($name!="" && $location!=""){
                $mysqli->query("insert into tdata(name, location) values('$name', '$location')")or 
                die($mysqli->error); 

                $_SESSION['mensaje']="Registro ha sido guardado...";
                $_SESSION['msg_type']="success";               
            }else{
                $_SESSION['mensaje']="Completar los campos...";
                $_SESSION['msg_type']="danger";  
            }

            header("location: index.php");
        }
    } 

    if(isset($_GET['delete'])){
        $id = $_GET['delete'];       
        $mysqli->query("delete from tdata where id=$id")or die(mysqli_error($mysqli));

        $_SESSION['mensaje']="Registro ha sido eliminado...";
        $_SESSION['msg_type']="danger";

        header("location: index.php");
    }    

    if(isset($_GET['edit'])){
        $id = $_GET['edit'];     
        $update = true;  
        $result = $mysqli->query("select * from tdata where id = $id") or die($mysqli->error()) ;
        if(count($result)==1){
            $row = $result->fetch_array();
            $name = $row['name'];
            $location = $row['location'];
        }
    }

    if(isset($_POST['update'])){
        $id = $_POST['id'];     
        $name = $_POST['name'];     
        $location = $_POST['location'];

        $result = $mysqli->query("update tdata set name = '$name', location= '$location' where id=$id") 
            or die($mysqli->error()) ;
        
        $_SESSION['mensaje']="Registro ha sido actualizado...";
        $_SESSION['msg_type']="warning";

         header("location: index.php");
    }
?>