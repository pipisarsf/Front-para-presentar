<?php
session_start();

if(isset($_POST['nombre'], $_POST['password'])) {
    $username = $_POST['nombre'];
    $password = $_POST['password'];

    // Conectar a la base de datos
    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Sentencia preparada para evitar SQL injection
    $stmt = mysqli_prepare($conn, "SELECT id FROM docente WHERE nombre = ? AND password = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    // Verificar si se encontró un docente
    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $docente_id);
        mysqli_stmt_fetch($stmt);
        
        $_SESSION['docente_id'] = $docente_id; // Guardar el ID del docente en la sesión
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
        
        header("Location: contenidos.html");
        exit();
    } else {
        $error = "Nombre de usuario o contraseña incorrectos.";
        echo "<script type='text/javascript'>alert('$error');</script>";
        include("index.html");
    }
} else {
    echo "No se han recibido los datos correctamente.";
}
?>
