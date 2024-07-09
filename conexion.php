<?php
$conex = mysqli_connect("localhost", "root", "", "practicaprofesional");

if (!$conex) {
    die("La página no está funcionando: " . mysqli_connect_error());
} else {
    echo "Conexión a base de datos exitosa";
}
?>
