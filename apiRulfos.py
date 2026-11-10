from flask import Flask, jsonify
from flask_cors import CORS
import mysql.connector
import smtplib
from email.mime.text import MIMEText
from config import EMAIL_SENDER, EMAIL_PASSWORD, EMAIL_RECEIVER

# Configuración del correo
SMTP_SERVER = "smtp.gmail.com"
SMTP_PORT = 587
# EMAIL_SENDER, EMAIL_PASSWORD y EMAIL_RECEIVER se leen de config.py (no se sube a Git)

app = Flask(__name__)

# Solo la página servida por XAMPP en esta computadora puede llamar a la API.
CORS(app, origins=[r"^http://(localhost|127\.0\.0\.1)(:\d+)?$"])


def conectar_bd():
    # Una conexión nueva por petición: así no falla si MySQL cerró una conexión vieja.
    return mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="gool"
    )


def enviar_correo(resultado, correo):
    body = ""
    for nombre, precio, cantidad in resultado:
        body += f"Producto: {nombre}\nPrecio: {precio}\nCantidad: {cantidad}\n\n"

    mensaje = MIMEText(body)
    mensaje["Subject"] = "Inventario Actual"
    mensaje["From"] = EMAIL_SENDER
    mensaje["To"] = correo

    with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as server:
        server.starttls()
        server.login(EMAIL_SENDER, EMAIL_PASSWORD)
        server.sendmail(EMAIL_SENDER, correo, mensaje.as_string())


@app.route('/obt_inv', methods=['POST'])
def obt_inv():
    # El destinatario es fijo (config.py): así nadie puede usar la API
    # para mandar correos a cualquier dirección.
    try:
        bd = conectar_bd()
        try:
            cursor = bd.cursor()
            cursor.execute("SELECT nombre, precio2, cantidad FROM productos")  # precio2 = precio de venta
            resultado = cursor.fetchall()
        finally:
            bd.close()

        enviar_correo(resultado, EMAIL_RECEIVER)
        print("Correo enviado exitosamente.")
        return jsonify({"status": "ok"})
    except Exception as e:
        print(f"Error al enviar el reporte: {e}")
        return jsonify({"status": "error", "mensaje": str(e)}), 500


if __name__ == '__main__':
    # debug=False: el modo debug permite ejecutar código desde el navegador si hay un error.
    app.run(host="127.0.0.1", port=5000, debug=False)
