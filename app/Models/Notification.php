<?php

namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected $table = 'Notifications';
    protected $primaryKey = 'notification_id';

    public function getRecent($limit = 10)
    {
        $query = "SELECT n.*, t.template_key, t.title_ar AS template_title_ar
                  FROM {$this->table} n
                  LEFT JOIN Notification_Templates t ON n.template_id = t.template_id
                  ORDER BY n.created_at DESC
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function sendToPilgrim($pilgrimId, $data)
    {
        $query = "INSERT INTO {$this->table} 
                  (recipient_user_id, title_ar, title_en, content_ar, content_en, type, category, priority, created_at)
                  VALUES (:user_id, :title_ar, :title_en, :content_ar, :content_en, :type, :category, :priority, NOW())";
        
        $stmt = $this->database->prepare($query);
        return $stmt->execute([
            ':user_id' => $pilgrimId,
            ':title_ar' => $data['title_ar'],
            ':title_en' => $data['title_en'] ?? $data['title_ar'],
            ':content_ar' => $data['content_ar'],
            ':content_en' => $data['content_en'] ?? $data['content_ar'],
            ':type' => $data['type'] ?? 'in-app',
            ':category' => $data['category'] ?? 'health',
            ':priority' => $data['priority'] ?? 'normal'
        ]);
    }

    public function sendToGroup($groupId, $data)
    {
        $query = "INSERT INTO {$this->table} 
                  (recipient_group_id, title_ar, title_en, content_ar, content_en, type, category, priority, created_at)
                  VALUES (:group_id, :title_ar, :title_en, :content_ar, :content_en, :type, :category, :priority, NOW())";
        
        $stmt = $this->database->prepare($query);
        return $stmt->execute([
            ':group_id' => $groupId,
            ':title_ar' => $data['title_ar'],
            ':title_en' => $data['title_en'] ?? $data['title_ar'],
            ':content_ar' => $data['content_ar'],
            ':content_en' => $data['content_en'] ?? $data['content_ar'],
            ':type' => $data['type'] ?? 'in-app',
            ':category' => $data['category'] ?? 'health',
            ':priority' => $data['priority'] ?? 'normal'
        ]);
    }

    public function sendToAll($data)
    {
        // Get all pilgrim user IDs
        $pilgrimQuery = "SELECT u.user_id FROM Users u JOIN Pilgrims p ON u.user_id = p.user_id";
        $pilgrims = $this->database->query($pilgrimQuery)->fetchAll();

        $success = true;
        foreach ($pilgrims as $pilgrim) {
            $result = $this->sendToPilgrim($pilgrim['user_id'], $data);
            if (!$result) {
                $success = false;
            }
        }

        return $success;
    }
}