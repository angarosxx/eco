<?php

namespace Eco\Auth;

use Eco\Core\Database;
use PDO;
use Exception;

class RegisterHandler {
    
    /**
     * Register a new user and create their corresponding profile under a single transaction.
     */
    public function register(array $data): array {
        $db = Database::getConnection();
        
        // Basic common validation
        $email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = $data['password'] ?? '';
        $accountType = $data['account_type'] ?? ''; // 'private' or 'company'
        
        if (!$email || strlen($password) < 8 || !in_array($accountType, ['private', 'company'])) {
            return ['success' => false, 'message' => 'Información de cuenta inválida o contraseña muy corta (mínimo 8 caracteres).'];
        }

        // Hash using modern, secure default algorithms
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Start Transaction
        $db->beginTransaction();

        try {
            // 1. Insert Core User login credentials
            $userStmt = $db->prepare("
                INSERT INTO users (email, password_hash, account_type, is_active) 
                VALUES (:email, :password_hash, :account_type, 1)
            ");
            
            $userStmt->execute([
                ':email' => $email,
                ':password_hash' => $passwordHash,
                ':account_type' => $accountType
            ]);

            // Grab the auto-incremented ID from your remote MariaDB cluster
            $userId = $db->lastInsertId();

            // 2. Branch logic based on choice
            if ($accountType === 'private') {
                $this->createPrivateProfile($db, $userId, $data);
            } else {
                $this->createCompanyProfile($db, $userId, $data);
            }

            // If everything passes, lock it down in the database permanently
            $db->commit();
            return ['success' => true, 'user_id' => $userId, 'account_type' => $accountType];

        } catch (Exception $e) {
            // If anything fails, discard everything executed in this block to keep data clean
            $db->rollBack();
            
            // Check for duplicate entry error code from MariaDB
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), '1062')) {
                return ['success' => false, 'message' => 'El correo electrónico ya se encuentra registrado.'];
            }
            
            return ['success' => false, 'message' => 'Error en el registro: ' . $e->getMessage()];
        }
    }

    /**
     * Inserts standard personal profile fields
     */
    private function createPrivateProfile(PDO $db, int $userId, array $data): void {
        $stmt = $db->prepare("
            INSERT INTO profiles_private (user_id, first_name, last_name, phone, comuna_id)
            VALUES (:user_id, :first_name, :last_name, :phone, :comuna_id)
        ");
        
        $stmt->execute([
            ':user_id'    => $userId,
            ':first_name' => strip_tags($data['first_name'] ?? ''),
            ':last_name'  => strip_tags($data['last_name'] ?? ''),
            ':phone'      => strip_tags($data['phone'] ?? null),
            ':comuna_id'  => !empty($data['comuna_id']) ? (int)$data['comuna_id'] : null
        ]);
    }

    /**
     * Inserts enterprise/business specific validation metrics
     */
    private function createCompanyProfile(PDO $db, int $userId, array $data): void {
        // Enforce company requirements strictly
        if (empty($data['company_name']) || empty($data['tax_id_vat']) || empty($data['comuna_id'])) {
            throw new Exception("Faltan datos obligatorios para el perfil de empresa (RUT, Razón Social, Comuna).");
        }

        $stmt = $db->prepare("
            INSERT INTO profiles_company (user_id, company_name, tax_id_vat, phone_business, website_url, address, comuna_id, is_verified)
            VALUES (:user_id, :company_name, :tax_id_vat, :phone_business, :website_url, :address, :comuna_id, 0)
        ");
        
        $stmt->execute([
            ':user_id'        => $userId,
            ':company_name'   => strip_tags($data['company_name']),
            ':tax_id_vat'     => strip_tags($data['tax_id_vat']), // E.g., RUT de Empresa chilena
            ':phone_business' => strip_tags($data['phone_business'] ?? ''),
            ':website_url'    => filter_var($data['website_url'] ?? '', FILTER_VALIDATE_URL) ?: null,
            ':address'        => strip_tags($data['address'] ?? ''),
            ':comuna_id'      => (int)$data['comuna_id']
        ]);
    }
}