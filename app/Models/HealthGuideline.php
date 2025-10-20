<?php

namespace App\Models;

use App\Core\Model;

class HealthGuideline extends Model
{
    protected $table = 'Health_Guidelines';
    protected $primaryKey = 'guideline_id';

    /**
     * Get all health guidelines
     */
    public function all($limit = null, $offset = 0)
    {
        $query = "SELECT * FROM {$this->table} ORDER BY created_at DESC";
        
        if ($limit !== null) {
            $query .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->database->query($query)->fetchAll();
    }

    /**
     * Get guidelines by category
     */
    public function getByCategory($category, $lang = 'ar')
    {
        $categoryColumn = $lang === 'ar' ? 'category_ar' : 'category_en';
        $query = "SELECT * FROM {$this->table} 
                  WHERE {$categoryColumn} = :category 
                  ORDER BY created_at DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':category', $category);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get all categories
     */
    public function getCategories($lang = 'ar')
    {
        $categoryColumn = $lang === 'ar' ? 'category_ar' : 'category_en';
        $query = "SELECT DISTINCT {$categoryColumn} as category 
                  FROM {$this->table} 
                  WHERE {$categoryColumn} IS NOT NULL 
                  ORDER BY {$categoryColumn}";
        return $this->database->query($query)->fetchAll();
    }

    /**
     * Search guidelines
     */
    public function search($keyword, $lang = 'ar')
    {
        $titleColumn = $lang === 'ar' ? 'title_ar' : 'title_en';
        $contentColumn = $lang === 'ar' ? 'content_ar' : 'content_en';
        
        $query = "SELECT * FROM {$this->table} 
                  WHERE {$titleColumn} LIKE :keyword 
                  OR {$contentColumn} LIKE :keyword 
                  ORDER BY created_at DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':keyword', "%{$keyword}%");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Create a new guideline
     */
    public function create($data)
    {
        $query = "INSERT INTO {$this->table} 
                  (title_ar, title_en, content_ar, content_en, category_ar, category_en, image_url) 
                  VALUES 
                  (:title_ar, :title_en, :content_ar, :content_en, :category_ar, :category_en, :image_url)";
        
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':title_ar', $data['title_ar']);
        $stmt->bindValue(':title_en', $data['title_en']);
        $stmt->bindValue(':content_ar', $data['content_ar']);
        $stmt->bindValue(':content_en', $data['content_en']);
        $stmt->bindValue(':category_ar', $data['category_ar'] ?? null);
        $stmt->bindValue(':category_en', $data['category_en'] ?? null);
        $stmt->bindValue(':image_url', $data['image_url'] ?? null);
        
        if ($stmt->execute()) {
            return $this->database->lastInsertId();
        }
        return false;
    }

    /**
     * Update a guideline
     */
    public function update($id, $data)
    {
        $query = "UPDATE {$this->table} 
                  SET title_ar = :title_ar, 
                      title_en = :title_en, 
                      content_ar = :content_ar, 
                      content_en = :content_en, 
                      category_ar = :category_ar, 
                      category_en = :category_en, 
                      image_url = :image_url 
                  WHERE {$this->primaryKey} = :id";
        
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':title_ar', $data['title_ar']);
        $stmt->bindValue(':title_en', $data['title_en']);
        $stmt->bindValue(':content_ar', $data['content_ar']);
        $stmt->bindValue(':content_en', $data['content_en']);
        $stmt->bindValue(':category_ar', $data['category_ar'] ?? null);
        $stmt->bindValue(':category_en', $data['category_en'] ?? null);
        $stmt->bindValue(':image_url', $data['image_url'] ?? null);
        
        return $stmt->execute();
    }

    /**
     * Delete a guideline
     */
    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}
