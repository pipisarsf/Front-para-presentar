<?php
    include("conexion.php");
    if(isset($_POST['send'])){
        if(
            isset($_POST['name']) && strlen($_POST['name']) >= 1 &&
            isset($_POST['password']) && strlen($_POST['password']) >= 1 &&
            isset($_POST['email']) && strlen($_POST['email']) >= 1
        ){
            $name = trim($_POST['name']);
            $password = trim($_POST['password']);
            $email = trim($_POST['email']);
            $fecha = date("d/m/y");
            $consulta = "INSERT INTO docente(nombre, cotraseña, email, fecha)
                VALUES ('$name', '$password', '$email', '$fecha')";

            $resultado = mysqli_query($conex, $consulta);

            if($resultado){
                ?>
                    <h2 class="success">Tu registro ha sido completado</h2>
                <?php
            } else{
                ?>
                    <h2 class="error">No se pudo realizar su registro. Intente nuevamente</h2>
                <?php
            }

        }
    }
?>
