<?php

namespace Eco\Controllers;

use Eco\Models\User;

class LoginController {
    private $userModel;

    public function __construct() {
        // Instantiate our data object gateway
        $this->userModel = new User();
    }

    public function authenticate(array $data): array {
        $email = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            return ['success' => false, 'message' => 'Por favor, rellene todos los campos requeridos.'];
        }

        // ✅ Query via our clean Model layer
        $user = $this->userModel->findByEmail($email);

        // Security check: Guard against timing leaks
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Credenciales inválidas. Inténtelo de nuevo.'];
        }

        if ((int)$user['is_active'] !== 1) {
            return ['success' => false, 'message' => 'Esta cuenta se encuentra de baja o inactiva.'];
        }

        return [
            'success'      => true,
            'user_id'      => (int)$user['id'],
            'account_type' => $user['account_type']
        ];
    }
}