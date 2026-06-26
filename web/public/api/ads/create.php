<?php
//$customSessionPath = __DIR__ . '/../../../sessions';
//if (!is_dir($customSessionPath)) {
//    mkdir($customSessionPath, 0700, true);
//}
//session_save_path($customSessionPath);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL); // 🔥 Activamos reportes totales para desarrollo
ini_set('display_errors', '0'); // Mantenemos en 0 para no corromper el JSON

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Models\Listing;

// Proteger el endpoint
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Sesión no autorizada.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido.']);
    exit;
}

try {
    $imageUrl = null;

    // Manejo profesional de subida de archivos
    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['ad_image']['tmp_name'];
        $fileName = $_FILES['ad_image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        
        if (in_array($fileExtension, $allowedExtensions)) {
            // 🔥 CORRECCIÓN: Ruta física real dentro de la carpeta public de Apache
            $uploadFileDir = __DIR__ . '/../../uploads/'; 
            
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $uploadFileDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imageUrl = '/uploads/' . $newFileName;
            }
        }
    }

    // Empaquetar datos para la capa del Modelo
    $adData = [
        'user_id'     => (int)$_SESSION['user_id'],
        'title'       => $_POST['title'] ?? '',
        'description' => $_POST['description'] ?? '',
        'price'       => $_POST['price'] ?? 0,
        'region_id'   => $_POST['region_id'] ?? 0,
        'comuna_id'   => $_POST['comuna_id'] ?? 0,
        'image_url'   => $imageUrl
    ];

    $listingModel = new Listing();
    if ($listingModel->create($adData)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el registro en la base de datos.']);
    }
    exit;

} catch (\Exception $e) {
    http_response_code(500);
    // 🔥 MEJORA: Te devuelve el error real para no ir a ciegas si falla la BD
    echo json_encode([
        'success' => false, 
        'message' => 'Fallo interno al procesar el anuncio: ' . $e->getMessage()
    ]);
    exit;
}