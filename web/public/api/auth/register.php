<?php
// web/public/api/auth/register.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
// 🔥 SMART NATIVE PSR-4 AUTOLOADER
spl_autoload_register(function ($class) {
    $prefix = 'Eco\\';
=======
// 🔥 NATIVE PSR-4 NAMESPACE AUTOLOADER
spl_autoload_register(function ($class) {
    $prefix = 'Eco\\';
    
    // Hardcoded absolute container path matching your ls -la output exactly
>>>>>>> 6ada6f088084e4d9273afbce35fcf83256937840
    $base_dir = '/var/www/html/src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
<<<<<<< HEAD

    // 🎯 SPECIAL RULE: If any script asks for Eco\Core\Database, redirect it to src/Database.php
    if ($relative_class === 'Core\\Database') {
        $file = $base_dir . 'Database.php';
    } else {
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    }
=======
    
    // Converts Eco\Core\Database -> /var/www/html/src/Core/Database.php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
>>>>>>> 6ada6f088084e4d9273afbce35fcf83256937840

    if (file_exists($file)) {
        require_once $file;
    }
});

use Eco\Auth\RegisterHandler;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Método no permitido"]);
    exit;
}

try {
    // 1. Instantiate your registration engine
    $handler = new RegisterHandler();
    
<<<<<<< HEAD
    // 2. Pass the entire $_POST array (maps private vs company profiles seamlessly!)
=======
    // 2. Pass the entire $_POST array (maps private vs company seamlessly!)
>>>>>>> 6ada6f088084e4d9273afbce35fcf83256937840
    $result = $handler->register($_POST);

    if ($result['success'] === true) {
        // Start session and authorize user globally
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['account_type'] = $result['account_type'];
        $_SESSION['user_name'] = strip_tags($_POST['first_name'] ?? $_POST['company_name'] ?? 'Usuario');

        // Redirect nicely to your dashboard
        header('Location: /dashboard.php');
        exit;
    } else {
        // Return validation errors gracefully
        http_response_code(400);
        echo json_encode($result);
        exit;
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error crítico: " . $e->getMessage()]);
    exit;
}