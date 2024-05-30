<?php
// Conexión a la base de datos
$conex = mysqli_connect("localhost", "root", "", "practicaprofesional");

if (!$conex) {
    die("La página no está funcionando: " . mysqli_connect_error());
}

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizar y validar los datos recibidos
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Hash de la contraseña
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // Consulta preparada para insertar datos
    $stmt = $conex->prepare("INSERT INTO usuarios (nombre, password) VALUES (?, ?)");
    if ($stmt === false) {
        die("Error en la preparación de la consulta: " . htmlspecialchars($conex->error));
    }
    $stmt->bind_param("ss", $nombre, $password_hashed);

    if ($stmt->execute() === TRUE) {
        echo "Nuevo registro creado con éxito";
    } else {
        echo "Error: " . htmlspecialchars($stmt->error);
    }

    // Cerrar el statement
    $stmt->close();
}

// Cerrar la conexión
$conex->close();
?>
