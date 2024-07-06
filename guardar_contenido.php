<?php
session_start();

header('Content-Type: application/json');

if (isset($_POST['materia'], $_POST['contenido'], $_POST['alumno'], $_POST['estadoContenido'])) {
    $materia_id = (int)$_POST['materia'];
    $contenido_id = (int)$_POST['contenido'];
    $alumno_id = (int)$_POST['alumno'];
    $estadoContenido = $_POST['estadoContenido'];

    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        echo json_encode(["error" => "Error de conexión: " . mysqli_connect_error()]);
        exit();
    }

    // Verificar que contenido_id existe en la tabla contenidos
    $consulta_contenido = "SELECT id FROM contenidos WHERE id = ?";
    $stmt_contenido = mysqli_prepare($conn, $consulta_contenido);
    mysqli_stmt_bind_param($stmt_contenido, "i", $contenido_id);
    mysqli_stmt_execute($stmt_contenido);
    $resultado_contenido = mysqli_stmt_get_result($stmt_contenido);

    if (mysqli_num_rows($resultado_contenido) === 0) {
        echo json_encode(["error" => "Error: El contenido no existe."]);
        mysqli_stmt_close($stmt_contenido);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_close($stmt_contenido);

    // Verificar que alumno_id existe en la tabla estudiante
    $consulta_alumno = "SELECT id FROM estudiante WHERE id = ?";
    $stmt_alumno = mysqli_prepare($conn, $consulta_alumno);
    mysqli_stmt_bind_param($stmt_alumno, "i", $alumno_id);
    mysqli_stmt_execute($stmt_alumno);
    $resultado_alumno = mysqli_stmt_get_result($stmt_alumno);

    if (mysqli_num_rows($resultado_alumno) === 0) {
        echo json_encode(["error" => "Error: El estudiante no existe."]);
        mysqli_stmt_close($stmt_alumno);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_close($stmt_alumno);

    // Verificar si ya existe un registro con la misma materia, contenido y estudiante
    $consulta_verificar = "SELECT * FROM contenido_estudiante WHERE contenido_id = ? AND estudiante_id = ?";
    $stmt_verificar = mysqli_prepare($conn, $consulta_verificar);

    if (!$stmt_verificar) {
        echo json_encode(["error" => "Error en la preparación de la consulta de verificación: " . mysqli_error($conn)]);
        mysqli_close($conn);
        exit();
    }

    mysqli_stmt_bind_param($stmt_verificar, "ii", $contenido_id, $alumno_id);
    mysqli_stmt_execute($stmt_verificar);
    $resultado_verificar = mysqli_stmt_get_result($stmt_verificar);

    if (!$resultado_verificar) {
        echo json_encode(["error" => "Error en la consulta de verificación: " . mysqli_error($conn)]);
    } else {
        if (mysqli_num_rows($resultado_verificar) == 0) {
            // Insertar los datos en la tabla contenido_estudiante
            $consulta_insertar = "INSERT INTO contenido_estudiante (contenido_id, estudiante_id, nivel_desempeno) VALUES (?, ?, ?)";
            $stmt_insertar = mysqli_prepare($conn, $consulta_insertar);

            if (!$stmt_insertar) {
                echo json_encode(["error" => "Error en la preparación de la consulta de inserción: " . mysqli_error($conn)]);
                mysqli_stmt_close($stmt_verificar);
                mysqli_close($conn);
                exit();
            }

            // Verificar tipos de datos y parámetros
            $contenido_id = (int)$contenido_id;
            $alumno_id = (int)$alumno_id;
            $estadoContenido = (string)$estadoContenido;

            // Añadir registros de depuración
            error_log("contenido_id: $contenido_id, alumno_id: $alumno_id, estadoContenido: $estadoContenido");

            mysqli_stmt_bind_param($stmt_insertar, "iis", $contenido_id, $alumno_id, $estadoContenido);

            if (mysqli_stmt_execute($stmt_insertar)) {
                echo json_encode(["success" => "Datos guardados correctamente."]);
            } else {
                echo json_encode(["error" => "Error al guardar los datos: " . mysqli_stmt_error($stmt_insertar)]);
            }

            mysqli_stmt_close($stmt_insertar);
        } else {
            echo json_encode(["error" => "Ya existe un registro para esta combinación de materia, contenido y estudiante."]);
        }

        mysqli_stmt_close($stmt_verificar);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["error" => "No se han recibido los datos correctamente."]);
}
?>
