<?php

namespace App\Models;

use App\Core\Model;

class Pilgrim extends Model
{
    protected $table = 'Pilgrims';
    protected $primaryKey = 'pilgrim_id';

    public function findByUserId($userId)
    {
        $query = "SELECT p.*, u.first_name_ar, u.last_name_ar, u.phone_number 
                  FROM {$this->table} p 
                  JOIN Users u ON p.user_id = u.user_id 
                  WHERE p.user_id = :user_id";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getAll()
    {
        $query = "SELECT p.*, u.first_name_ar, u.last_name_ar, u.phone_number, u.user_id
                  FROM {$this->table} p 
                  JOIN Users u ON p.user_id = u.user_id 
                  ORDER BY u.first_name_ar";
        return $this->database->query($query)->fetchAll();
    }
    
    public function getAllWithHealthData()
    {
        $query = "SELECT p.*, u.first_name_ar, u.last_name_ar, u.phone_number,
                         TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS age,
                         u.gender,
                         COUNT(phd.health_data_id) as health_records_count
                  FROM {$this->table} p 
                  JOIN Users u ON p.user_id = u.user_id 
                  LEFT JOIN Pilgrim_Health_Data phd ON p.pilgrim_id = phd.pilgrim_id
                  GROUP BY p.pilgrim_id
                  ORDER BY u.first_name_ar";
        return $this->database->query($query)->fetchAll();
    }
    
    public function getRecentHealthRecord($pilgrimId)
    {
        $query = "SELECT * FROM Pilgrim_Health_Data 
                  WHERE pilgrim_id = :pilgrim_id 
                  ORDER BY recorded_at DESC 
                  LIMIT 1";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function getGroups()
    {
        $query = "SELECT * FROM Pilgrim_Groups ORDER BY group_name_ar";
        return $this->database->query($query)->fetchAll();
    }
    
    public function getHealthData($pilgrimId)
    {
        $query = "SELECT * FROM Pilgrim_Health_Data 
                  WHERE pilgrim_id = :pilgrim_id 
                  ORDER BY recorded_at DESC 
                  LIMIT 10";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getChronicDiseases($pilgrimId)
    {
        $query = "SELECT cd.name_ar, cd.name_en, cd.risk_level, 
                         pcd.notes_ar, pcd.notes_en, pcd.diagnosed_at,
                         pcd.pilgrim_disease_id
                  FROM Pilgrim_Chronic_Diseases pcd
                  JOIN Chronic_Diseases cd ON pcd.disease_id = cd.disease_id
                  WHERE pcd.pilgrim_id = :pilgrim_id
                  ORDER BY pcd.diagnosed_at DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getHealthReports($pilgrimId)
    {
        $query = "SELECT hr.*, u.first_name_ar as doctor_name
                  FROM Health_Reports hr
                  LEFT JOIN Users u ON hr.reporter_user_id = u.user_id
                  WHERE hr.pilgrim_id = :pilgrim_id
                  ORDER BY hr.report_date DESC
                  LIMIT 3";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllHealthData($pilgrimId)
    {
        $query = "SELECT phd.*, u.first_name_ar as recorded_by_name
                  FROM Pilgrim_Health_Data phd
                  LEFT JOIN Users u ON phd.recorded_by_user_id = u.user_id
                  WHERE phd.pilgrim_id = :pilgrim_id 
                  ORDER BY phd.recorded_at DESC 
                  LIMIT 50";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getHealthStats($pilgrimId)
    {
        $query = "SELECT 
                    measurement_type_en,
                    measurement_type_ar,
                    COUNT(*) as count,
                    AVG(CAST(measurement_value AS DECIMAL(10,2))) as avg_value,
                    MAX(CAST(measurement_value AS DECIMAL(10,2))) as max_value,
                    MIN(CAST(measurement_value AS DECIMAL(10,2))) as min_value,
                    MAX(recorded_at) as last_recorded
                  FROM Pilgrim_Health_Data 
                  WHERE pilgrim_id = :pilgrim_id AND measurement_value REGEXP '^[0-9]+\.?[0-9]*$'
                  GROUP BY measurement_type_en, measurement_type_ar
                  ORDER BY last_recorded DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getAllHealthReports($pilgrimId)
    {
        $query = "SELECT hr.*, u.first_name_ar as doctor_name, u.last_name_ar as doctor_last_name
                  FROM Health_Reports hr
                  LEFT JOIN Users u ON hr.reporter_user_id = u.user_id
                  WHERE hr.pilgrim_id = :pilgrim_id
                  ORDER BY hr.report_date DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    public function getNotifications($userId)
    {
        $query = "SELECT * FROM Notifications 
                  WHERE recipient_user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT 5";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Get all health records for a pilgrim (ordered by most recent first)
     */
    public function getHealthRecords($pilgrimId)
    {
        $query = "SELECT * FROM Pilgrim_Health_Data 
                  WHERE pilgrim_id = :pilgrim_id 
                  ORDER BY recorded_at DESC";
        $stmt = $this->database->prepare($query);
        $stmt->bindValue(':pilgrim_id', $pilgrimId);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /**
     * Add a new health record for a pilgrim
     * Inserts multiple measurements as separate rows
     */
    public function addHealthRecord($healthData)
    {
        $pilgrimId = $healthData['pilgrim_id'];
        $recordedBy = $healthData['recorded_by'] ?? null;
        $recordedAt = $healthData['recorded_at'] ?? date('Y-m-d H:i:s');
        $notes = $healthData['notes'] ?? '';
        
        $measurements = [
            'blood_pressure' => ['ar' => 'ضغط الدم', 'en' => 'Blood Pressure', 'unit_ar' => 'mmHg', 'unit_en' => 'mmHg'],
            'heart_rate' => ['ar' => 'معدل النبض', 'en' => 'Heart Rate', 'unit_ar' => 'نبضة/دقيقة', 'unit_en' => 'bpm'],
            'temperature' => ['ar' => 'درجة الحرارة', 'en' => 'Temperature', 'unit_ar' => '°س', 'unit_en' => '°C'],
            'oxygen_saturation' => ['ar' => 'تشبع الأكسجين', 'en' => 'Oxygen Saturation', 'unit_ar' => '%', 'unit_en' => '%'],
            'blood_sugar' => ['ar' => 'سكر الدم', 'en' => 'Blood Sugar', 'unit_ar' => 'mg/dL', 'unit_en' => 'mg/dL'],
            'weight' => ['ar' => 'الوزن', 'en' => 'Weight', 'unit_ar' => 'كجم', 'unit_en' => 'kg']
        ];
        
        $query = "INSERT INTO Pilgrim_Health_Data 
                  (pilgrim_id, measurement_type_ar, measurement_type_en, 
                   measurement_value, unit_ar, unit_en, recorded_at, recorded_by_user_id) 
                  VALUES 
                  (:pilgrim_id, :measurement_type_ar, :measurement_type_en, 
                   :measurement_value, :unit_ar, :unit_en, :recorded_at, :recorded_by)";
        
        $success = true;
        
        foreach ($measurements as $key => $labels) {
            if (!empty($healthData[$key])) {
                $stmt = $this->database->prepare($query);
                $stmt->bindValue(':pilgrim_id', $pilgrimId);
                $stmt->bindValue(':measurement_type_ar', $labels['ar']);
                $stmt->bindValue(':measurement_type_en', $labels['en']);
                $stmt->bindValue(':measurement_value', $healthData[$key]);
                $stmt->bindValue(':unit_ar', $labels['unit_ar']);
                $stmt->bindValue(':unit_en', $labels['unit_en']);
                $stmt->bindValue(':recorded_at', $recordedAt);
                $stmt->bindValue(':recorded_by', $recordedBy);
                
                if (!$stmt->execute()) {
                    $success = false;
                }
            }
        }
        
        // Add notes as a separate entry if provided
        if (!empty($notes)) {
            $stmt = $this->database->prepare($query);
            $stmt->bindValue(':pilgrim_id', $pilgrimId);
            $stmt->bindValue(':measurement_type_ar', 'ملاحظات');
            $stmt->bindValue(':measurement_type_en', 'Notes');
            $stmt->bindValue(':measurement_value', $notes);
            $stmt->bindValue(':unit_ar', '');
            $stmt->bindValue(':unit_en', '');
            $stmt->bindValue(':recorded_at', $recordedAt);
            $stmt->bindValue(':recorded_by', $recordedBy);
            $stmt->execute();
        }
        
        return $success;
    }
}
