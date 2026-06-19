<?php
// 1. Cargar el autoloader (Subiendo 3 niveles desde /public/api/vehicles/ hacia /html/)
require_once __DIR__ . '/../../../vendor/autoload.php'; 

// 2. Cargar variables de entorno desde la raíz del proyecto
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->safeLoad();

header('Content-Type: application/json; charset=utf-8');

// 3. Capturar el ID de la marca enviado por el formulario
// Soportamos tanto 'marca' como 'marca_id' por si tu JS usa nombres distintos
$marca_id = isset($_GET['marca_id']) ? (int)$_GET['marca_id'] : (isset($_GET['marca']) ? (int)$_GET['marca'] : 0);

if ($marca_id <= 0) {
    echo json_encode([]);
    exit;
}

// 4. Configuración de Base de Datos segura
$db_host = $_ENV['DB_HOST'] ?? 'localhost';
$db_name = $_ENV['DB_NAME'] ?? '';
$db_user = $_ENV['DB_USER'] ?? '';
$db_pass = $_ENV['DB_PASS'] ?? '';

try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // 5. Consulta adaptada estrictamente a tus columnas reales
    $stmt = $pdo->prepare("
        SELECT id, nombre 
        FROM modelos 
        WHERE marca_id = ? AND activo = 1 
        ORDER BY nombre ASC
    ");
    $stmt->execute([$marca_id]);
    $modelos = $stmt->fetchAll();

    // 6. Devolver la respuesta en JSON limpio
    echo json_encode($modelos);

} catch (PDOException $e) {
    // Si hay error, lo devolvemos en formato JSON para poder verlo en la consola (F12)
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}