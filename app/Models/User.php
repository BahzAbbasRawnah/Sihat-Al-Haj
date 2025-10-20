<?php

namespace App\Models;

use App\Core\Model;

/**
 * User Model
 * 
 * Handles user data and authentication
 */
class User extends Model
{
    protected $table = 'Users';
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'username',
        'password_hash',
        'email',
        'phone_number',
        'first_name_ar',
        'first_name_en',
        'last_name_ar',
        'last_name_en',
        'profile_image_url',
        'date_of_birth',
        'gender',
        'nationality_id',
        'id_number',
        'passport_number',
        'user_type',
        'status'
    ];
    
    protected $hidden = [
        'password_hash'
    ];
    
    /**
     * Get user by username or email
     */
    public function findByUsernameOrEmail($username)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username OR email = :username LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Get user by username or ID number (for login)
     */
    public function findByUsernameOrIdNumber($loginField)
    {
        $sql = "SELECT * FROM {$this->table} WHERE username = :login_field OR id_number = :login_field LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':login_field', $loginField);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Find user by multiple identifiers (username, email, phone, id_number, passport_number)
     */
    public function findByIdentifier($identifier)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 
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
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Get user's full name in current language
     */
    public function getFullName($user, $language = 'ar')
    {
        $firstName = $user["first_name_{$language}"] ?? $user['first_name_ar'];
        $lastName = $user["last_name_{$language}"] ?? $user['last_name_ar'];
        
        return trim($firstName . ' ' . $lastName);
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole($user, $role)
    {
        return $user['user_type'] === $role;
    }
    
    /**
     * Get users by type
     */
    public function getUsersByType($userType, $limit = null, $offset = 0)
    {
        return $this->where(['user_type' => $userType], $limit, $offset);
    }
    
    /**
     * Get active users
     */
    public function getActiveUsers($limit = null, $offset = 0)
    {
        return $this->where(['status' => 'active'], $limit, $offset);
    }
    
    /**
     * Update user status
     */
    public function updateStatus($userId, $status)
    {
        return $this->update($userId, ['status' => $status]);
    }
    
    /**
     * Get user statistics
     */
    public function getStatistics()
    {
        $stats = [];
        
        // Total users
        $stats['total_users'] = $this->count();
        
        // Active users
        $stats['active_users'] = $this->count(['status' => 'active']);
        
        // Users by type
        $userTypes = ['pilgrim', 'guide', 'medical_personnel', 'administrator'];
        foreach ($userTypes as $type) {
            $stats["total_{$type}s"] = $this->count(['user_type' => $type]);
            $stats["active_{$type}s"] = $this->count([
                'user_type' => $type,
                'status' => 'active'
            ]);
        }
        
        // Recent registrations (last 30 days)
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $stmt = $this->database->query($sql);
        $result = $stmt->fetch();
        $stats['recent_registrations'] = $result['count'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Search users
     */
    public function search($query, $userType = null, $limit = 20, $offset = 0)
    {
        $conditions = [];
        $params = [];
        
        // Search in names, username, email
        $searchConditions = [
            "first_name_ar LIKE :search",
            "first_name_en LIKE :search",
            "last_name_ar LIKE :search", 
            "last_name_en LIKE :search",
            "username LIKE :search",
            "email LIKE :search"
        ];
        
        $conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
        $params[':search'] = "%{$query}%";
        
        // Filter by user type
        if ($userType) {
            $conditions[] = "user_type = :user_type";
            $params[':user_type'] = $userType;
        }
        
        $whereClause = implode(" AND ", $conditions);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause} ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->database->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return array_map([$this, 'hideFields'], $results);
    }
    
    /**
     * Get user with nationality
     */
    public function getUserWithNationality($userId)
    {
        $sql = "SELECT u.*, c.name_ar as nationality_ar, c.name_en as nationality_en 
                FROM {$this->table} u 
                LEFT JOIN Countries c ON u.nationality_id = c.country_id 
                WHERE u.user_id = :user_id";
        
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Update last login
     */
    public function updateLastLogin($userId)
    {
        $sql = "UPDATE {$this->table} SET updated_at = CURRENT_TIMESTAMP WHERE user_id = :user_id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        
        return $stmt->execute();
    }
    
    /**
     * Validate user data
     */
    public function validateUserData($data, $isUpdate = false)
    {
        $errors = [];
        
        // Username validation
        if (!$isUpdate || isset($data['username'])) {
            if (empty($data['username'])) {
                $errors['username'] = 'Username is required';
            } elseif (strlen($data['username']) < 3) {
                $errors['username'] = 'Username must be at least 3 characters';
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
                $errors['username'] = 'Username can only contain letters, numbers, and underscores';
            }
        }
        
        // Email validation
        if (!$isUpdate || isset($data['email'])) {
            if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Invalid email format';
            }
        }
        
        // Phone validation
        if (!empty($data['phone_number'])) {
            if (!preg_match('/^[+]?[0-9\s\-\(\)]+$/', $data['phone_number'])) {
                $errors['phone_number'] = 'Invalid phone number format';
            }
        }
        
        // Name validation
        if (!$isUpdate || isset($data['first_name_ar'])) {
            if (empty($data['first_name_ar'])) {
                $errors['first_name_ar'] = 'Arabic first name is required';
            }
        }
        
        if (!$isUpdate || isset($data['first_name_en'])) {
            if (empty($data['first_name_en'])) {
                $errors['first_name_en'] = 'English first name is required';
            }
        }
        
        // User type validation
        if (!$isUpdate || isset($data['user_type'])) {
            $validTypes = ['pilgrim', 'guide', 'medical_personnel', 'administrator'];
            if (!in_array($data['user_type'] ?? '', $validTypes)) {
                $errors['user_type'] = 'Invalid user type';
            }
        }
        
        return $errors;
    }
    
    /**
     * Get all users with pagination and ordering
     */
    public function getAll($limit = null, $offset = 0, $orderBy = [])
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if (!empty($orderBy)) {
            $orderClauses = [];
            foreach ($orderBy as $column => $direction) {
                $orderClauses[] = "{$column} {$direction}";
            }
            $sql .= " ORDER BY " . implode(', ', $orderClauses);
        }
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        $stmt = $this->database->query($sql);
        $results = $stmt->fetchAll();
        return array_map([$this, 'hideFields'], $results);
    }
    
    /**
     * Get users with conditions
     */
    public function where($conditions = [], $limit = null, $offset = 0)
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "{$column} = :{$column}";
                $params[":{$column}"] = $value;
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }
        
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        $stmt = $this->database->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        return array_map([$this, 'hideFields'], $results);
    }
    
    /**
     * Count records with conditions
     */
    public function count($conditions = [])
    {
        $whereClause = '';
        $params = [];
        
        if (!empty($conditions)) {
            $whereParts = [];
            foreach ($conditions as $column => $value) {
                $whereParts[] = "{$column} = :{$column}";
                $params[":{$column}"] = $value;
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }
        
        $sql = "SELECT COUNT(*) as count FROM {$this->table} {$whereClause}";
        if (!empty($params)) {
            $stmt = $this->database->prepare($sql);
            $stmt->execute($params);
        } else {
            $stmt = $this->database->query($sql);
        }
        $result = $stmt->fetch();
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Find user by ID
     */
    public function find($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':id' => $userId]);
        $result = $stmt->fetch();
        
        return $result ? $this->hideFields($result) : null;
    }
    
    /**
     * Create new user
     */
    public function create($data)
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $fields['created_at'] = date('Y-m-d H:i:s');
        
        $columns = implode(', ', array_keys($fields));
        $placeholders = ':' . implode(', :', array_keys($fields));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        $params = [];
        foreach ($fields as $key => $value) {
            $params[":{$key}"] = $value;
        }
        
        $stmt = $this->database->prepare($sql);
        if ($stmt->execute($params)) {
            return $this->database->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update user
     */
    public function update($userId, $data)
    {
        $fields = array_intersect_key($data, array_flip($this->fillable));
        $fields['updated_at'] = date('Y-m-d H:i:s');
        
        $setParts = [];
        $params = [':id' => $userId];
        
        foreach ($fields as $key => $value) {
            $setParts[] = "{$key} = :{$key}";
            $params[":{$key}"] = $value;
        }
        
        $setClause = implode(', ', $setParts);
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->database->prepare($sql);
        return $stmt->execute($params) !== false;
    }
    
    /**
     * Delete user (soft delete by setting status to inactive)
     */
    public function delete($userId)
    {
        return $this->update($userId, ['status' => 'inactive']);
    }
    
    /**
     * Find first user matching conditions
     */
    public function whereFirst($conditions)
    {
        $results = $this->where($conditions, 1);
        return $results[0] ?? null;
    }
    
    /**
     * Hide sensitive fields from user data
     */
    private function hideFields($user)
    {
        if (!$user) return null;
        
        foreach ($this->hidden as $field) {
            unset($user[$field]);
        }
        
        return $user;
    }
}

