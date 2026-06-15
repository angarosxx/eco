<?php
// web/public/api/auth/register.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ensure we only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

// In a real setup, pull your existing DB connection wrapper
// For now, we establish a clean PDO instance matching your cluster's MariaDB environment
try {
    $host = '127.0.0.1'; // Or your internal K3s MariaDB service name/IP
    $db   = 'eco_marketplace';
    $user = 'eco_user';
    $pass = 'your_password'; 
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERR_ERRORMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error de conexión a la base de datos: " . $e->getMessage()]);
    exit;
}

// Capture incoming form variables
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$region   = trim($_POST['region_id'] ?? '');
$comuna   = trim($_POST['comuna_id'] ?? '');

// Basic Validation
if (empty($name) || empty($email) || empty($password) || empty($region) || empty($comuna)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
    exit;
}

try {
    // 1. Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "El correo electrónico ya está registrado."]);
        exit;
    }

    // 2. Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // 3. Insert user record
    $insertStmt = $pdo->prepare("INSERT INTO users (name, email, password, region_id, comuna_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $insertStmt->execute([$name, $email, $hashedPassword, $region, $comuna]);

    // 4. Automatically start an authenticated session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_name'] = $name;

    // Redirect to Dashboard upon success
    header('Location: /dashboard.php');
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error al guardar el usuario: " . $e->getMessage()]);
    exit;
}