<?php
session_start();

if (!isset($_SESSION['docente_id'])) {
    echo json_encode(["error" => "Docente no autenticado"]);
    exit();
}

$docente_id = $_SESSION['docente_id'];
include("conexion.php");

$datos = [];

try {
    $query_asignatura = "SELECT id, nombre_materia FROM asignatura WHERE docente_id = $docente_id";
    $result_asignatura = mysqli_query($conex, $query_asignatura);
    if (!$result_asignatura) {
        throw new Exception("Error al ejecutar la consulta de asignaturas: " . mysqli_error($conex));
    }
    $datos['asignaturas'] = mysqli_fetch_all($result_asignatura, MYSQLI_ASSOC);

    $query_estudiante = "SELECT id, nombre FROM estudiante";
    $result_estudiante = mysqli_query($conex, $query_estudiante);
    if (!$result_estudiante) {
        throw new Exception("Error al ejecutar la consulta de estudiantes: " . mysqli_error($conex));
    }
    $datos['estudiantes'] = mysqli_fetch_all($result_estudiante, MYSQLI_ASSOC);

    $query_contenidos = "SELECT id, nombre_contenido, materia_id FROM contenidos";
    $result_contenidos = mysqli_query($conex, $query_contenidos);
    if (!$result_contenidos) {
        throw new Exception("Error al ejecutar la consulta de contenidos: " . mysqli_error($conex));
    }
    $datos['contenidos'] = mysqli_fetch_all($result_contenidos, MYSQLI_ASSOC);

    $query_docente = "SELECT id, nombre FROM docente WHERE id = $docente_id";
    $result_docente = mysqli_query($conex, $query_docente);
    if (!$result_docente) {
        throw new Exception("Error al ejecutar la consulta del docente: " . mysqli_error($conex));
    }
    $datos['docente'] = mysqli_fetch_assoc($result_docente);

    echo json_encode($datos);

} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
} finally {
    mysqli_close($conex);
}
?>
