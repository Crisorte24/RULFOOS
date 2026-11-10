<?php
// Copia este archivo como config.php y pon tus llaves de PayPal.
// config.php NO se sube a Git (está en .gitignore).
//
// Las llaves se sacan en https://developer.paypal.com -> Apps & Credentials -> Create App.
// Empieza con las de "Sandbox" (pruebas, dinero falso). Cuando todo funcione,
// cambia a las de "Live" y pon PAYPAL_MODO en 'live'.
define('PAYPAL_CLIENT_ID', 'tu-client-id');
define('PAYPAL_SECRET', 'tu-secret');
define('PAYPAL_MODO', 'sandbox'); // 'sandbox' o 'live'
