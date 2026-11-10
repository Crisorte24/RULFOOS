// Login: mostrar/ocultar la contraseña
function togglePassword() {
    const passwordInput = document.getElementById("contrasena");
    const passwordToggle = document.getElementById("toggle-password");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        passwordToggle.textContent = "Ocultar";
    } else {
        passwordInput.type = "password";
        passwordToggle.textContent = "Mostrar";
    }
}
