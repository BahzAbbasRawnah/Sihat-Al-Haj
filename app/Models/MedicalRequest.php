<?php

namespace App\Models;

use App\Core\Model;

class MedicalRequest extends Model
{
    protected $table = 'Medical_Requests';
    protected $primaryKey = 'request_id';

    public function getUrgentRequests($limit = 10)
    {
        $query = "SELECT mr.*, p.*, u.first_name_ar, u.last_name_ar, 
                         mt.team_name_ar as assigned_team_name
                  FROM {$this->table} mr
                  JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                  JOIN Users u ON p.user_id = u.user_id
                  LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
                  WHERE mr.urgency_level IN ('critical', 'high') AND mr.status = 'pending'
                  ORDER BY FIELD(mr.urgency_level, 'critical', 'high'), mr.requested_at ASC
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingRequests($limit = 20)
    {
        $query = "SELECT mr.*, p.*, u.first_name_ar, u.last_name_ar,
                         mt.team_name_ar as assigned_team_name
                  FROM {$this->table} mr
                  JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                  JOIN Users u ON p.user_id = u.user_id
                  LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
                  WHERE mr.status = 'pending'
                  ORDER BY mr.requested_at DESC
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getDashboardStats()
    {
        $stats = [
            'urgent' => 0,
            'pending' => 0,
            'in_progress' => 0,
            'completed_today' => 0,
            'total_pilgrims' => 0
        ];

        // Urgent requests
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE urgency_level IN ('critical', 'high') AND status = 'pending'";
        $result = $this->database->query($query)->fetch();
        $stats['urgent'] = $result['count'] ?? 0;

        // Pending requests
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'";
        $result = $this->database->query($query)->fetch();
        $stats['pending'] = $result['count'] ?? 0;

        // In progress requests
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'in_progress'";
        $result = $this->database->query($query)->fetch();
        $stats['in_progress'] = $result['count'] ?? 0;

        // Completed today
        $query = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'resolved' AND DATE(resolved_at) = CURDATE()";
        $result = $this->database->query($query)->fetch();
        $stats['completed_today'] = $result['count'] ?? 0;

        // Total pilgrims
        $query = "SELECT COUNT(*) as count FROM Pilgrims";
        $result = $this->database->query($query)->fetch();
        $stats['total_pilgrims'] = $result['count'] ?? 0;

        return $stats;
    }

    public function getAllWithPilgrimInfo($status = null, $limit = 50)
    {
        $whereClause = $status ? "WHERE mr.status = :status" : "";
        $query = "SELECT mr.*, p.*, u.first_name_ar, u.last_name_ar,
                         mt.team_name_ar as assigned_team_name
                  FROM {$this->table} mr
                  JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                  JOIN Users u ON p.user_id = u.user_id
                  LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
                  {$whereClause}
                  ORDER BY mr.requested_at DESC
                  LIMIT :limit";
        $stmt = $this->database->prepare($query);
        if ($status) {
            $stmt->bindValue(':status', $status);
        }
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithPilgrimInfo($id)
    {
        $query = "SELECT mr.*, p.*, u.first_name_ar, u.last_name_ar,
                         mt.team_name_ar as assigned_team_name
                  FROM {$this->table} mr
                  JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                  JOIN Users u ON p.user_id = u.user_id
                  LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
                  WHERE mr.request_id = :id";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateStatus($id, $status, $teamId = null)
    {
        $query = "UPDATE {$this->table} SET status = :status";
        $params = [':id' => $id, ':status' => $status];
        
        if ($teamId) {
            $query .= ", assigned_team_id = :team_id";
            $params[':team_id'] = $teamId;
        }
        
        if ($status === 'resolved') {
            $query .= ", resolved_at = NOW()";
        }
        
        $query .= " WHERE request_id = :id";
        
        $stmt = $this->database->prepare($query);
        return $stmt->execute($params);
    }

    public function updateResponse($id, $data)
    {
        $query = "UPDATE {$this->table} SET 
                  status = :status,
                  resolved_at = NOW()
                  WHERE request_id = :id";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':status', $data['status']);
        return $stmt->execute();
    }

    public function getRequestsByStatus($status)
    {
        return $this->getAllWithPilgrimInfo($status);
    }
}