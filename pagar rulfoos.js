// Variable que mantiene el estado visible del carrito
let carritoVisible = false;

// Esperamos que todos los elementos de la página carguen para ejecutar el script
document.addEventListener('DOMContentLoaded', ready);

function ready() {
    agregarEventosBotones('btn-eliminar', eliminarItemCarrito);
    agregarEventosBotones('sumar-cantidad', sumarCantidad);
    agregarEventosBotones('restar-cantidad', restarCantidad);
    
    // Asignar evento solo una vez para todos los botones de agregar al carrito
    const botonesAgregar = document.getElementsByClassName('boton-item'); 
    for (let button of botonesAgregar) {
        button.addEventListener('click', agregarAlCarritoClicked);
    }

    document.querySelector('.btn-pagar').addEventListener('click', pagarClicked);
}

function agregarEventosBotones(clase, funcion) {
    const botones = document.getElementsByClassName(clase);
    for (let button of botones) {
        button.addEventListener('click', funcion);
    }
}

// Eliminamos todos los elementos del carrito y lo ocultamos
function pagarClicked() {
    alert("Gracias por la compra");
    const carritoItems = document.querySelector('.carrito-items');
    while (carritoItems.firstChild) {
        carritoItems.removeChild(carritoItems.firstChild);
    }
    actualizarTotalCarrito();
    ocultarCarrito();
}

// Función que controla el botón clickeado de agregar al carrito
function agregarAlCarritoClicked(event) {
    const button = event.currentTarget; 
    const item = button.closest('.item');
    const titulo = item.querySelector('.titulo-item').innerText;
    const precio = item.querySelector('.precio-item').innerText;
    const imagenSrc = item.querySelector('.img-item').src;

    agregarItemAlCarrito(titulo, precio, imagenSrc);
    hacerVisibleCarrito();
}

// Función que hace visible el carrito
function hacerVisibleCarrito() {
    carritoVisible = true;
    const carrito = document.querySelector('.carrito');
    carrito.style.marginRight = '0';
    carrito.style.opacity = '1';

    const items = document.querySelector('.contenedor-items');
    items.style.width = '60%';
}

// Función que agrega un item al carrito
function agregarItemAlCarrito(titulo, precio, imagenSrc) {
    const itemsCarrito = document.querySelector('.carrito-items');

    const itemExistente = Array.from(itemsCarrito.getElementsByClassName('carrito-item-titulo')).find(item => item.innerText === titulo);

    if (itemExistente) {
        const cantidadInput = itemExistente.closest('.carrito-item').querySelector('.carrito-item-cantidad');
        cantidadInput.value = parseInt(cantidadInput.value) + 1;
        alert("Se ha aumentado la cantidad del item en el carrito.");
    } else {
        const itemCarritoContenido = `
            <div class="carrito-item">
                <img src="${imagenSrc}" width="80px" alt="">
                <div class="carrito-item-detalles">
                    <span class="carrito-item-titulo">${titulo}</span>
                    <div class="selector-cantidad">
                        <i class="fa-solid fa-minus restar-cantidad"></i>
                        <input type="text" value="1" class="carrito-item-cantidad" disabled>
                        <i class="fa-solid fa-plus sumar-cantidad"></i>
                    </div>
                    <span class="carrito-item-precio">${precio}</span>
                </div>
                <button class="btn-eliminar">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;

        const item = document.createElement('div');
        item.innerHTML = itemCarritoContenido;
        itemsCarrito.appendChild(item);

        agregarEventosItem(item);
        hacerVisibleCarrito();
    }

    actualizarTotalCarrito();
}

// Función para agregar eventos a un item del carrito
function agregarEventosItem(item) {
    item.querySelector('.btn-eliminar').addEventListener('click', eliminarItemCarrito);
    item.querySelector('.restar-cantidad').addEventListener('click', restarCantidad);
    item.querySelector('.sumar-cantidad').addEventListener('click', sumarCantidad);
}

// Aumento en uno la cantidad del elemento seleccionado
function sumarCantidad(event) {
    const cantidadInput = event.target.parentElement.querySelector('.carrito-item-cantidad');
    cantidadInput.value = parseInt(cantidadInput.value) + 1;
    actualizarTotalCarrito();
}

// Resto en uno la cantidad del elemento seleccionado
function restarCantidad(event) {
    const cantidadInput = event.target.parentElement.querySelector('.carrito-item-cantidad');
    if (cantidadInput.value > 1) {
        cantidadInput.value = parseInt(cantidadInput.value) - 1;
        actualizarTotalCarrito();
    }
}

// Elimino el item seleccionado del carrito
function eliminarItemCarrito(event) {
    const item = event.target.closest('.carrito-item'); 
    if (item) {
        item.remove();
        actualizarTotalCarrito();
        ocultarCarrito();
    }
}

// Función que controla si hay elementos en el carrito. Si no hay, oculto el carrito.
function ocultarCarrito() {
    const carritoItems = document.querySelector('.carrito-items');
    if (!carritoItems.childElementCount) {
        const carrito = document.querySelector('.carrito');
        carrito.style.marginRight = '-100%';
        carrito.style.opacity = '0';
        carritoVisible = false;

        const items = document.querySelector('.contenedor-items');
        items.style.width = '100%';
    }
}

// Actualizamos el total de Carrito
function actualizarTotalCarrito() {
    const carritoItems = document.querySelectorAll('.carrito-item');
    let total = 0;

    carritoItems.forEach(item => {
        const precio = parseFloat(item.querySelector('.carrito-item-precio').innerText.replace('$', '').replace(',', '').replace('.', ''));
        const cantidad = parseInt(item.querySelector('.carrito-item-cantidad').value);
        total += precio * cantidad;
    });

    const totalCarrito = document.querySelector('.carrito-precio-total'); // Asegúrate de que esto sea correcto
    totalCarrito.innerText = `$${total.toLocaleString('es-CO', { minimumFractionDigits: 1, maximumFractionDigits: 1 })}`;

}
