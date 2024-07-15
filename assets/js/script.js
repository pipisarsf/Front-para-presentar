document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');

    // Asignar evento onclick a cada botón
    const botones = document.querySelectorAll('.boton');
    botones.forEach((boton, index) => {
        boton.addEventListener('click', function() {
            mostrarDescripcion(index + 1); // index + 1 porque los IDs empiezan en 1 según tu estructura
        });
    });

    const form = document.querySelector('.login-form');
    
    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            // Validar campos del formulario
            if (username.trim() === '' || password.trim() === '') {
                alert('Por favor, completa todos los campos.');
                return;
            }

            // Realizar la solicitud al servidor
            fetch('/validar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud.');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = 'rubricas.html'; // Redirigir si la validación es exitosa
                } else {
                    console.warn('Usuario o contraseña incorrectos');
                    alert('Usuario o contraseña incorrectos');
                }
            })
            .catch(error => {
                console.error('Error:', error.message);
                alert('Hubo un error al intentar iniciar sesión. Por favor, inténtalo de nuevo más tarde.');
            });
        });
    } else {
        console.error('Formulario no encontrado');
    }
});
