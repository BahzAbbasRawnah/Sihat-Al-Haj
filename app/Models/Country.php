<?php

namespace App\Models;

use App\Core\Model;

class Country extends Model
{
    protected $table = 'Countries';
    protected $primaryKey = 'country_id';

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY name_ar";
        return $this->database->query($query)->fetchAll();
    }

    public function getByRegion($region = null)
    {
        if ($region) {
            $query = "SELECT * FROM {$this->table} WHERE iso_code LIKE :region ORDER BY name_ar";
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':region', $region . '%');
            $stmt->execute();
            return $stmt->fetchAll();
        }
        return $this->getAll();
    }

    public function getPopular($limit = 10)
    {
        $query = "SELECT c.*, COUNT(u.user_id) as user_count 
                  FROM {$this->table} c 
                  LEFT JOIN Users u ON c.country_id = u.nationality_id 
                  GROUP BY c.country_id 
                  ORDER BY user_count DESC, c.name_ar 
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}