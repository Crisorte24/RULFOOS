// Página de inicio: carruseles (Swiper).
// El buscador, el carrito y la ventana del QR están en tienda.js.

// Carrusel principal de productos
new Swiper('.mySwiper-1', {
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    autoplay: { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true },
    navigation: { nextEl: '.mySwiper-1 .swiper-button-next', prevEl: '.mySwiper-1 .swiper-button-prev' },
    pagination: { el: '.mySwiper-1 .swiper-pagination', clickable: true },
});

// Carrusel de promociones: 1 en celular, 2 en tableta, 3 en computadora
new Swiper('.mySwiper-2', {
    spaceBetween: 20,
    autoplay: { delay: 4000, disableOnInteraction: false, pauseOnMouseEnter: true },
    navigation: { nextEl: '.promos-carrusel .swiper-button-next', prevEl: '.promos-carrusel .swiper-button-prev' },
    breakpoints: {
        0:    { slidesPerView: 1.15 },
        600:  { slidesPerView: 2 },
        1000: { slidesPerView: 3 },
    },
});
