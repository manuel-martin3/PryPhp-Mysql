<?php

include './dbconfig.php';


     function ConsularTabla($tabla) {
        $cn=new daoDemo();  
        $db = $cn->db_connect(); 
        mysqli_set_charset($db,"utf8");
        //simple query        
        $query = "SELECT * FROM ". $tabla.";";        
        $result = mysqli_query($db, $query);        
        $json_formato=Genera_json($result,$tabla);                  
       
        return $json_formato;
     }

     function Genera_json($results, $tabla){
            $filas= array();
            while ($fila = $results->fetch_assoc()) {
                /*$filas[$tabla][]= $fila;    */  
                $filas[]= $fila;
            }

            $json_formato = json_encode($filas);
           
            $extension=".json";
            Crear_fichero($json_formato, $tabla, $extension);        
            var_dump($json_formato);
            return $json_formato;
      }

     function Crear_fichero($json_formato, $tabla, $extension){
            //creamos el archivo.json
            $handler = fopen($tabla.$extension,"w+");
            fwrite($handler, $json_formato);
            fclose($handler);
      }

       




