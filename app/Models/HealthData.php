<?php

namespace App\Models;

use App\Core\Model;

class HealthData extends Model
{
    protected $table = 'pilgrim_health_data';
    
    public function getByPilgrimId($pilgrimId, $limit = 10)
    {
        $sql = "SELECT * FROM {$this->table} WHERE pilgrim_id = ? ORDER BY recorded_at DESC LIMIT ?";
        return $this->query($sql, [$pilgrimId, $limit]);
    }
    
    public function getRecentByPilgrimId($pilgrimId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE pilgrim_id = ? ORDER BY recorded_at DESC LIMIT 1";
        $result = $this->query($sql, [$pilgrimId]);
        return $result ? $result[0] : null;
    }
    
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (pilgrim_id, heart_rate, temperature, oxygen_level, blood_pressure, steps, status, notes, recorded_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        return $this->execute($sql, [
            $data['pilgrim_id'],
            $data['heart_rate'] ?? null,
            $data['temperature'] ?? null,
            $data['oxygen_level'] ?? null,
            $data['blood_pressure'] ?? null,
            $data['steps'] ?? null,
            $data['status'] ?? 'normal',
            $data['notes'] ?? null
        ]);
    }
    
    public function getStats($pilgrimId)
    {
        $sql = "SELECT 
                    AVG(heart_rate) as avg_heart_rate,
                    AVG(temperature) as avg_temperature,
                    AVG(oxygen_level) as avg_oxygen_level,
                    SUM(steps) as total_steps,
                    COUNT(*) as total_records
                FROM {$this->table} 
                WHERE pilgrim_id = ? AND recorded_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
        
        $result = $this->query($sql, [$pilgrimId]);
        return $result ? $result[0] : null;
    }
}