// Página de productos: pago con PayPal.
// Usa calcularTotalCarrito(), itemsParaServidor() y vaciarCarritoPago() de carrito.js.
// La ventana del QR está en tienda.js.
//
// El pago se hace en el servidor (paypal_crear_orden.php y paypal_capturar.php):
// allí se revisa el stock, se calcula el total con los precios de la base de datos
// y se descuentan las piezas vendidas. El navegador solo dice QUÉ se compra.

let paypalRenderizado = false;

// Manda datos al servidor y regresa la respuesta; si el servidor responde con error, lanza ese mensaje.
function enviarAlServidor(url, datos) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(datos)
    }).then(function (respuesta) {
        return respuesta.json().catch(function () { return {}; }).then(function (json) {
            if (!respuesta.ok) {
                throw new Error(json.error || 'Ocurrió un error, intenta de nuevo.');
            }
            return json;
        });
    });
}

document.querySelector('.btn-pagar').addEventListener('click', function () {
    if (calcularTotalCarrito() <= 0) {
        alert("Tu carrito está vacío.");
        return;
    }
    if (typeof paypal === 'undefined') {
        alert("El pago con PayPal todavía no está configurado (falta config.php con las llaves de PayPal).");
        return;
    }

    document.getElementById('paypal-button-container').style.display = 'block';

    if (!paypalRenderizado) {
        let ultimoError = null;

        paypal.Buttons({
            style: { shape: "rect", color: "gold", layout: "vertical", label: "paypal" },

            // 1) El servidor revisa stock y precios y crea la orden por el total correcto.
            createOrder: function () {
                ultimoError = null;
                return enviarAlServidor('paypal_crear_orden.php', { items: itemsParaServidor() })
                    .then(function (datos) { return datos.id; })
                    .catch(function (err) { ultimoError = err.message; throw err; });
            },

            // 2) El cliente aprobó en PayPal: el servidor cobra y descuenta el inventario.
            onApprove: function (data) {
                return enviarAlServidor('paypal_capturar.php', { orderID: data.orderID })
                    .then(function () {
                        alert("¡Gracias por tu compra! Tu pago se registró correctamente.");
                        vaciarCarritoPago();
                        location.reload(); // para ver el stock actualizado
                    })
                    .catch(function (err) { alert(err.message); });
            },

            onError: function (err) {
                console.error("Error en el pago:", err);
                alert(ultimoError || "Ocurrió un error al procesar el pago.");
            }
        }).render('#paypal-button-container');
        paypalRenderizado = true;
    }
});
