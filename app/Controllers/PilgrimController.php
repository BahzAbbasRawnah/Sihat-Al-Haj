<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Pilgrim;

class PilgrimController extends Controller
{
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = Database::getInstance();
        
        // Require authentication
        $this->requireAuth();
        
        // Require pilgrim role
        $this->requireRole('pilgrim');
    }

    public function dashboard()
    {
        $userId = Session::get('user_id');
        
        // Get current user data
        $user = $this->user();
        
        // Create pilgrim model instance
        $pilgrimModel = new Pilgrim();
        
        // Get pilgrim data using model
        $pilgrim = $pilgrimModel->findByUserId($userId);
        
        if (!$pilgrim) {
            return $this->render('pages/pilgrim/dashboard', [
                'error' => 'بيانات الحاج غير موجودة',
                'debug' => 'User ID: ' . $userId,
                'user' => $user,
                'stats' => [
                    'medical_requests' => 0,
                    'unread_notifications' => 0,
                    'group_members' => 0,
                    'chronic_diseases' => 0
                ],
                'recent_requests' => [],
                'recent_notifications' => [],
                'layout' => 'pilgrim_dashboard'
            ]);
        }
        
        // Get data using model methods
        $healthData = $pilgrimModel->getHealthData($pilgrim['pilgrim_id']);
        $chronicDiseases = $pilgrimModel->getChronicDiseases($pilgrim['pilgrim_id']);
        $healthReports = $pilgrimModel->getHealthReports($pilgrim['pilgrim_id']);
        $notifications = $pilgrimModel->getNotifications($userId);
        
        // Prepare stats
        $stats = [
            'medical_requests' => 0, // TODO: Get actual count
            'unread_notifications' => is_array($notifications) ? count(array_filter($notifications, function($n) { return !($n['is_read'] ?? true); })) : 0,
            'group_members' => 0, // TODO: Get actual count
            'chronic_diseases' => is_array($chronicDiseases) ? count($chronicDiseases) : 0
        ];
        
        return $this->render('pages/pilgrim/dashboard', [
            'user' => $user,
            'pilgrim' => $pilgrim ?: [],
            'healthData' => $healthData ?: [],
            'chronicDiseases' => $chronicDiseases ?: [],
            'healthReports' => $healthReports ?: [],
            'notifications' => $notifications ?: [],
            'stats' => $stats,
            'recent_requests' => [], // TODO: Get recent requests
            'recent_notifications' => is_array($notifications) ? array_slice($notifications, 0, 5) : [],
            'layout' => 'pilgrim_dashboard'
        ]);
    }

    public function healthData()
    {
        $userId = Session::get('user_id');
        $pilgrimModel = new Pilgrim();
        $pilgrim = $pilgrimModel->findByUserId($userId);
        
        if (!$pilgrim) {
            return $this->render('pages/pilgrim/health-data', ['error' => 'بيانات الحاج غير موجودة']);
        }
        
        $healthRecords = $pilgrimModel->getAllHealthData($pilgrim['pilgrim_id']);
        $stats = $pilgrimModel->getHealthStats($pilgrim['pilgrim_id']);
        $chronicDiseases = $pilgrimModel->getChronicDiseases($pilgrim['pilgrim_id']);
        
        return $this->render('pages/pilgrim/health-data', [
            'healthRecords' => $healthRecords,
            'stats' => $stats,
            'chronicDiseases' => $chronicDiseases,
            'layout' => 'pilgrim_dashboard'
        ]);
    }

    public function health()
    {
        $userId = Session::get('user_id');
        
        // Get pilgrim data
        $stmt = $this->db->prepare("SELECT pilgrim_id FROM Pilgrims WHERE user_id = ?");
        $stmt->execute([$userId]);
        $pilgrim = $stmt->fetch();
        
        if (!$pilgrim) {
            return $this->render('pages/pilgrim/health', ['error' => 'بيانات الحاج غير موجودة']);
        }
        
        // Get recent health data
        $stmt = $this->db->prepare("
            SELECT * FROM Pilgrim_Health_Data 
            WHERE pilgrim_id = ? 
            ORDER BY recorded_at DESC 
            LIMIT 10
        ");
        $stmt->execute([$pilgrim['pilgrim_id']]);
        $recentHealthData = $stmt->fetchAll();
        
        // Get chronic diseases
        $stmt = $this->db->prepare("
            SELECT cd.name_ar, cd.name_en, cd.risk_level, pcd.notes_ar
            FROM Pilgrim_Chronic_Diseases pcd
            JOIN Chronic_Diseases cd ON pcd.disease_id = cd.disease_id
            WHERE pcd.pilgrim_id = ?
        ");
        $stmt->execute([$pilgrim['pilgrim_id']]);
        $chronicDiseases = $stmt->fetchAll();
        
        return $this->render('pages/pilgrim/health', [
            'recentHealthData' => $recentHealthData,
            'chronicDiseases' => $chronicDiseases,
            'layout' => 'pilgrim_dashboard'
        ]);
    }

    public function medicalRequests()
    {
        $userId = Session::get('user_id');
        
        // Get pilgrim data
        $stmt = $this->db->prepare("SELECT pilgrim_id FROM Pilgrims WHERE user_id = ?");
        $stmt->execute([$userId]);
        $pilgrim = $stmt->fetch();
        
        if (!$pilgrim) {
            return $this->render('pages/pilgrim/medical-requests', ['error' => 'بيانات الحاج غير موجودة']);
        }
        
        // Get medical requests
        $stmt = $this->db->prepare("
            SELECT mr.*, mt.team_name_ar, er.response_details_ar, er.outcome_ar
            FROM Medical_Requests mr
            LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
            LEFT JOIN Emergency_Responses er ON mr.request_id = er.request_id
            WHERE mr.pilgrim_id = ?
            ORDER BY mr.requested_at DESC
        ");
        $stmt->execute([$pilgrim['pilgrim_id']]);
        $medicalRequests = $stmt->fetchAll();
        
        return $this->render('pages/pilgrim/medical-requests', [
            'medicalRequests' => $medicalRequests,
            'layout' => 'pilgrim_dashboard'
        ]);
    }

    public function notifications()
    {
        $userId = Session::get('user_id');
        
        // Get notifications
        $stmt = $this->db->prepare("
            SELECT * FROM Notifications 
            WHERE recipient_user_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();
        
        // Mark notifications as read
        $stmt = $this->db->prepare("
            UPDATE Notifications 
            SET is_read = 1, read_at = NOW() 
            WHERE recipient_user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);
        
        return $this->render('pages/pilgrim/notifications', [
            'notifications' => $notifications,
            'layout' => 'pilgrim_dashboard'
        ]);
    }

    public function support()
    {
        return $this->render('pages/pilgrim/support', [
            'layout' => 'pilgrim_dashboard'
        ]);
    }
    
    public function healthReports()
    {
        $userId = Session::get('user_id');
        $pilgrimModel = new Pilgrim();
        $pilgrim = $pilgrimModel->findByUserId($userId);
        
        if (!$pilgrim) {
            return $this->render('pages/pilgrim/health-reports', ['error' => 'بيانات الحاج غير موجودة']);
        }
        
        $healthReports = $pilgrimModel->getAllHealthReports($pilgrim['pilgrim_id']);
        
        return $this->render('pages/pilgrim/health-reports', [
            'healthReports' => $healthReports,
            'layout' => 'pilgrim_dashboard'
        ]);
    }
    
    public function addChronicDisease()
    {
        header('Content-Type: application/json');
        
        try {
            $userId = Session::get('user_id');
            
            // Get pilgrim ID
            $stmt = $this->db->prepare("SELECT pilgrim_id FROM Pilgrims WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pilgrim = $stmt->fetch();
            
            if (!$pilgrim) {
                echo json_encode(['success' => false, 'message' => 'بيانات الحاج غير موجودة']);
                return;
            }
            
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $diseaseId = $data['disease_id'] ?? null;
            $diagnosedAt = $data['diagnosed_at'] ?? null;
            $notesAr = $data['notes_ar'] ?? null;
            
            // If custom disease, create it first
            if ($diseaseId === 'custom') {
                $nameAr = $data['custom_name_ar'] ?? '';
                $nameEn = $data['custom_name_en'] ?? '';
                $riskLevel = $data['custom_risk_level'] ?? 'medium';
                
                // Insert new disease
                $stmt = $this->db->prepare("
                    INSERT INTO Chronic_Diseases (name_ar, name_en, risk_level) 
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$nameAr, $nameEn, $riskLevel]);
                $diseaseId = $this->db->lastInsertId();
            }
            
            // Check if already exists
            $stmt = $this->db->prepare("
                SELECT pilgrim_disease_id FROM Pilgrim_Chronic_Diseases 
                WHERE pilgrim_id = ? AND disease_id = ?
            ");
            $stmt->execute([$pilgrim['pilgrim_id'], $diseaseId]);
            
            if ($stmt->fetch()) {
                echo json_encode(['success' => false, 'message' => 'هذا المرض مسجل بالفعل']);
                return;
            }
            
            // Insert pilgrim chronic disease
            $stmt = $this->db->prepare("
                INSERT INTO Pilgrim_Chronic_Diseases (pilgrim_id, disease_id, notes_ar, diagnosed_at) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([
                $pilgrim['pilgrim_id'],
                $diseaseId,
                $notesAr,
                $diagnosedAt
            ]);
            
            echo json_encode(['success' => true, 'message' => 'تم إضافة المرض المزمن بنجاح']);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }
    
    public function addHealthData()
    {
        header('Content-Type: application/json');
        
        try {
            $userId = Session::get('user_id');
            
            // Get pilgrim ID
            $stmt = $this->db->prepare("SELECT pilgrim_id FROM Pilgrims WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pilgrim = $stmt->fetch();
            
            if (!$pilgrim) {
                echo json_encode(['success' => false, 'message' => 'بيانات الحاج غير موجودة']);
                return;
            }
            
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $measurementTypeAr = $data['measurement_type_ar'] ?? '';
            $measurementTypeEn = $data['measurement_type_en'] ?? '';
            $measurementValue = $data['measurement_value'] ?? '';
            $unitAr = $data['unit_ar'] ?? '';
            $unitEn = $data['unit_en'] ?? '';
            $recordedAt = $data['recorded_at'] ?? date('Y-m-d H:i:s');
            
            // Insert health data
            $stmt = $this->db->prepare("
                INSERT INTO Pilgrim_Health_Data 
                (pilgrim_id, measurement_type_ar, measurement_type_en, measurement_value, unit_ar, unit_en, recorded_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $pilgrim['pilgrim_id'],
                $measurementTypeAr,
                $measurementTypeEn,
                $measurementValue,
                $unitAr,
                $unitEn,
                $recordedAt
            ]);
            
            echo json_encode(['success' => true, 'message' => 'تم إضافة القياس الصحي بنجاح']);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }
    
    public function createMedicalRequest()
    {
        header('Content-Type: application/json');
        
        try {
            $userId = Session::get('user_id');
            
            // Get pilgrim ID
            $stmt = $this->db->prepare("SELECT pilgrim_id FROM Pilgrims WHERE user_id = ?");
            $stmt->execute([$userId]);
            $pilgrim = $stmt->fetch();
            
            if (!$pilgrim) {
                echo json_encode(['success' => false, 'message' => 'بيانات الحاج غير موجودة']);
                return;
            }
            
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $requestTypeAr = $data['request_type_ar'] ?? '';
            $requestTypeEn = $data['request_type_en'] ?? '';
            $urgencyLevel = $data['urgency_level'] ?? 'medium';
            $descriptionAr = $data['description_ar'] ?? '';
            $descriptionEn = $data['description_en'] ?? '';
            
            // Optional: Parse location if provided (for future GPS integration)
            $latitude = $data['current_latitude'] ?? null;
            $longitude = $data['current_longitude'] ?? null;
            
            // Insert medical request
            $stmt = $this->db->prepare("
                INSERT INTO Medical_Requests 
                (pilgrim_id, request_type_ar, request_type_en, urgency_level, 
                 description_ar, description_en, current_latitude, current_longitude, 
                 status, requested_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', NOW())
            ");
            $stmt->execute([
                $pilgrim['pilgrim_id'],
                $requestTypeAr,
                $requestTypeEn,
                $urgencyLevel,
                $descriptionAr,
                $descriptionEn,
                $latitude,
                $longitude
            ]);
            
            $requestId = $this->db->lastInsertId();
            
            // TODO: Send notification to medical teams
            // TODO: If critical, trigger immediate alert
            
            echo json_encode([
                'success' => true, 
                'message' => 'تم إرسال الطلب بنجاح',
                'request_id' => $requestId
            ]);
            
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ: ' . $e->getMessage()]);
        }
    }
}