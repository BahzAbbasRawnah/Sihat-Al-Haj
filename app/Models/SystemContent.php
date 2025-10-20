<?php

namespace App\Models;

use App\Core\Model;

class SystemContent extends Model
{
    protected $table = 'System_Content';
    protected $primaryKey = 'content_id';

    public function getByKey($key)
    {
        $query = "SELECT * FROM {$this->table} WHERE key_name = :key";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByType($type)
    {
        $query = "SELECT * FROM {$this->table} WHERE content_type = :type ORDER BY display_order ASC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getHeroContent()
    {
        return $this->getByType('hero');
    }

    public function getVisionValues()
    {
        return $this->getByType('vision_value');
    }

    public function getStatistics()
    {
        return $this->getByType('statistic');
    }

    public function getBenefits()
    {
        return $this->getByType('benefit');
    }

    public function getContactInfo()
    {
        return $this->getByType('contact_info');
    }
}