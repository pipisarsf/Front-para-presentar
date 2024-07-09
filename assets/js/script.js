document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM content loaded');

    const form = document.querySelector('.login-form');

    if (form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            console.log(`Username: ${username}, Password: ${password}`);

            // Aquí debes implementar una llamada al servidor para validar el usuario
            // Ejemplo usando fetch
            fetch('/validar.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ username, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'rubricas.html';
                } else {
                    console.warn('Usuario o contraseña incorrectos');
                    alert('Usuario o contraseña incorrectos');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    } else {
        console.error('Formulario no encontrado');
    }
});
