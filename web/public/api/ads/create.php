<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// TEMP DEBUG LOGS: right after session starts
error_log('CREATE START host=' . gethostname());
error_log('CREATE START session_id=' . session_id());
error_log('CREATE START cookie=' . ($_COOKIE['PHPSESSID'] ?? 'none'));
error_log('CREATE START user_id=' . ($_SESSION['user_id'] ?? 'none'));

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Models\Listing;

// Proteger el endpoint
if (!isset($_SESSION['user_id'])) {
    error_log('CREATE UNAUTHORIZED host=' . gethostname());
    error_log('CREATE UNAUTHORIZED session_id=' . session_id());
    error_log('CREATE UNAUTHORIZED cookie=' . ($_COOKIE['PHPSESSID'] ?? 'none'));
    error_log('CREATE UNAUTHORIZED user_id=' . ($_SESSION['user_id'] ?? 'none'));

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

    if (isset($_FILES['ad_image']) && $_FILES['ad_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['ad_image']['tmp_name'];
        $fileName = $_FILES['ad_image']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($fileExtension, $allowedExtensions, true)) {
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
    error_log('CREATE ERROR: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fallo interno al procesar el anuncio: ' . $e->getMessage()
    ]);
    exit;
}
