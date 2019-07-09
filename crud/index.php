<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    
</head>
<body>  
    <?php require_once 'process.php'; ?>

    <?php 
        if(isset($_SESSION['mensaje'])): ?>

    <div class="alert alert-<?=$_SESSION['msg_type']?>">
        <?php 
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        ?>
    </div>
    <?php endif ?>
    <div class="container">
        <?php
            require_once 'config.php';
            $db_host=HOST;
            $db_user=USER_NAME;
            $db_pass=PASS;
            $db_name=DB_NAME;
            $consluta=QUERDATA; 

            $mysqli = new mysqli($db_host,$db_user, $db_pass, $db_name) or die(mysqli_error($mysqli));
            $result = $mysqli->query($consluta)or die($mysqli->error);
        ?>
        <div class="row justify-content-center">
            <table class= "table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Ubcación</th>
                        <th colspan="2">Accion</th>
                    </tr>
                </thead>
            <?php while($row=$result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['location']; ?></td>
                    <td>
                        <a href="index.php?edit=<?php echo $row['id']; ?>" class="btn btn-warning">Editar</a>
                        <a href="process.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </table>
        </div>
        <?php
            pre_r($result->fetch_assoc());
            function pre_r($array){
                echo'<pre>';
                print_r($array);
                echo '</pre>';
            }
        ?>

        <div class="row justify-content-center">
        <form action="process.php" method="POST">
            <input type="hidden" name ="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="name" class="form-control" 
                value="<?php echo $name; ?>" placeholder="Ingrese su nombre">
            </div>

            <div class="form-group">
                <label>Ubicacion</label>
                <input type="text" name="location" class="form-control" 
                value="<?php echo $location; ?>" placeholder="Ingrese su ubicacion">
            </div>

            <div class="form-group">
            <?php 
                if($update==true):?>
                <button type="submit" class="btn btn-warning" name="update">Editar</button>
            <?php else: ?>            
                <button type="submit" class="btn btn-primary" name="save">Save</button>
            <?php endif ?>  
            </div>    
        </form>
        </div>
    </div>    
</body>
</html>