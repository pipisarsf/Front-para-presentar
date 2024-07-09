<?php
header('Content-Type: application/json');

try {
    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }

    $datos = ['asignatura' => [], 'contenidos' => [], 'estudiantes' => []];

    $query_asignaturas = "SELECT id, nombre_asignatura FROM asignatura";
    $result_asignaturas = mysqli_query($conn, $query_asignaturas);
    if ($result_asignaturas) {
        while ($row = mysqli_fetch_assoc($result_asignaturas)) {
            $datos['asignaturas'][] = $row;
        }
    } else {
        throw new Exception("Error al obtener asignaturas: " . mysqli_error($conn));
    }

    $query_contenidos = "SELECT id, nombre_contenido, materia_id FROM contenidos";
    $result_contenidos = mysqli_query($conn, $query_contenidos);
    if ($result_contenidos) {
        while ($row = mysqli_fetch_assoc($result_contenidos)) {
            $datos['contenidos'][] = $row;
        }
    } else {
        throw new Exception("Error al obtener contenidos: " . mysqli_error($conn));
    }

    $query_estudiantes = "SELECT id, nombre FROM estudiante";
    $result_estudiantes = mysqli_query($conn, $query_estudiantes);
    if ($result_estudiantes) {
        while ($row = mysqli_fetch_assoc($result_estudiantes)) {
            $datos['estudiantes'][] = $row;
        }
    } else {
        throw new Exception("Error al obtener estudiantes: " . mysqli_error($conn));
    }

    mysqli_close($conn);

    echo json_encode($datos);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
