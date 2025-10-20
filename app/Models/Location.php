<?php

namespace App\Models;

use App\Core\Model;

class Location extends Model
{
    protected $table = 'Pilgrim_Locations';
    protected $primaryKey = 'location_id';
    
    public function getByPilgrimId($pilgrimId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE pilgrim_id = :pilgrim_id ORDER BY updated_at DESC LIMIT 1";
        $result = $this->database->query($sql, [':pilgrim_id' => $pilgrimId]);
        return $result[0] ?? null;
    }
    
    public function updateLocation($pilgrimId, $latitude, $longitude)
    {
        $sql = "INSERT INTO {$this->table} (pilgrim_id, latitude, longitude, updated_at) 
                VALUES (:pilgrim_id, :latitude, :longitude, NOW())
                ON DUPLICATE KEY UPDATE 
                latitude = :latitude, longitude = :longitude, updated_at = NOW()";
        
        return $this->database->query($sql, [
            ':pilgrim_id' => $pilgrimId,
            ':latitude' => $latitude,
            ':longitude' => $longitude
        ]);
    }
}