<?php

namespace App\Models;

use App\Core\Model;

class PilgrimGroup extends Model
{
    protected $table = 'Pilgrim_Groups';
    protected $primaryKey = 'group_id';
    
    public function getByLeaderId($leaderId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE leader_user_id = :leader_id";
        return $this->database->query($sql, [':leader_id' => $leaderId]);
    }
    
    public function getPilgrimsWithLocations($groupId)
    {
        $sql = "SELECT p.*, u.first_name_ar, u.first_name_en, u.last_name_ar, u.last_name_en,
                       pl.latitude, pl.longitude, pl.updated_at as location_updated
                FROM Pilgrims p
                JOIN Users u ON p.user_id = u.user_id
                LEFT JOIN Pilgrim_Locations pl ON p.pilgrim_id = pl.pilgrim_id
                WHERE p.group_id = :group_id";
        return $this->database->query($sql, [':group_id' => $groupId]);
    }
    
    public function getGroupHealthData($groupId)
    {
        $sql = "SELECT phd.*, p.*, u.first_name_ar, u.first_name_en, u.last_name_ar, u.last_name_en
                FROM Pilgrim_Health_Data phd
                JOIN Pilgrims p ON phd.pilgrim_id = p.pilgrim_id
                JOIN Users u ON p.user_id = u.user_id
                WHERE p.group_id = :group_id
                ORDER BY phd.recorded_at DESC";
        return $this->database->query($sql, [':group_id' => $groupId]);
    }
}