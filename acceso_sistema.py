import time
import os
import mysql.connector
from mysql.connector import Error
import bcrypt
import json
import base64
import hmac
import hashlib
import urllib.parse
from cryptography.hazmat.primitives.ciphers import Cipher, algorithms, modes
from cryptography.hazmat.backends import default_backend
from cryptography.hazmat.primitives import padding
import getpass

SOGAC_ACCESS_KEY_BASE64 = "RXN0ZUVzVW5TZWNyZXRvRGUzMkJ5dGVzRXhhY3Rvc3M="
URL_BASE_SISTEMA = "http://localhost:8000"

CONFIG_SOGC = {
    "host": "127.0.0.1",
    "database": "emulacion_sogac_2",
    "user": "root",
    "password": ""
}

def encrypt_for_laravel(data_dict, app_key_b64):
    key = base64.b64decode(app_key_b64)
    iv = os.urandom(16)
    value = json.dumps(data_dict).encode('utf-8')
    padder = padding.PKCS7(128).padder()
    padded_data = padder.update(value) + padder.finalize()
    cipher = Cipher(algorithms.AES(key), modes.CBC(iv), backend=default_backend())
    encryptor = cipher.encryptor()
    encrypted_value = encryptor.update(padded_data) + encryptor.finalize()
    iv_b64 = base64.b64encode(iv).decode('utf-8')
    value_b64 = base64.b64encode(encrypted_value).decode('utf-8')
    mac_payload = iv_b64 + value_b64
    mac = hmac.new(key, mac_payload.encode('utf-8'), hashlib.sha256).hexdigest()
    payload = json.dumps({'iv': iv_b64, 'value': value_b64, 'mac': mac, 'tag': ''})
    return base64.b64encode(payload.encode('utf-8')).decode('utf-8')

def conectar_y_validar_local(usuario, password_input):
    try:
        conn = mysql.connector.connect(
            host=CONFIG_SOGC['host'],
            user=CONFIG_SOGC['user'],
            password=CONFIG_SOGC['password'],
            database=CONFIG_SOGC['database']
        )
        if conn.is_connected():
            cursor = conn.cursor(dictionary=True)
            cursor.execute("SELECT usu_nombre, usu_clave, usu_cedula, usu_estatus FROM usuario WHERE usu_nombre = %s", (usuario,))
            user_record = cursor.fetchone()
            if user_record:
                hash_bd = user_record['usu_clave']
                if hash_bd.startswith('$2y$'):
                    hash_bd = hash_bd.replace('$2y$', '$2b$')
                if bcrypt.checkpw(password_input.encode('utf-8'), hash_bd.encode('utf-8')):
                    if user_record['usu_estatus'] == '3':
                        return None
                    return user_record
            return None
    except Error:
        return None
    finally:
        if 'conn' in locals() and conn.is_connected():
            conn.close()

def login():
    usuario_input = input("Usuario: ")
    password_input = getpass.getpass("Contraseña: ").upper()
    usuario_data = conectar_y_validar_local(usuario_input, password_input)
    if usuario_data:
        timestamp_actual = int(time.time())
        seed_validacion = f"{usuario_data['usu_cedula']}{timestamp_actual}{SOGAC_ACCESS_KEY_BASE64}"
        firma_seguridad = hashlib.sha256(seed_validacion.encode()).hexdigest()
        payload_data = {
            'cedula': usuario_data['usu_cedula'].strip(),
            'fecha_creacion': timestamp_actual,
            'firma_validacion': firma_seguridad
        }
        ticket_encriptado = encrypt_for_laravel(payload_data, SOGAC_ACCESS_KEY_BASE64)
        url_final = f"{URL_BASE_SISTEMA}/login?payload={urllib.parse.quote(ticket_encriptado)}"
        print(f"\nLink de acceso: {url_final}")
    else:
        print("\nAcceso denegado.")

if __name__ == "__main__":
    try:
        login()
    except KeyboardInterrupt:
        pass
