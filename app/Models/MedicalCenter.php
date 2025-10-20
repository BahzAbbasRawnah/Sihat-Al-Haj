<?php

namespace App\Models;

use App\Core\Model;

/**
 * Medical Center Model
 * 
 * Handles medical center data and operations
 */
class MedicalCenter extends Model
{
    protected $table = 'Medical_Centers';
    protected $primaryKey = 'center_id';
    
    protected $fillable = [
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'latitude',
        'longitude',
        'phone_number',
        'operating_hours_ar',
        'operating_hours_en',
        'services_offered_ar',
        'services_offered_en',
        'status',
        'icon_name'
    ];
    
    /**
     * Get all medical centers
     */
    public function getAll($limit = null, $offset = 0, $orderBy = ['name_ar' => 'ASC'])
    {
        return parent::all($limit, $offset);
    }
    
    /**
     * Get active medical centers
     */
    public function getActive($limit = null, $offset = 0)
    {
        return $this->where(['status' => 'active'], $limit, $offset);
    }
    
    /**
     * Get medical centers by status
     */
    public function getByStatus($status, $limit = null, $offset = 0)
    {
        return $this->where(['status' => $status], $limit, $offset);
    }
    
    /**
     * Search medical centers
     */
    public function search($searchTerm, $limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name_ar LIKE :search 
                OR name_en LIKE :search 
                OR address_ar LIKE :search 
                OR address_en LIKE :search
                ORDER BY name_ar";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->database->prepare($sql);
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get medical center statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0,
            'full' => 0
        ];
        
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $results = $this->database->query($sql);
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        return $stats;
    }
    
    /**
     * Update center status
     */
    public function updateStatus($centerId, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE {$this->primaryKey} = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $centerId);
        
        return $stmt->execute();
    }
    
    /**
     * Validate medical center data
     */
    public function validateCenterData($data, $isUpdate = false)
    {
        $errors = [];
        
        if (!$isUpdate || isset($data['name_ar'])) {
            if (empty($data['name_ar'])) {
                $errors['name_ar'] = 'Arabic name is required';
            }
        }
        
        if (!$isUpdate || isset($data['name_en'])) {
            if (empty($data['name_en'])) {
                $errors['name_en'] = 'English name is required';
            }
        }
        
        if (isset($data['phone_number']) && !empty($data['phone_number'])) {
            if (!preg_match('/^[0-9+\-\s()]+$/', $data['phone_number'])) {
                $errors['phone_number'] = 'Invalid phone number format';
            }
        }
        
        if (isset($data['latitude']) && !empty($data['latitude'])) {
            if (!is_numeric($data['latitude']) || $data['latitude'] < -90 || $data['latitude'] > 90) {
                $errors['latitude'] = 'Invalid latitude value';
            }
        }
        
        if (isset($data['longitude']) && !empty($data['longitude'])) {
            if (!is_numeric($data['longitude']) || $data['longitude'] < -180 || $data['longitude'] > 180) {
                $errors['longitude'] = 'Invalid longitude value';
            }
        }
        
        return $errors;
    }
}