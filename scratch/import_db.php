<?php

$host = '127.0.0.1';
$db   = 'hp_16';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Conectado al servidor MySQL.\n";
    
    echo "Recreando la base de datos '$db'...\n";
    $pdo->exec("DROP DATABASE IF EXISTS $db");
    $pdo->exec("CREATE DATABASE $db");
    $pdo->exec("USE $db");
    echo "Base de datos '$db' recreada con éxito.\n";
    
    $sqlFile = __DIR__ . '/../database/hp_15.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("El archivo SQL '$sqlFile' no existe.");
    }
    
    echo "Leyendo el archivo SQL...\n";
    $sql = file_get_contents($sqlFile);
    
    echo "Importando estructura y datos en '$db'...\n";
    // Ejecutamos la consulta completa
    $pdo->exec($sql);
    
    echo "¡Base de datos '$db' importada con éxito!\n";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
