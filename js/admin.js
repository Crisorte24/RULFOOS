// Panel de productos (admin.php): llenar el formulario para editar
const form = document.getElementById('form-producto');
const campoImagen = form.querySelector('[name="imagen"]');

// Llena el formulario con los datos del producto para editarlo.
document.querySelectorAll('.btn-editar').forEach(function (boton) {
    boton.addEventListener('click', function () {
        const d = boton.dataset;
        form.querySelector('[name="id"]').value = d.id;
        form.querySelector('[name="nombre"]').value = d.nombre;
        form.querySelector('[name="precio1"]').value = d.precio1;
        form.querySelector('[name="precio2"]').value = d.precio2;
        form.querySelector('[name="cantidad"]').value = d.cantidad;
        form.querySelector('[name="url_3d"]').value = d.url3d;
        campoImagen.required = false; // al editar, la imagen es opcional
        document.getElementById('nota-imagen').textContent = '(déjalo vacío para conservar la actual)';
        document.getElementById('titulo-form').textContent = 'Editando producto #' + d.id;
        document.getElementById('cancelar-edicion').hidden = false;
        form.scrollIntoView({ behavior: 'smooth' });
    });
});

document.getElementById('cancelar-edicion').addEventListener('click', function () {
    form.reset();
    form.querySelector('[name="id"]').value = '';
    campoImagen.required = true;
    document.getElementById('nota-imagen').textContent = '';
    document.getElementById('titulo-form').textContent = 'Nuevo producto';
    this.hidden = true;
});
