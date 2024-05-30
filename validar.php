<?php
session_start();

if(isset($_POST['nombre'], $_POST['password'])) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    $consulta = "SELECT id FROM docente WHERE nombre = '$nombre' AND password = '$password'";
    $resultado = mysqli_query($conn, $consulta);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $row = mysqli_fetch_assoc($resultado);
        $_SESSION['docente_id'] = $row['id']; // Guardar el ID del docente en la sesión
        header("Location: contenidos.html");
        exit();
    } else {
       
        $error = "NOMBRE DE USUARIO O CONTRASEÑA INCORRECTOS";
        echo "<script type='text/javascript'>alert('$error');</script>";
        include("index.html");

    }

    mysqli_free_result($resultado);
    mysqli_close($conn);
} else {
    echo "No se han recibido los datos correctamente.";
}
?>
