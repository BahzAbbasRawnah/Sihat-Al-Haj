<?php

namespace App\Models;

use App\Core\Model;

/**
 * Service Model
 * 
 * Handles digital services data and operations
 */
class Service extends Model
{
    protected $table = 'Services';
    protected $primaryKey = 'service_id';
    
    protected $fillable = [
        'service_name_ar',
        'service_name_en',
        'description_ar',
        'description_en',
        'icon_name',
        'is_active'
    ];
    
    /**
     * Get all services
     */
    public function getAll($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY service_name_ar";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->database->query($sql);
    }
    
    /**
     * Get active services
     */
    public function getAllActive($limit = null, $offset = 0)
    {
        return $this->where(['is_active' => 1], $limit, $offset);
    }
    
    /**
     * Get inactive services
     */
    public function getInactive($limit = null, $offset = 0)
    {
        return $this->where(['is_active' => 0], $limit, $offset);
    }
    
    /**
     * Search services
     */
    public function search($searchTerm, $limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE service_name_ar LIKE :search 
                OR service_name_en LIKE :search 
                OR description_ar LIKE :search 
                OR description_en LIKE :search
                ORDER BY service_name_ar";
        
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
     * Get service statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0
        ];
        
        $sql = "SELECT is_active, COUNT(*) as count FROM {$this->table} GROUP BY is_active";
        $results = $this->database->query($sql);
        
        foreach ($results as $result) {
            $key = $result['is_active'] ? 'active' : 'inactive';
            $stats[$key] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }
        
        return $stats;
    }

    /**
     * Get service categories
     */
    public function getServiceCategories()
    {
        $query = "SELECT DISTINCT 
                    SUBSTRING_INDEX(service_name_ar, ' ', 2) as category_ar,
                    SUBSTRING_INDEX(service_name_en, ' ', 2) as category_en,
                    icon_name,
                    COUNT(*) as service_count
                  FROM {$this->table} 
                  WHERE is_active = 1 
                  GROUP BY category_ar, category_en, icon_name
                  ORDER BY service_count DESC";
        return $this->database->query($query);
    }
    
    /**
     * Toggle service status
     */
    public function toggleStatus($serviceId)
    {
        $sql = "UPDATE {$this->table} 
                SET is_active = NOT is_active, updated_at = NOW() 
                WHERE {$this->primaryKey} = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindParam(':id', $serviceId);
        
        return $stmt->execute();
    }
    
    /**
     * Validate service data
     */
    public function validateServiceData($data, $isUpdate = false)
    {
        $errors = [];
        
        if (!$isUpdate || isset($data['service_name_ar'])) {
            if (empty($data['service_name_ar'])) {
                $errors['service_name_ar'] = 'Arabic service name is required';
            }
        }
        
        if (!$isUpdate || isset($data['service_name_en'])) {
            if (empty($data['service_name_en'])) {
                $errors['service_name_en'] = 'English service name is required';
            }
        }
        
        return $errors;
    }
}