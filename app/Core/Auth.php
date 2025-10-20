<?php

namespace App\Core;

/**
 * Authentication Class
 * 
 * Handles user authentication and authorization
 */
class Auth
{
    private $user = null;
    private $database;
    
    public function __construct()
    {
        $this->database = Database::getInstance();
        $this->loadUser();
    }
    
    /**
     * Load user from session
     */
    private function loadUser()
    {
        if (isset($_SESSION['user_id'])) {
            $this->user = $this->getUserById($_SESSION['user_id']);
        }
    }
    
    /**
     * Attempt to authenticate user
     */
    public function attempt($credentials)
    {
        $identifier = $credentials['username'] ?? $credentials['identifier'] ?? '';
        $password = $credentials['password'] ?? '';
        
        if (empty($identifier) || empty($password)) {
            return false;
        }
        
        // Find user by username, email, phone_number, id_number, or passport_number
        $sql = "SELECT * FROM Users WHERE 
                username = :identifier1 
                OR email = :identifier2 
                OR phone_number = :identifier3 
                OR id_number = :identifier4 
                OR passport_number = :identifier5 
                LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':identifier1', $identifier);
        $stmt->bindParam(':identifier2', $identifier);
        $stmt->bindParam(':identifier3', $identifier);
        $stmt->bindParam(':identifier4', $identifier);
        $stmt->bindParam(':identifier5', $identifier);
        $stmt->execute();
        
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            // Check if user is active
            if ($user['status'] !== 'active') {
                return false;
            }
            
            // Set session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];
            
            // Load user data
            $this->user = $user;
            
            // Update last login
            $this->updateLastLogin($user['user_id']);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Login user by ID
     */
    public function loginById($userId)
    {
        $user = $this->getUserById($userId);
        
        if ($user && $user['status'] === 'active') {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_type'] = $user['user_type'];
            $this->user = $user;
            
            $this->updateLastLogin($userId);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Logout user
     */
    public function logout()
    {
        $this->user = null;
        
        // Clear session data
        unset($_SESSION['user_id']);
        unset($_SESSION['user_type']);
        
        // Destroy session if no other data
        if (empty($_SESSION)) {
            session_destroy();
        }
    }
    
    /**
     * Check if user is authenticated
     */
    public function check()
    {
        return $this->user !== null;
    }
    
    /**
     * Check if user is guest (not authenticated)
     */
    public function guest()
    {
        return $this->user === null;
    }
    
    /**
     * Get current user
     */
    public function user()
    {
        return $this->user;
    }
    
    /**
     * Get user ID
     */
    public function id()
    {
        return $this->user ? $this->user['user_id'] : null;
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($role)
    {
        return $this->user && $this->user['user_type'] === $role;
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('administrator');
    }
    
    /**
     * Check if user is medical personnel
     */
    public function isMedicalPersonnel()
    {
        return $this->hasRole('medical_personnel');
    }
    
    /**
     * Check if user is guide
     */
    public function isGuide()
    {
        return $this->hasRole('guide');
    }
    
    /**
     * Check if user is pilgrim
     */
    public function isPilgrim()
    {
        return $this->hasRole('pilgrim');
    }
    
    /**
     * Check user permissions (basic implementation)
     */
    public function can($permission)
    {
        if (!$this->check()) {
            return false;
        }
        
        // Basic permission system based on user type
        $permissions = [
            'administrator' => ['*'], // Admin has all permissions
            'medical_personnel' => [
                'view_health_data',
                'create_health_report',
                'respond_to_emergency',
                'view_medical_requests'
            ],
            'guide' => [
                'view_group_members',
                'send_notifications',
                'view_locations'
            ],
            'pilgrim' => [
                'view_own_profile',
                'update_own_profile',
                'request_medical_help',
                'view_services'
            ]
        ];
        
        $userPermissions = $permissions[$this->user['user_type']] ?? [];
        
        return in_array('*', $userPermissions) || in_array($permission, $userPermissions);
    }
    
    /**
     * Get user by ID
     */
    private function getUserById($userId)
    {
        $sql = "SELECT * FROM Users WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Update last login timestamp
     */
    private function updateLastLogin($userId)
    {
        $sql = "UPDATE Users SET updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
    }
    
    /**
     * Hash password
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate random token
     */
    public static function generateToken($length = 32)
    {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Create user
     */
    public function createUser($userData)
    {
        // Hash password
        if (isset($userData['password'])) {
            $userData['password_hash'] = self::hashPassword($userData['password']);
            unset($userData['password']);
        }
        
        // Set default values
        $userData['status'] = $userData['status'] ?? 'active';
        $userData['created_at'] = date('Y-m-d H:i:s');
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        // Prepare SQL
        $columns = implode(', ', array_keys($userData));
        $placeholders = ':' . implode(', :', array_keys($userData));
        
        $sql = "INSERT INTO Users ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->database->prepare($sql);
        
        foreach ($userData as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        if ($stmt->execute()) {
            return $this->database->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update user
     */
    public function updateUser($userId, $userData)
    {
        // Hash password if provided
        if (isset($userData['password'])) {
            $userData['password_hash'] = self::hashPassword($userData['password']);
            unset($userData['password']);
        }
        
        $userData['updated_at'] = date('Y-m-d H:i:s');
        
        $setClause = [];
        foreach ($userData as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
        }
        
        $sql = "UPDATE Users SET " . implode(', ', $setClause) . " WHERE user_id = :user_id";
        $stmt = $this->database->prepare($sql);
        
        $stmt->bindValue(':user_id', $userId);
        foreach ($userData as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        return $stmt->execute();
    }
}

