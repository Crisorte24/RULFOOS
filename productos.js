// Productos iniciales (puedes cargarlos desde un archivo JSON si lo prefieres)
const productos = [
    {
        categoria: 'audifonos-premium',
        nombre: 'Audífono Bluetooth Premium',
        precio: 1200,
        imagen: 'audifono-premium.jpg'
    },
    {
        categoria: 'nuevos-productos',
        nombre: 'Cargador Rápido USB-C',
        precio: 300,
        imagen: 'cargador-usb-c.jpg'
    },
    {
        categoria: 'promociones',
        nombre: 'Funda Protectora iPhone 12',
        precio: 150,
        imagen: 'funda-iphone.jpg'
    }
];

// Función para cargar productos dinámicamente en la página del cliente
function cargarProductos() {
    productos.forEach(producto => {
        const contenedor = document.getElementById(producto.categoria);
        const divProducto = document.createElement('div');
        divProducto.classList.add('producto');
        divProducto.innerHTML = `
            <img src="${producto.imagen}" alt="${producto.nombre}">
            <h3>${producto.nombre}</h3>
            <p>Precio: $${producto.precio}</p>
        `;
        contenedor.appendChild(divProducto);
    });
}

// Llama a la función cuando la página cargue
window.onload = cargarProductos;
