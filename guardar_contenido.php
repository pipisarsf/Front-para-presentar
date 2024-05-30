<?php
session_start();

if (isset($_POST['materia'], $_POST['contenido'], $_POST['alumno'], $_POST['estadoContenido'])) {
    $materia_id = $_POST['materia'];
    $contenido_id = $_POST['contenido'];
    $alumno_id = $_POST['alumno'];
    $estadoContenido = $_POST['estadoContenido'];

    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // Verificar si ya existe un registro con la misma materia, contenido y estudiante
    $consulta_verificar = "SELECT * FROM contenido_estudiante WHERE contenido_id = ? AND estudiante_id = ?";
    $stmt_verificar = mysqli_prepare($conn, $consulta_verificar);

    if (!$stmt_verificar) {
        die("Error en la preparación de la consulta de verificación: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_verificar, "ii", $contenido_id, $alumno_id);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

    if (!$resultado_verificar) {
        echo "Error en la consulta de verificación: " . mysqli_error($conn);
    } else {
        if (mysqli_num_rows($resultado_verificar) == 0) {
            // Insertar los datos en la tabla contenido_estudiante
            $consulta_insertar = "INSERT INTO contenido_estudiante (contenido_id, estudiante_id, nivel_desempeno) VALUES (?, ?, ?)";
            $stmt_insertar = mysqli_prepare($conn, $consulta_insertar);
            
            if (!$stmt_insertar) {
                die("Error en la preparación de la consulta de inserción: " . mysqli_error($conn));
            }
            
            mysqli_stmt_bind_param($stmt_insertar, "iis", $contenido_id, $alumno_id, $estadoContenido);
            
            if (mysqli_stmt_execute($stmt_insertar)) {
                echo "Datos guardados correctamente.";
            } else {
                echo "Error al guardar los datos: " . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt_insertar);
        } else {
            echo "Ya existe un registro para esta combinación de materia, contenido y estudiante.";
        }

        mysqli_stmt_close($stmt_verificar);
    }

    mysqli_close($conn);
} else {
    echo "No se han recibido los datos correctamente.";
}
?>

