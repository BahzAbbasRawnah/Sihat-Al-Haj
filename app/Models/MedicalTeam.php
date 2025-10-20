<?php

namespace App\Models;

use App\Core\Model;

/**
 * Medical Team Model
 * 
 * Handles medical team data and operations
 */
class MedicalTeam extends Model
{
    protected $table = 'Medical_Teams';
    protected $primaryKey = 'team_id';
    
    protected $fillable = [
        'team_name_ar',
        'team_name_en',
        'description_ar',
        'description_en',
        'current_location_ar',
        'current_location_en',
        'contact_number',
        'status'
    ];

    /**
     * Get all medical teams
     */
    public function getAll($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY team_name_ar";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        return $this->database->query($sql);
    }
    
    /**
     * Get available teams
     */
    public function getAvailable($limit = null, $offset = 0)
    {
        return $this->where(['status' => 'available'], $limit, $offset);
    }
    
    /**
     * Get teams by status
     */
    public function getByStatus($status, $limit = null, $offset = 0)
    {
        return $this->where(['status' => $status], $limit, $offset);
    }
    
    /**
     * Find team by ID
     */
    public function findById($id)
    {
        return $this->find($id);
    }
    
    /**
     * Search medical teams
     */
    public function search($searchTerm, $limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE team_name_ar LIKE :search 
                OR team_name_en LIKE :search 
                OR description_ar LIKE :search 
                OR description_en LIKE :search
                ORDER BY team_name_ar";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->database->prepare($sql);
        $searchParam = "%{$searchTerm}%";
        $stmt->bindParam(':search', $searchParam);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get team members with user details
     */
    public function getTeamMembers($teamId)
    {
        $sql = "SELECT mtm.*, 
                       u.first_name_ar, u.first_name_en,
                       u.last_name_ar, u.last_name_en,
                       u.email, u.phone_number, u.status as user_status
                FROM Medical_Team_Members mtm
                JOIN Users u ON mtm.user_id = u.user_id
                WHERE mtm.team_id = :team_id
                ORDER BY mtm.joined_at DESC";
        
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':team_id', $teamId);
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Add member to team
     */
    public function addMember($teamId, $userId, $roleAr = null, $roleEn = null)
    {
        $sql = "INSERT INTO Medical_Team_Members (team_id, user_id, role_in_team_ar, role_in_team_en) 
                VALUES (:team_id, :user_id, :role_ar, :role_en)";
        
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':team_id', $teamId);
        $stmt->bindValue(':user_id', $userId);
        $stmt->bindValue(':role_ar', $roleAr);
        $stmt->bindValue(':role_en', $roleEn);
        
        return $stmt->execute();
    }
    
    /**
     * Remove member from team
     */
    public function removeMember($teamMemberId)
    {
        $sql = "DELETE FROM Medical_Team_Members WHERE team_member_id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':id', $teamMemberId);
        
        return $stmt->execute();
    }
    
    /**
     * Check if user is already in team
     */
    public function isMemberInTeam($teamId, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM Medical_Team_Members 
                WHERE team_id = :team_id AND user_id = :user_id";
        
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':team_id', $teamId);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Update team status
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE team_id = :id";
        $stmt = $this->database->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id);
        
        return $stmt->execute();
    }

    /**
     * Get team statistics
     */
    public function getTeamStats()
    {
        $stats = [
            'total' => 0,
            'available' => 0,
            'on_mission' => 0,
            'on_break' => 0,
            'unavailable' => 0
        ];

        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $results = $this->database->query($sql);
        
        foreach ($results as $result) {
            $stats[$result['status']] = (int)$result['count'];
            $stats['total'] += (int)$result['count'];
        }

        return $stats;
    }
    
    /**
     * Get statistics for all teams
     */
    public function getStatistics()
    {
        return $this->getTeamStats();
    }
    
    /**
     * Validate team data
     */
    public function validateTeamData($data, $isUpdate = false)
    {
        $errors = [];
        
        if (!$isUpdate || isset($data['team_name_ar'])) {
            if (empty($data['team_name_ar'])) {
                $errors['team_name_ar'] = 'Arabic team name is required';
            }
        }
        
        if (!$isUpdate || isset($data['team_name_en'])) {
            if (empty($data['team_name_en'])) {
                $errors['team_name_en'] = 'English team name is required';
            }
        }
        
        if (isset($data['contact_number']) && !empty($data['contact_number'])) {
            if (!preg_match('/^[0-9+\-\s()]+$/', $data['contact_number'])) {
                $errors['contact_number'] = 'Invalid contact number format';
            }
        }
        
        if (isset($data['status']) && !empty($data['status'])) {
            $validStatuses = ['available', 'on_mission', 'on_break', 'unavailable'];
            if (!in_array($data['status'], $validStatuses)) {
                $errors['status'] = 'Invalid status value';
            }
        }
        
        return $errors;
    }
}