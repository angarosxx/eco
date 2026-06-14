<?php

namespace Eco\Services;

use Exception;

class UploadService {
    
    private array $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
    private int $maxFileSize = 5 * 1024 * 1024; // 5 Megabytes límite por foto
    private string $uploadDir = '/var/www/html/public/uploads/';

    /**
     * Procesa de forma segura una imagen subida por un cliente
     */
    public function uploadProductImage(array $fileInput): string {
        if ($fileInput['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error nativo de subida en el servidor PHP.");
        }

        if ($fileInput['size'] > $this->maxFileSize) {
            throw new Exception("El archivo excede el tamaño máximo permitido de 5MB.");
        }

        // Validación estricta del tipo de archivo en memoria binaria
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fileInput['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $this->allowedMimeTypes)) {
            throw new Exception("Formato inválido. Solo se admiten extensiones JPG, PNG o WEBP.");
        }

        // Generar un hash único e irreversible para el nombre del archivo
        $extension = pathinfo($fileInput['name'], PATHINFO_EXTENSION);
        $secureName = bin2hex(random_bytes(16)) . '.' . $extension;
        $destination = $this->uploadDir . $secureName;

        if (!move_uploaded_file($fileInput['tmp_name'], $destination)) {
            throw new Exception("No se pudo mover el archivo al almacenamiento persistente.");
        }

        // Retorna la ruta relativa para almacenar limpiamente en la base de datos
        return '/uploads/' . $secureName;
    }
}