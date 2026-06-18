<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Models\User;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método de red inválido.']);
    exit;
}

$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);

if (!$email) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Por favor proporcione una dirección de correo válida.']);
    exit;
}

try {
    $userModel = new User();
    $user = $userModel->findByEmail($email);

    // 🔒 Security standard: To mitigate account enumeration discovery vectors,
    // always respond with a success statement even if the email doesn't exist in the database matrix!
    if (!$user) {
        echo json_encode([
            'success' => true, 
            'message' => 'Si el correo electrónico coincide con una cuenta activa, recibirá un enlace de restablecimiento a la brevedad.'
        ]);
        exit;
    }

    // Future implementation: Provision secure hash signature inside token expirations matrix
    // $token = bin2hex(random_bytes(32));
    
    echo json_encode([
        'success' => true, 
        'message' => 'Si el correo electrónico coincide con una cuenta activa, recibirá un enlace de restablecimiento a la brevedad.'
    ]);
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error de procesamiento en el servidor de credenciales.']);
    exit;
}