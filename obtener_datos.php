<?php
header('Content-Type: application/json');
session_start();
ob_start(); // Inicia el almacenamiento en búfer de salida

$response = [];

try {
    $conn = new mysqli("localhost", "root", "", "practicaprofesional");
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Consulta para obtener asignaturas
    $asignaturas_result = $conn->query("SELECT id, nombre_materia FROM asignatura");
    if (!$asignaturas_result) {
        throw new Exception("Error en la consulta de asignaturas: " . $conn->error);
    }

    $asignaturas = [];
    while ($row = $asignaturas_result->fetch_assoc()) {
        $asignaturas[] = $row;
    }
    $response['asignaturas'] = $asignaturas;

    // Consulta para obtener contenidos
    $contenidos_result = $conn->query("SELECT id, nombre_contenido, materia_id FROM contenidos");
    if (!$contenidos_result) {
        throw new Exception("Error en la consulta de contenidos: " . $conn->error);
    }

    $contenidos = [];
    while ($row = $contenidos_result->fetch_assoc()) {
        $contenidos[] = $row;
    }
    $response['contenidos'] = $contenidos;

    // Consulta para obtener estudiantes
    $estudiantes_result = $conn->query("SELECT id, nombre FROM estudiante");
    if (!$estudiantes_result) {
        throw new Exception("Error en la consulta de estudiantes: " . $conn->error);
    }

    $estudiantes = [];
    while ($row = $estudiantes_result->fetch_assoc()) {
        $estudiantes[] = $row;
    }
    $response['estudiantes'] = $estudiantes;

    // Consulta para obtener datos del docente actual
    if (isset($_SESSION['docente_id'])) {
        $docente_id = $_SESSION['docente_id'];
        $docente_result = $conn->query("SELECT nombre FROM docente WHERE id = $docente_id");
        if (!$docente_result) {
            throw new Exception("Error en la consulta de docente: " . $conn->error);
        }

        $docente = $docente_result->fetch_assoc();
        $response['docente'] = $docente;
    } else {
        $response['docente'] = null;
    }

    ob_end_clean(); // Limpia el búfer de salida y desactiva el almacenamiento en búfer
    echo json_encode($response);
} catch (Exception $e) {
    ob_end_clean(); // Limpia el búfer de salida y desactiva el almacenamiento en búfer
    $response['error'] = $e->getMessage();
    echo json_encode($response);
} finally {
    if (isset($conn) && $conn->connect_errno == 0) {
        $conn->close();
    }
}
?>
