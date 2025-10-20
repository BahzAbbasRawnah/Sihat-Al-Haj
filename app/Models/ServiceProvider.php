<?php

namespace App\Models;

use App\Core\Model;

class ServiceProvider extends Model
{
    protected $table = 'Service_Providers';
    protected $primaryKey = 'provider_id';

    public function getAllActive()
    {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name_ar";
        return $this->database->query($query)->fetchAll();
    }

    public function getFeatured($limit = 6)
    {
        $query = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}