<?php
// web/public/api/auth/register.php

header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔥 CUSTOM PSR-4 AUTOLOADER (Replaces Composer)
spl_autoload_register(function ($class) {
    // Project-specific namespace prefix
    $prefix = 'Eco\\';

    // Base directory for the namespace prefix (pointing back to your src folder)
    // Adjust the number of ../ depending on where your 'src' folder lives relative to this file
    $base_dir = __DIR__ . '/../../../src/';

    // Does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // No, move to the next registered autoloader
        return;
    }

    // Get the relative class name
    $relative_class = substr($class, $len);

    // Replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // If the file exists, require it
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
    
    // 2. Pass the entire $_POST array
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
        // Return verification errors gracefully
        http_response_code(400);
        echo json_encode($result);
        exit;
    }

} catch (\Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Error crítico: " . $e->getMessage()]);
    exit;
}