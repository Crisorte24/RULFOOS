// Página de inicio: carruseles, buscador y ventana del QR

// Carrusel de productos
new Swiper('.mySwiper-1', {
    slidesPerView: 1,
    spaceBetween: 30,
    loop: true,
    autoplay: { delay: 4000, disableOnInteraction: false },
    navigation: { nextEl: '.mySwiper-1 .swiper-button-next', prevEl: '.mySwiper-1 .swiper-button-prev' },
    pagination: { el: '.mySwiper-1 .swiper-pagination', clickable: true },
});

// Carrusel de promociones (1 en celular, 2 en pantallas grandes)
new Swiper('.mySwiper-2', {
    spaceBetween: 10,
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    navigation: { nextEl: '.mySwiper-2 .swiper-button-next', prevEl: '.mySwiper-2 .swiper-button-prev' },
    breakpoints: { 0: { slidesPerView: 1 }, 520: { slidesPerView: 2 } },
});

// Mostrar/Ocultar el panel del buscador
document.getElementById("suggestion-box").addEventListener("click", function (e) {
    e.preventDefault();
    const panel = document.getElementById("suggestion-panel");
    const abrir = panel.style.display === "none" || panel.style.display === "";
    panel.style.display = abrir ? "block" : "none";
    if (abrir) document.getElementById("product-search").focus();
});

// Buscar productos y mostrar sugerencias
let temporizadorBusqueda;
document.getElementById("product-search").addEventListener("input", function () {
    const query = this.value.trim();
    const suggestionsList = document.getElementById("suggestions-list");
    clearTimeout(temporizadorBusqueda);

    if (query.length === 0) {
        suggestionsList.innerHTML = "";
        return;
    }

    // Espera a que el usuario deje de escribir para no hacer una consulta por cada letra.
    temporizadorBusqueda = setTimeout(function () {
        fetch('buscar_productos.php?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                suggestionsList.innerHTML = "";
                if (data.length === 0) {
                    const li = document.createElement("li");
                    li.textContent = "Sin resultados";
                    suggestionsList.appendChild(li);
                    return;
                }
                data.forEach(producto => {
                    const li = document.createElement("li");
                    const link = document.createElement("a");
                    link.href = "productos rulfoos php.php#producto-" + producto.id;
                    link.textContent = producto.nombre;
                    li.appendChild(link);
                    suggestionsList.appendChild(li);
                });
            })
            .catch(error => console.error("Error al obtener las sugerencias:", error));
    }, 250);
});

// Modal del QR
function mostrarQR(src) {
    document.getElementById("qrImage").src = src;
    document.getElementById("qrModal").style.display = "block";
}

function cerrarQR() {
    document.getElementById("qrModal").style.display = "none";
}
