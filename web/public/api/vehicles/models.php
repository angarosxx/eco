<?php
header('Content-Type: application/json; charset=utf-8');

// Capturar el ID de la marca enviado por el formulario
$marca_id = isset($_GET['marca_id']) ? (int)$_GET['marca_id'] : (isset($_GET['marca']) ? (int)$_GET['marca'] : 0);

if ($marca_id <= 0) {
    echo json_encode([]);
    exit;
}

// Variables de entorno nativas de tu Pod de Kubernetes
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_name = $_ENV['DB_DATABASE'] ?? ''; // Variable real en tu clúster
$db_user = $_ENV['DB_USER'] ?? '';
$db_pass = $_ENV['DB_PASSWORD'] ?? ''; // Variable real en tu clúster

try {
    // Conexión limpia usando PDO sin depender de autoloaders externos
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Consulta adaptada estrictamente a tus columnas
    $stmt = $pdo->prepare("
        SELECT id, nombre 
        FROM modelos 
        WHERE marca_id = ? AND activo = 1 
        ORDER BY nombre ASC
    ");
    $stmt->execute([$marca_id]);
    $modelos = $stmt->fetchAll();

    echo json_encode($modelos);

} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}
