<?php

/**
 * Script de Acceso Independiente (PHP Puro)
 * Este script emula el comportamiento de SOGAC para generar enlaces de acceso.
 */

// --- CONFIGURACIÓN ---
$SOGAC_ACCESS_KEY_BASE64 = "RXN0ZUVzVW5TZWNyZXRvRGUzMkJ5dGVzRXhhY3Rvc3M=";
$URL_BASE_SISTEMA = "http://localhost:8000";

$CONFIG_SOGC = [
    "host" => "127.0.0.1",
    "database" => "emulacion_sogac_2",
    "user" => "root",
    "password" => ""
];

// --- FUNCIONES DE SEGURIDAD (Adaptadas de Laravel/Mcrypt) ---

function encrypt_for_laravel($data, $key_b64)
{
    $key = base64_decode($key_b64);
    $iv = openssl_random_pseudo_bytes(16);

    // Serializar a JSON (como lo hace Laravel)
    $value = json_encode($data);

    // Encriptar AES-256-CBC
    $encryptedValue = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    $iv_b64 = base64_encode($iv);
    $value_b64 = base64_encode($encryptedValue);

    // Generar MAC (Firma de integridad que Laravel exige)
    $mac_payload = $iv_b64 . $value_b64;
    $mac = hash_hmac('sha256', $mac_payload, $key);

    // Formato final que espera Crypt::decryptString
    $json_payload = json_encode([
        'iv' => $iv_b64,
        'value' => $value_b64,
        'mac' => $mac,
        'tag' => ''
    ]);

    return base64_encode($json_payload);
}

// --- LÓGICA DE VALIDACIÓN ---

function conectar_y_validar($usuario, $password_input)
{
    global $CONFIG_SOGC;

    try {
        $dsn = "mysql:host={$CONFIG_SOGC['host']};dbname={$CONFIG_SOGC['database']};charset=utf8mb4";
        $pdo = new PDO($dsn, $CONFIG_SOGC['user'], $CONFIG_SOGC['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);

        $stmt = $pdo->prepare("SELECT usu_nombre, usu_clave, usu_cedula, usu_estatus FROM usuario WHERE usu_nombre = ?");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch();

        if ($user) {
            // SOGAC usa bcrypt. En PHP password_verify es compatible con el hash de Laravel/Bcrypt
            if (password_verify(strtoupper($password_input), $user['usu_clave'])) {
                if ($user['usu_estatus'] == '3')
                    return null;
                return $user;
            }
        }
    } catch (PDOException $e) {
        return null;
    }
    return null;
}

// --- EJECUCIÓN POR CONSOLA ---

if (php_sapi_name() === 'cli') {
    echo "Usuario: ";
    $usuario_input = trim(fgets(STDIN));

    echo "Contrasena: ";
    // Ocultar password en consola (funciona en Linux/Mac, en Windows es texto plano por limitaciones de PHP puro)
    $password_input = trim(fgets(STDIN));

    $usuario_data = conectar_y_validar($usuario_input, $password_input);

    if ($usuario_data) {
        $timestamp = time();
        $seed = $usuario_data['usu_cedula'] . $timestamp . $SOGAC_ACCESS_KEY_BASE64;
        $firma = hash('sha256', $seed);

        $payload_data = [
            'cedula' => trim($usuario_data['usu_cedula']),
            'fecha_creacion' => $timestamp,
            'firma_validacion' => $firma
        ];

        $ticket = encrypt_for_laravel($payload_data, $SOGAC_ACCESS_KEY_BASE64);
        $url = "{$URL_BASE_SISTEMA}/login?payload=" . urlencode($ticket);

        echo "\nLink de acceso generado:\n";
        echo $url . "\n";
    } else {
        echo "\nAcceso denegado.\n";
    }
}
