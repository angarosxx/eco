<?php

namespace Eco\Controllers;

use Eco\Core\Database;
use Exception;

class LoginController
{
    /**
     * Autentica al usuario contra la base de datos MariaDB
     * * @param array $data Datos del $_POST (email, password)
     * @return array Resultado de la operación
     */
    public function authenticate(array $data): array
    {
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';

        if (!$email || empty($password)) {
            return [
                'success' => false,
                'message' => 'Por favor, ingresa un correo electrónico y contraseña válidos.'
            ];
        }

        try {
            // Conexión nativa a MariaDB utilizando tu Singleton Core\Database
            $db = Database::getConnection();

            // Buscamos al usuario por su correo
            $stmt = $db->prepare("
                SELECT id, password, account_type, status 
                FROM users 
                WHERE email = :email 
                LIMIT 1
            ");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);

            // 1. Validar existencia del usuario
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'Las credenciales introducidas no coinciden con nuestros registros.'
                ];
            }

            // 2. Validar si la cuenta está activa (opcional, por seguridad en plataformas B2C2B)
            if (isset($user['status']) && $user['status'] === 'suspended') {
                return [
                    'success' => false,
                    'message' => 'Tu cuenta se encuentra temporalmente suspendida.'
                ];
            }

            // 3. Verificar el Hash de la contraseña usando la función nativa segura de PHP
            if (password_verify($password, $user['password'])) {
                return [
                    'success' => true,
                    'user_id' => $user['id'],
                    'account_type' => $user['account_type'] ?? 'user'
                ];
            }

            // Si falla la contraseña, devolvemos error genérico idéntico al de no-existencia
            return [
                'success' => false,
                'message' => 'Las credenciales introducidas no coinciden con nuestros registros.'
            ];

        } catch (\PDOException $e) {
            // 🕵️‍♂️ Capturamos el error real + el estado de lo que PHP está leyendo
            $host = getenv('DB_HOST') ?: (isset($_ENV['DB_HOST']) ? $_ENV['DB_HOST'] : 'VACÍO');
            $user = getenv('DB_USER') ?: (isset($_ENV['DB_USER']) ? $_ENV['DB_USER'] : 'VACÍO');
            
            error_log('MariaDB Login Error: ' . $e->getMessage());
            
            // Forzamos el mensaje real hacia el JSON de salida
            $debugMessage = sprintf(
                "PDO Falló. Host detectado por PHP: '%s', Usuario: '%s'. Error Real: %s",
                $host,
                $user,
                $e->getMessage()
            );
            
            throw new Exception($debugMessage);
        }
    }
}