document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form[action="index.php?action=login"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = loginForm.querySelector('input[name="email"]').value.trim();
            const password = loginForm.querySelector('input[name="password"]').value.trim();
            
            if (!email || !password) {
                e.preventDefault();
                alert('Por favor, complete todos los campos de inicio de sesión.');
            }
        });
    }

    const registerForm = document.querySelector('form[action="index.php?action=register"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const name = registerForm.querySelector('input[name="name"]').value.trim();
            const phoneInput = registerForm.querySelector('input[name="phone"]');
            const phone = phoneInput ? phoneInput.value.trim() : '';
            const email = registerForm.querySelector('input[name="email"]').value.trim();
            const password = registerForm.querySelector('input[name="password"]').value.trim();
            
            if (!name || !email || !password || !phone) {
                e.preventDefault();
                alert('Por favor, complete todos los campos de registro.');
                return;
            } 
            
            if (password.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres.');
                return;
            }

            const phoneRegex = /^[0-9]{7,15}$/;
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                alert('Por favor, ingrese un número de teléfono válido.');
                return;
            }

            if (!email.toLowerCase().endsWith('.com')) {
                e.preventDefault();
                alert('El correo electrónico debe terminar en .com');
                return;
            }
        });
    }
});
