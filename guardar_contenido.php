<?php
session_start();

header('Content-Type: application/json');

$response = [];

try {
    if (!isset($_POST['materia_id'], $_POST['contenido_id'], $_POST['estudiante_id'], $_POST['nivel_desempeno'])) {
        throw new Exception("Datos incompletos.");
    }

    $materia_id = (int)$_POST['materia_id'];
    $contenido_id = (int)$_POST['contenido_id'];
    $alumno_id = (int)$_POST['estudiante_id'];
    $estadoContenido = $_POST['nivel_desempeno'];

    $conn = mysqli_connect("localhost", "root", "", "practicaprofesional");
    if (!$conn) {
        throw new Exception("Error de conexión: " . mysqli_connect_error());
    }

    $stmt_contenido = $conn->prepare("SELECT id FROM contenidos WHERE id = ?");
    $stmt_contenido->bind_param("i", $contenido_id);
    $stmt_contenido->execute();
    $resultado_contenido = $stmt_contenido->get_result();

    if ($resultado_contenido->num_rows === 0) {
        throw new Exception("Error: El contenido no existe.");
    }

    $stmt_alumno = $conn->prepare("SELECT id FROM estudiante WHERE id = ?");
    $stmt_alumno->bind_param("i", $alumno_id);
    $stmt_alumno->execute();
    $resultado_alumno = $stmt_alumno->get_result();

    if ($resultado_alumno->num_rows === 0) {
        throw new Exception("Error: El alumno no existe.");
    }

    $stmt = $conn->prepare("INSERT INTO rubrica (materia_id, contenido_id, alumno_id, estadoContenido) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $materia_id, $contenido_id, $alumno_id, $estadoContenido);

    if (!$stmt->execute()) {
        throw new Exception("Error al guardar los datos: " . $stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Datos guardados exitosamente.';

    $stmt_contenido->close();
    $stmt_alumno->close();
    $stmt->close();
    mysqli_close($conn);
} catch (Exception $e) {
    $response['success'] = false;
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>
