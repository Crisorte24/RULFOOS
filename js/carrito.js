// =====================================================================
//  CARRITO ÚNICO DE RULFOOS
//  Un solo carrito para toda la tienda. Se guarda en el navegador
//  (localStorage), así que lo que agregues se conserva al cambiar de
//  página o al recargar.
//
//  Se muestra en dos lugares:
//   1. El carrito pequeño del menú (ícono arriba a la derecha) -> todas las páginas.
//   2. El carrito de pago "Tu Carrito" -> solo en productos rulfoos php.php.
//
//  Se agregan cosas desde:
//   - Promociones en inicio:  <a class="agregar-carrito" data-id data-tipo data-titulo data-precio data-imagen>
//   - Productos en productos: <button class="boton-item"> dentro de <div class="item" data-...>
// =====================================================================

const CLAVE_CARRITO = 'rulfoos_carrito';

// ---------- Guardar / leer ----------

function leerCarrito() {
    try {
        const datos = JSON.parse(localStorage.getItem(CLAVE_CARRITO));
        return Array.isArray(datos) ? datos : [];
    } catch (e) {
        return []; // navegador sin almacenamiento (modo privado, etc.)
    }
}

function guardarCarrito(items) {
    try {
        localStorage.setItem(CLAVE_CARRITO, JSON.stringify(items));
    } catch (e) {
        // Si no se puede guardar, el carrito funciona solo en esta página.
    }
    carritoEnMemoria = items;
    dibujarCarritos();
}

let carritoEnMemoria = leerCarrito();

// ---------- Operaciones ----------

// item = { clave, titulo, precio (número), imagen }
function agregarAlCarrito(item) {
    const items = carritoEnMemoria.slice();
    const existente = items.find(i => i.clave === item.clave);
    if (existente) {
        existente.cantidad += 1;
    } else {
        items.push({ ...item, cantidad: 1 });
    }
    guardarCarrito(items);
    avisar('Agregado al carrito: ' + item.titulo);
}

function cambiarCantidad(clave, cambio) {
    const items = carritoEnMemoria.slice();
    const item = items.find(i => i.clave === clave);
    if (!item) return;
    item.cantidad = Math.max(1, item.cantidad + cambio);
    guardarCarrito(items);
}

function quitarDelCarrito(clave) {
    guardarCarrito(carritoEnMemoria.filter(i => i.clave !== clave));
}

function vaciarCarrito() {
    guardarCarrito([]);
}

// Total en número (lo usa PayPal en productos rulfoos php.php)
function calcularTotalCarrito() {
    return carritoEnMemoria.reduce((total, i) => total + i.precio * i.cantidad, 0);
}

// Nombre que usa la página de productos después de pagar
function vaciarCarritoPago() {
    vaciarCarrito();
}

// ---------- Utilidades ----------

function formatoPrecio(numero) {
    return '$' + numero.toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Mensajito temporal abajo de la pantalla
function avisar(texto) {
    let aviso = document.getElementById('aviso-carrito');
    if (!aviso) {
        aviso = document.createElement('div');
        aviso.id = 'aviso-carrito';
        aviso.style.cssText = 'position:fixed;left:50%;bottom:30px;transform:translateX(-50%);' +
            'background:#000;color:#fff;padding:12px 20px;border-radius:8px;z-index:1000;' +
            'font-family:sans-serif;box-shadow:0 4px 12px rgba(0,0,0,.3);transition:opacity .3s;max-width:90%;text-align:center';
        document.body.appendChild(aviso);
    }
    aviso.textContent = texto;
    aviso.style.opacity = '1';
    clearTimeout(avisar.temporizador);
    avisar.temporizador = setTimeout(() => { aviso.style.opacity = '0'; }, 1800);
}

// ---------- Dibujar ----------

function dibujarCarritos() {
    dibujarCarritoMenu();
    dibujarCarritoPago();
}

// 1) Carrito pequeño del menú
function dibujarCarritoMenu() {
    const tbody = document.querySelector('#lista-carrito tbody');
    if (!tbody) return;

    tbody.innerHTML = '';
    carritoEnMemoria.forEach(item => {
        const fila = document.createElement('tr');

        const tdImagen = document.createElement('td');
        const img = document.createElement('img');
        img.src = item.imagen;
        img.width = 100;
        img.alt = item.titulo;
        tdImagen.appendChild(img);

        const tdTitulo = document.createElement('td');
        tdTitulo.textContent = item.titulo + (item.cantidad > 1 ? ' x' + item.cantidad : '');

        const tdPrecio = document.createElement('td');
        tdPrecio.textContent = formatoPrecio(item.precio * item.cantidad);

        const tdBorrar = document.createElement('td');
        const borrar = document.createElement('a');
        borrar.href = '#';
        borrar.className = 'borrar';
        borrar.dataset.clave = item.clave;
        borrar.textContent = 'X';
        tdBorrar.appendChild(borrar);

        fila.append(tdImagen, tdTitulo, tdPrecio, tdBorrar);
        tbody.appendChild(fila);
    });

    if (carritoEnMemoria.length === 0) {
        const fila = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 4;
        td.textContent = 'Tu carrito está vacío';
        td.style.padding = '10px';
        fila.appendChild(td);
        tbody.appendChild(fila);
    }

    const total = document.getElementById('total-mini');
    if (total) total.textContent = formatoPrecio(calcularTotalCarrito());
}

// 2) Carrito de pago (solo existe en la página de productos)
function dibujarCarritoPago() {
    const contenedor = document.querySelector('.carrito-items');
    if (!contenedor) return;

    contenedor.innerHTML = '';
    carritoEnMemoria.forEach(item => {
        const div = document.createElement('div');
        div.className = 'carrito-item';
        div.dataset.clave = item.clave;
        div.innerHTML = `
            <img width="80px" alt="">
            <div class="carrito-item-detalles">
                <span class="carrito-item-titulo"></span>
                <div class="selector-cantidad">
                    <i class="fa-solid fa-minus restar-cantidad"></i>
                    <input type="text" class="carrito-item-cantidad" disabled>
                    <i class="fa-solid fa-plus sumar-cantidad"></i>
                </div>
                <span class="carrito-item-precio"></span>
            </div>
            <button class="btn-eliminar" title="Quitar">
                <i class="fa-solid fa-trash"></i>
            </button>
        `;
        // Los textos se ponen con textContent para que no se interpreten como HTML.
        div.querySelector('img').src = item.imagen;
        div.querySelector('img').alt = item.titulo;
        div.querySelector('.carrito-item-titulo').textContent = item.titulo;
        div.querySelector('.carrito-item-cantidad').value = item.cantidad;
        div.querySelector('.carrito-item-precio').textContent = formatoPrecio(item.precio);
        contenedor.appendChild(div);
    });

    const total = document.querySelector('.carrito-precio-total');
    if (total) total.textContent = formatoPrecio(calcularTotalCarrito());

    // Mostrar u ocultar el panel según tenga o no cosas
    const carrito = document.querySelector('.carrito');
    const lista = document.querySelector('.contenedor-items');
    const hayItems = carritoEnMemoria.length > 0;
    if (carrito) {
        carrito.style.marginRight = hayItems ? '0' : '-100%';
        carrito.style.opacity = hayItems ? '1' : '0';
    }
    if (lista) lista.style.width = hayItems ? '60%' : '100%';

    if (!hayItems) {
        const paypal = document.getElementById('paypal-button-container');
        if (paypal) paypal.style.display = 'none';
    }
}

// ---------- Eventos (uno solo por tipo, para toda la página) ----------

document.addEventListener('click', function (e) {
    // Agregar una promoción (inicio)
    const promo = e.target.closest('.agregar-carrito');
    if (promo) {
        e.preventDefault();
        agregarAlCarrito({
            clave: 'promo-' + promo.dataset.id,
            titulo: promo.dataset.titulo,
            precio: parseFloat(promo.dataset.precio) || 0,
            imagen: promo.dataset.imagen
        });
        return;
    }

    // Agregar un producto (productos)
    const botonProducto = e.target.closest('.boton-item');
    if (botonProducto) {
        const p = botonProducto.closest('.item');
        agregarAlCarrito({
            clave: 'producto-' + p.dataset.id,
            titulo: p.dataset.titulo,
            precio: parseFloat(p.dataset.precio) || 0,
            imagen: p.dataset.imagen
        });
        return;
    }

    // Quitar desde el carrito del menú
    const borrar = e.target.closest('#lista-carrito .borrar');
    if (borrar) {
        e.preventDefault();
        quitarDelCarrito(borrar.dataset.clave);
        return;
    }

    // Vaciar desde el carrito del menú
    if (e.target.closest('#vaciar-carrito')) {
        e.preventDefault();
        vaciarCarrito();
        return;
    }

    // Botones del carrito de pago
    const itemPago = e.target.closest('.carrito-items .carrito-item');
    if (itemPago) {
        const clave = itemPago.dataset.clave;
        if (e.target.closest('.sumar-cantidad'))  cambiarCantidad(clave, +1);
        if (e.target.closest('.restar-cantidad')) cambiarCantidad(clave, -1);
        if (e.target.closest('.btn-eliminar'))    quitarDelCarrito(clave);
    }
});

// Si el carrito cambia en otra pestaña, se actualiza aquí también.
window.addEventListener('storage', function (e) {
    if (e.key === CLAVE_CARRITO) {
        carritoEnMemoria = leerCarrito();
        dibujarCarritos();
    }
});

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', dibujarCarritos);
} else {
    dibujarCarritos();
}
