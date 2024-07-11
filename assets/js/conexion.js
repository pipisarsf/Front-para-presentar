let mysql = require("mysql");

let conexion = mysql.createConnection({
    host: "localhost",
    database: "practicaprofesional",
    user: "root",
    password: ""
});

conexion.connect(function(err) {
    if (err) {
        console.error("Error de conexión:", err);
    } else {
        console.log("Conexión exitosa");
        // Realizar operaciones aquí

        // Cerrar la conexión después de las operaciones
        conexion.end(function(err) {
            if (err) {
                console.error("Error al cerrar la conexión:", err);
            } else {
                console.log("Conexión cerrada");
            }
        });
    }
});
