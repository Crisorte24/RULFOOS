// Menú del administrador (menu_add.php)
// Pide a la API de Python (apiRulfos.py) que mande el inventario por correo.
// El correo de destino se configura en config.py (EMAIL_RECEIVER), no aquí.
const btnApi = document.getElementById("botonApi");

const textoOriginal = btnApi.innerHTML;

btnApi.addEventListener('click', function () {
    btnApi.disabled = true;
    btnApi.textContent = "Enviando...";

    fetch("http://127.0.0.1:5000/obt_inv", { method: 'POST' })
        .then(respuesta => respuesta.json().then(data => ({ ok: respuesta.ok, data })))
        .then(({ ok, data }) => {
            if (ok) {
                alert("Reporte de inventario enviado por correo.");
            } else {
                alert("No se pudo enviar el reporte: " + (data.mensaje || "error desconocido"));
            }
        })
        .catch(() => alert("No se pudo conectar con la API. ¿Está corriendo apiRulfos.py?"))
        .finally(() => {
            btnApi.disabled = false;
            btnApi.innerHTML = textoOriginal;
        });
});
