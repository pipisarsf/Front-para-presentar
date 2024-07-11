<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['docente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Sesión de docente no iniciada']);
    exit;
}

$docente_id = $_SESSION['docente_id'];

try {
    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }

    $datos = ['asignaturas' => [], 'contenidos' => [], 'estudiantes' => []];

    // Consultar las asignaturas del docente
    $query_asignaturas = "SELECT id, nombre_asignatura FROM asignatura WHERE docente_id = ?";
    $stmt_asignaturas = mysqli_prepare($conn, $query_asignaturas);
    if (!$stmt_asignaturas) {
        throw new Exception("Error en la preparación de la consulta de asignaturas: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_asignaturas, "i", $docente_id);
    mysqli_stmt_execute($stmt_asignaturas);
    $result_asignaturas = mysqli_stmt_get_result($stmt_asignaturas);

    if (!$result_asignaturas) {
        throw new Exception("Error al obtener asignaturas: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result_asignaturas)) {
        $datos['asignaturas'][] = $row;
    }

    mysqli_stmt_close($stmt_asignaturas);

    // Consultar todos los contenidos
    $query_contenidos = "SELECT id, nombre_contenido, materia_id FROM contenidos";
    $result_contenidos = mysqli_query($conn, $query_contenidos);
    if (!$result_contenidos) {
        throw new Exception("Error al obtener contenidos: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result_contenidos)) {
        $datos['contenidos'][] = $row;
    }

    // Consultar todos los estudiantes
    $query_estudiantes = "SELECT id, nombre FROM estudiante";
    $result_estudiantes = mysqli_query($conn, $query_estudiantes);
    if (!$result_estudiantes) {
        throw new Exception("Error al obtener estudiantes: " . mysqli_error($conn));
    }

    while ($row = mysqli_fetch_assoc($result_estudiantes)) {
        $datos['estudiantes'][] = $row;
    }

    mysqli_close($conn);

    echo json_encode($datos);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
