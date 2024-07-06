document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');

    // Encuentra el formulario por clase
    const form = document.querySelector('.login-form'); // Asegúrate de que el selector es correcto

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Previene el comportamiento predeterminado

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            console.log(`Username: ${username}, Password: ${password}`); 

            // Lógica para validar el usuario
            const usuarioValido = window.usuarios.find(user => user.usuario === username && user.contraseña === password);

            if (usuarioValido) {
                console.log('Usuario válido');
                window.location.href = 'rubricas.html'; // Redirección en caso de éxito
            } else {
                console.warn('Usuario o contraseña incorrectos');
                alert('Usuario o contraseña incorrectos'); // Alerta en caso de error
            }
        });
    } else {
        console.error('Formulario no encontrado'); 
    }
});
