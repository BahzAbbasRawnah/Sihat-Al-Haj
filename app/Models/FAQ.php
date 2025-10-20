<?php

namespace App\Models;

use App\Core\Model;

class FAQ extends Model
{
    protected $table = 'FAQs';
    protected $primaryKey = 'faq_id';

    public function getAllActive()
    {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY display_order ASC, faq_id ASC";
        return $this->database->query($query)->fetchAll();
    }

    public function getByCategory($category)
    {
        $query = "SELECT * FROM {$this->table} WHERE is_active = 1 AND (category_ar = :category OR category_en = :category) ORDER BY display_order ASC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategories()
    {
        $query = "SELECT DISTINCT category_ar, category_en FROM {$this->table} WHERE is_active = 1 AND category_ar IS NOT NULL ORDER BY category_ar";
        return $this->database->query($query)->fetchAll();
    }
}