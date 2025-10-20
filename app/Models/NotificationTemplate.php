<?php

namespace App\Models;

use App\Core\Model;

class NotificationTemplate extends Model
{
    protected $table = 'Notification_Templates';
    protected $primaryKey = 'template_id';

    public function getAll()
    {
        $query = "SELECT * FROM {$this->table} ORDER BY template_id DESC";
        return $this->database->query($query)->fetchAll();
    }

    public function findByKey($key)
    {
        $query = "SELECT * FROM {$this->table} WHERE template_key = :key";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        return $stmt->fetch();
    }
}
