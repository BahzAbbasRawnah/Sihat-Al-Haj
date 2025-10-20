<?php

namespace App\Models;

use App\Core\Database;

class HajjTimeline
{
    public static function getAll()
    {
        $db = Database::getInstance();
        
        $stmt = $db->prepare("
            SELECT * FROM Hajj_Timeline
        ");
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}