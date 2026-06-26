<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');

require_once __DIR__ . '/../../../vendor/autoload.php';

use Eco\Models\Listing;

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Sesión no autorizada.'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Método no permitido.'
    ]);
    exit;
}

/**
 * Build a simple slug from title.
 */
function makeSlug(string $text): string
{
    $text = trim($text);
    $text = mb_strtolower($text, 'UTF-8');

    $replacements = [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ä' => 'a', 'ë' => 'e', 'ï' => 'i', 'ö' => 'o', 'ü' => 'u',
        'ñ' => 'n'
    ];

    $text = strtr($text, $replacements);
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
    $text = trim($text, '-');

    return $text !== '' ? $text : 'anuncio';
}

try {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId = (int) ($_POST['category_id'] ?? 0);
    $regionId = (int) ($_POST['region_id'] ?? 0);
    $comunaId = (int) ($_POST['comuna_id'] ?? 0);
    $priceType = trim($_POST['price_type'] ?? 'fixed');

    $price = 0;
    if ($priceType !== 'contact') {
        $price = isset($_POST['price']) && $_POST['price'] !== ''
            ? (float) $_POST['price']
            : 0;
    }

    if ($title === '' || $description === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Título y descripción son obligatorios.'
        ]);
        exit;
    }

    if ($categoryId <= 0) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Debe seleccionar una categoría.'
        ]);
        exit;
    }

    if ($regionId <= 0) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Debe seleccionar una región.'
        ]);
        exit;
    }

    if ($comunaId <= 0) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Debe seleccionar una comuna.'
        ]);
        exit;
    }

    $slug = makeSlug($title) . '-' . time();

    $imagePaths = [];
    $uploadFileDir = __DIR__ . '/../../uploads/';

    if (!is_dir($uploadFileDir) && !mkdir($uploadFileDir, 0755, true)) {
        throw new RuntimeException('No se pudo crear el directorio de subida.');
    }

    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];

        $totalFiles = count($_FILES['images']['name']);
        $maxFiles = min($totalFiles, 5);

        for ($i = 0; $i < $maxFiles; $i++) {
            $error = $_FILES['images']['error'][$i] ?? UPLOAD_ERR_NO_FILE;

            if ($error === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($error !== UPLOAD_ERR_OK) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Una de las imágenes no se pudo subir correctamente.'
                ]);
                exit;
            }

            $tmpPath = $_FILES['images']['tmp_name'][$i];
            $originalName = $_FILES['images']['name'][$i];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions, true)) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Solo se permiten imágenes JPG, JPEG, PNG o WEBP.'
                ]);
                exit;
            }

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $tmpPath);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes, true)) {
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => 'Uno de los archivos no es una imagen válida.'
                ]);
                exit;
            }

            $newFileName = bin2hex(random_bytes(16)) . '.' . $extension;
            $destPath = $uploadFileDir . $newFileName;

            if (!move_uploaded_file($tmpPath, $destPath)) {
                throw new RuntimeException('No se pudo guardar una de las imágenes.');
            }

            $imagePaths[] = '/uploads/' . $newFileName;
        }
    }

    $adData = [
        'user_id'        => (int) $_SESSION['user_id'],
        'category_id'    => $categoryId,
        'region_id'      => $regionId,
        'comuna_id'      => $comunaId,
        'title'          => $title,
        'slug'           => $slug,
        'description'    => $description,
        'price'          => $price,
        'currency'       => 'CLP',
        'ad_type_origin' => 'private',
        'status'         => 'active',
    ];

    $listingModel = new Listing();
    $listingId = $listingModel->createWithImages($adData, $imagePaths);

    if (!$listingId) {
        throw new RuntimeException('No se pudo guardar el anuncio.');
    }

    echo json_encode([
        'success' => true,
        'listing_id' => $listingId
    ]);
    exit;

} catch (Throwable $e) {
    error_log('create.php error: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Fallo interno al procesar el anuncio.'
    ]);
    exit;
}
