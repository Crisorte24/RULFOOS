from flask import Flask, jsonify, request
from flask_cors import CORS
import mysql.connector
import smtplib
from email.mime.text import MIMEText
from config import EMAIL_SENDER, EMAIL_PASSWORD

BD = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="gool"
)

app = Flask(__name__)

CORS(app)

# Configuración del correo
SMTP_SERVER = "smtp.gmail.com"
SMTP_PORT = 587
# EMAIL_SENDER y EMAIL_PASSWORD se leen de config.py (no se sube a Git)

def enviar_correo(resultado, correo):
    noprods= len(resultado)
    body=""
    for i in range (0,noprods):
        msg1= f"Producto: {resultado[i][0]}\n"
        msg2= f"Precio: {resultado[i][1]}\n"
        msg3= f"Cantidad: {resultado[i][2]}\n"
        msg4= "\n"
        body = body + msg1 + msg2 + msg3 + msg4

    mensaje = MIMEText(str(body))

    mensaje["Subject"] = "Inventario Actual"
    mensaje["From"] = EMAIL_SENDER
    mensaje["To"] = correo
    try:
        with smtplib.SMTP(SMTP_SERVER, SMTP_PORT) as server:
            server.starttls()
            server.login(EMAIL_SENDER, EMAIL_PASSWORD)
            server.sendmail(EMAIL_SENDER, correo, mensaje.as_string())
        print("Correo enviado exitosamente.")
    except Exception as e:
        print(f"Error al enviar el correo: {e}")

@app.route('/obt_inv', methods=['POST'])
def obt_inv():
    correo = request.json['correo']
    MiCursor = BD.cursor()
    MiCursor.execute("SELECT nombre, precio1, cantidad FROM PRODUCTOS")
    resultado = MiCursor.fetchall()
    
    # Enviar correo con los resultados
    enviar_correo(resultado, correo)
    
    #return jsonify(resultado)
    return jsonify({"status": "ok"})

if __name__ == '__main__':
    app.run(debug=True)
