// Funciones comunes de la tienda (todas las páginas con encabezado.php / pie.php):
// abrir/cerrar el carrito del menú, el buscador, el menú del celular y la ventana del QR.

// ---------- Carrito del menú ----------
const carritoMenu = document.getElementById('carrito-menu');
const botonCarrito = document.getElementById('img-carrito');

function abrirCarritoMenu(abrir) {
    carritoMenu.classList.toggle('abierto', abrir);
    botonCarrito.setAttribute('aria-expanded', abrir ? 'true' : 'false');
}

botonCarrito.addEventListener('click', function () {
    abrirCarritoMenu(!carritoMenu.classList.contains('abierto'));
});

// Cerrar al dar clic fuera del carrito.
// Se usa composedPath() porque al quitar un producto la fila se redibuja y el elemento
// clickeado ya no está en la página, pero el clic sí ocurrió dentro del carrito.
document.addEventListener('click', function (e) {
    if (carritoMenu.classList.contains('abierto') && !e.composedPath().includes(carritoMenu)) {
        abrirCarritoMenu(false);
    }
});

// ---------- Buscador ----------
const botonBuscar = document.getElementById('suggestion-box');
const panelBuscar = document.getElementById('suggestion-panel');
const campoBuscar = document.getElementById('product-search');
const listaSugerencias = document.getElementById('suggestions-list');

botonBuscar.addEventListener('click', function () {
    const abrir = !panelBuscar.classList.contains('abierto');
    panelBuscar.classList.toggle('abierto', abrir);
    botonBuscar.setAttribute('aria-expanded', abrir ? 'true' : 'false');
    if (abrir) campoBuscar.focus();
});

let temporizadorBusqueda;
campoBuscar.addEventListener('input', function () {
    const texto = this.value.trim();
    clearTimeout(temporizadorBusqueda);

    if (texto.length === 0) {
        listaSugerencias.innerHTML = '';
        return;
    }

    // Espera a que el usuario deje de escribir para no hacer una consulta por cada letra.
    temporizadorBusqueda = setTimeout(function () {
        fetch('buscar_productos.php?q=' + encodeURIComponent(texto))
            .then(respuesta => respuesta.json())
            .then(productos => {
                listaSugerencias.innerHTML = '';
                if (productos.length === 0) {
                    const li = document.createElement('li');
                    li.className = 'sin-resultados';
                    li.textContent = 'No encontramos productos con "' + texto + '"';
                    listaSugerencias.appendChild(li);
                    return;
                }
                productos.forEach(producto => {
                    const li = document.createElement('li');
                    const enlace = document.createElement('a');
                    enlace.href = 'productos rulfoos php.php#producto-' + producto.id;
                    enlace.textContent = producto.nombre;
                    li.appendChild(enlace);
                    listaSugerencias.appendChild(li);
                });
            })
            .catch(error => console.error('Error al buscar:', error));
    }, 250);
});

// ---------- Menú del celular: se cierra al elegir una opción ----------
document.querySelectorAll('.navegacion a').forEach(function (enlace) {
    enlace.addEventListener('click', function () {
        document.getElementById('menu-toggle').checked = false;
    });
});

// ---------- Ventana del QR ----------
const modalQR = document.getElementById('qrModal');

function mostrarQR(imagen) {
    document.getElementById('qrImage').src = imagen;
    modalQR.classList.add('abierto');
}

function cerrarQR() {
    modalQR.classList.remove('abierto');
}

modalQR.addEventListener('click', function (e) {
    if (e.target === modalQR) cerrarQR();
});

// ---------- Tecla Escape cierra lo que esté abierto ----------
document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    cerrarQR();
    abrirCarritoMenu(false);
    panelBuscar.classList.remove('abierto');
    botonBuscar.setAttribute('aria-expanded', 'false');
});
