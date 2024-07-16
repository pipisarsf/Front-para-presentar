<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ver Registros</title>
    <style>
    /* Estilos globales */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(27deg, rgb(52, 73, 94) 50%, rgb(44, 62, 80) 50%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            margin-top: 20px;
            overflow-x: auto; /* Permite el desplazamiento horizontal si es necesario */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid var(--color-lineas); /* Usar variable para línea de separación */
        }

        th {
            background-color: var(--color-tarjetas); /* Usar variable para color de fondo */
            color: #ffffff;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Color de fondo al pasar el ratón */
        }
        </style>
</head>
<body>

<div class="container">
    <h2>Registros de Contenido-Estudiante</h2>

    <?php
    // Datos de conexión a la base de datos
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "practicaprofesional";

    try {
        // Conectar a la base de datos
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar la conexión
        if ($conn->connect_error) {
            throw new Exception("Conexión fallida: " . $conn->connect_error);
        }

        // Consulta SQL para obtener todos los registros de contenido_estudiante
        $sql = "SELECT * FROM contenido_estudiante";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Contenido ID</th>
                        <th>Estudiante ID</th>
                        <th>Nivel de Desempeño</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['contenido_id'] . "</td>
                        <td>" . $row['estudiante_id'] . "</td>
                        <td>" . $row['nivel_desempeno'] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron registros.";
        }

        // Cerrar conexión
        $conn->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>

<form action="contenidos.html">
        <button class="volver">Volver</button>
    </form>

</div>

</body>
</html>
