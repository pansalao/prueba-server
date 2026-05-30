<?php
$SOGAC_ACCESS_KEY_BASE64 = "RXN0ZUVzVW5TZWNyZXRvRGUzMkJ5dGVzRXhhY3Rvc3M=";
$URL_BASE_SISTEMA = "http://localhost:8000";

function encrypt_for_laravel($data, $key_b64)
{
    $key = base64_decode($key_b64);
    $iv = openssl_random_pseudo_bytes(16);
    $value = json_encode($data);
    $encryptedValue = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
    $iv_b64 = base64_encode($iv);
    $value_b64 = base64_encode($encryptedValue);
    $mac_payload = $iv_b64 . $value_b64;
    $mac = hash_hmac('sha256', $mac_payload, $key);
    $json_payload = json_encode([
        'iv' => $iv_b64,
        'value' => $value_b64,
        'mac' => $mac,
        'tag' => ''
    ]);
    return base64_encode($json_payload);
}

$usuario = $argv[1] ?? '31009367VICE';

// Connect to DB and get user data
$pdo = new PDO('mysql:host=127.0.0.1;dbname=emulacion_sogac_2;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

$stmt = $pdo->prepare("SELECT usu_nombre, usu_cedula FROM usuario WHERE usu_nombre = ?");
$stmt->execute([$usuario]);
$user = $stmt->fetch();

if (!$user) {
    echo "User '$usuario' not found.\n";
    exit(1);
}

$timestamp = time();
$seed = $user['usu_cedula'] . $timestamp . $SOGAC_ACCESS_KEY_BASE64;
$firma = hash('sha256', $seed);

$payload_data = [
    'cedula' => trim($user['usu_cedula']),
    'fecha_creacion' => $timestamp,
    'firma_validacion' => $firma
];

$ticket = encrypt_for_laravel($payload_data, $SOGAC_ACCESS_KEY_BASE64);
$url = $URL_BASE_SISTEMA . "/login?payload=" . urlencode($ticket);

echo $url . "\n";
