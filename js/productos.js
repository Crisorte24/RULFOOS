// Página de productos: ventana del QR y pago con PayPal.
// Usa calcularTotalCarrito() y vaciarCarritoPago() de carrito.js.

let paypalRenderizado = false;

function mostrarQR(imagenQR) {
    document.getElementById("qrImage").src = imagenQR;
    document.getElementById("qrModal").style.display = "block";
}

function cerrarQR() {
    document.getElementById("qrModal").style.display = "none";
}

window.addEventListener('click', function (event) {
    const modal = document.getElementById("qrModal");
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

document.querySelector('.btn-pagar').addEventListener('click', function () {
    if (calcularTotalCarrito() <= 0) {
        alert("Tu carrito está vacío.");
        return;
    }
    if (typeof paypal === 'undefined') {
        alert("El pago con PayPal todavía no está configurado (falta el Client ID en productos rulfoos php.php).");
        return;
    }

    document.getElementById('paypal-button-container').style.display = 'block';

    if (!paypalRenderizado) {
        paypal.Buttons({
            style: { shape: "rect", color: "gold", layout: "vertical", label: "paypal" },
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { currency_code: "MXN", value: calcularTotalCarrito().toFixed(2) }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    alert("¡Gracias por tu compra, " + details.payer.name.given_name + "!");
                    vaciarCarritoPago();
                    document.getElementById('paypal-button-container').style.display = 'none';
                });
            },
            onError: function (err) {
                console.error("Error en el pago:", err);
                alert("Ocurrió un error al procesar el pago.");
            }
        }).render('#paypal-button-container');
        paypalRenderizado = true;
    }
});
