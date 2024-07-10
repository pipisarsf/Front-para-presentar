<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    // Verificar datos recibidos
    if (!isset($_POST['contenido_id'], $_POST['estudiante_id'], $_POST['nivel_desempeno'])) {
        throw new Exception("Datos incompletos.");
    }

    // Obtener y limpiar datos del formulario
    $contenido_id = (int)$_POST['contenido_id'];
    $estudiante_id = (int)$_POST['estudiante_id'];
    $nivel_desempeno = $_POST['nivel_desempeno'];

    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "practicaprofesional");
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // Verificar si el contenido existe en la tabla contenidos
    $stmt_contenido = $conn->prepare("SELECT id FROM contenidos WHERE id = ?");
    $stmt_contenido->bind_param("i", $contenido_id);
    if (!$stmt_contenido->execute()) {
        throw new Exception("Error al verificar el contenido: " . $stmt_contenido->error);
    }
    $resultado_contenido = $stmt_contenido->get_result();
    if ($resultado_contenido->num_rows === 0) {
        throw new Exception("Error: El contenido con ID $contenido_id no existe.");
    }

    // Verificar si el estudiante existe en la tabla estudiante
    $stmt_estudiante = $conn->prepare("SELECT id FROM estudiante WHERE id = ?");
    $stmt_estudiante->bind_param("i", $estudiante_id);
    if (!$stmt_estudiante->execute()) {
        throw new Exception("Error al verificar el estudiante: " . $stmt_estudiante->error);
    }
    $resultado_estudiante = $stmt_estudiante->get_result();
    if ($resultado_estudiante->num_rows === 0) {
        throw new Exception("Error: El estudiante con ID $estudiante_id no existe.");
    }

    // Insertar datos en la tabla contenido_estudiante
    $stmt = $conn->prepare("INSERT INTO contenido_estudiante (contenido_id, estudiante_id, nivel_desempeno) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $contenido_id, $estudiante_id, $nivel_desempeno);
    if (!$stmt->execute()) {
        throw new Exception("Error al guardar los datos: " . $stmt->error);
    }

    // Éxito
    $response['success'] = true;
    $response['message'] = 'Datos guardados exitosamente.';

    // Cerrar conexiones y sentencias preparadas
    $stmt_contenido->close();
    $stmt_estudiante->close();
    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    // Manejar errores
    $response['message'] = $e->getMessage();
}

// Devolver respuesta JSON
echo json_encode($response);
?>
