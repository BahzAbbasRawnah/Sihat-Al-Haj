<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Pilgrim;

/**
 * Dashboard Controller
 * 
 * Handles user dashboard functionality
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Require authentication for all dashboard methods
        $this->requireAuth();
    }
    
    /**
     * Display main dashboard
     */
    public function index()
    {
        $user = $this->getUser();
        
        // Redirect based on user type
        switch ($user['user_type']) {
            case 'pilgrim':
                return $this->pilgrimDashboard();
            case 'medical_personnel':
                return $this->medicalDashboard();
            case 'administrator':
                return $this->adminDashboard();
            case 'guide':
                return $this->guideDashboard();
            default:
                return $this->generalDashboard();
        }
    }
    
    /**
     * Pilgrim dashboard
     */
    private function pilgrimDashboard()
    {
        $user = $this->getUser();
        $pilgrimModel = new Pilgrim();
        
        // Get or create pilgrim record
        $pilgrim = $pilgrimModel->getByUserId($user['user_id']);
        if (!$pilgrim) {
            // Create pilgrim record
            $pilgrimId = $pilgrimModel->create(['user_id' => $user['user_id']]);
            $pilgrim = $pilgrimModel->find($pilgrimId);
        }
        
        $data = [
            'title' => $this->language->get('nav.dashboard'),
            'user' => $user,
            'pilgrim' => $pilgrim,
            'stats' => $this->getPilgrimStats($pilgrim['pilgrim_id']),
            'recent_health_data' => $pilgrimModel->getHealthData($pilgrim['pilgrim_id'], 5),
            'recent_requests' => $pilgrimModel->getMedicalRequests($pilgrim['pilgrim_id'], null, 5),
            'chronic_diseases' => $pilgrimModel->getChronicDiseases($pilgrim['pilgrim_id'])
        ];
        
        return $this->render('pages/dashboard/pilgrim', $data);
    }
    
    /**
     * Medical personnel dashboard
     */
    private function medicalDashboard()
    {
        $user = $this->getUser();
        
        $data = [
            'title' => $this->language->get('Medical Dashboard'),
            'user' => $user,
            'stats' => $this->getMedicalStats(),
            'pending_requests' => $this->getPendingMedicalRequests(),
            'recent_cases' => $this->getRecentMedicalCases()
        ];
        
        return $this->render('pages/dashboard/medical', $data);
    }
    
    /**
     * Administrator dashboard
     */
    private function adminDashboard()
    {
        // Redirect to admin dashboard
        header('Location: /sihat-al-haj/admin/dashboard');
        exit;
    }
    
    /**
     * Guide dashboard
     */
    private function guideDashboard()
    {
        $user = $this->getUser();
        
        $data = [
            'title' => $this->language->get('Guide Dashboard'),
            'user' => $user,
            'stats' => $this->getGuideStats(),
            'assigned_groups' => $this->getAssignedGroups(),
            'group_activities' => $this->getGroupActivities()
        ];
        
        return $this->render('pages/dashboard/guide', $data);
    }
    
    /**
     * General dashboard for other user types
     */
    private function generalDashboard()
    {
        $user = $this->getUser();
        
        $data = [
            'title' => $this->language->get('nav.dashboard'),
            'user' => $user
        ];
        
        return $this->render('pages/dashboard/general', $data);
    }
    
    /**
     * Get pilgrim statistics
     */
    private function getPilgrimStats($pilgrimId)
    {
        $stats = [
            'health_records' => 0,
            'medical_requests' => 0,
            'emergency_contacts' => 0,
            'last_checkup' => null
        ];
        
        // Get health data count
        $sql = "SELECT COUNT(*) as count FROM Pilgrim_Health_Data WHERE pilgrim_id = :pilgrim_id";
        $result = $this->database->query($sql, [':pilgrim_id' => $pilgrimId]);
        $stats['health_records'] = $result[0]['count'] ?? 0;
        
        // Get medical requests count
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE pilgrim_id = :pilgrim_id";
        $result = $this->database->query($sql, [':pilgrim_id' => $pilgrimId]);
        $stats['medical_requests'] = $result[0]['count'] ?? 0;
        
        // Get last checkup
        $sql = "SELECT MAX(recorded_at) as last_checkup FROM Pilgrim_Health_Data WHERE pilgrim_id = :pilgrim_id";
        $result = $this->database->query($sql, [':pilgrim_id' => $pilgrimId]);
        $stats['last_checkup'] = $result[0]['last_checkup'] ?? null;
        
        return $stats;
    }
    
    /**
     * Get medical personnel statistics
     */
    private function getMedicalStats()
    {
        $stats = [
            'pending_requests' => 0,
            'completed_today' => 0,
            'emergency_cases' => 0,
            'total_cases' => 0
        ];
        
        // Get pending requests
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE status = 'pending'";
        $result = $this->database->query($sql);
        $stats['pending_requests'] = $result[0]['count'] ?? 0;
        
        // Get completed today
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE status = 'completed' AND DATE(updated_at) = CURDATE()";
        $result = $this->database->query($sql);
        $stats['completed_today'] = $result[0]['count'] ?? 0;
        
        // Get emergency cases
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE urgency_level = 'critical' AND status IN ('pending', 'in_progress')";
        $result = $this->database->query($sql);
        $stats['emergency_cases'] = $result[0]['count'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Get pending medical requests
     */
    private function getPendingMedicalRequests()
    {
        $sql = "SELECT mr.*, p.*, u.first_name_ar, u.first_name_en, u.last_name_ar, u.last_name_en
                FROM Medical_Requests mr
                JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                JOIN Users u ON p.user_id = u.user_id
                WHERE mr.status = 'pending'
                ORDER BY 
                    CASE mr.urgency_level 
                        WHEN 'critical' THEN 1 
                        WHEN 'high' THEN 2 
                        WHEN 'medium' THEN 3 
                        ELSE 4 
                    END,
                    mr.requested_at ASC
                LIMIT 10";
        
        return $this->database->query($sql);
    }
    
    /**
     * Get recent medical cases
     */
    private function getRecentMedicalCases()
    {
        $sql = "SELECT mr.*, p.*, u.first_name_ar, u.first_name_en, u.last_name_ar, u.last_name_en
                FROM Medical_Requests mr
                JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                JOIN Users u ON p.user_id = u.user_id
                WHERE mr.status IN ('completed', 'in_progress')
                ORDER BY mr.updated_at DESC
                LIMIT 10";
        
        return $this->database->query($sql);
    }
    
    /**
     * Get system alerts for admin
     */
    private function getSystemAlerts()
    {
        $alerts = [];
        
        // Check for critical medical requests
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE urgency_level = 'critical' AND status = 'pending'";
        $result = $this->database->query($sql);
        $criticalRequests = $result[0]['count'] ?? 0;
        
        if ($criticalRequests > 0) {
            $alerts[] = [
                'type' => 'emergency',
                'title' => $this->language->get('Critical Medical Requests'),
                'message' => $this->language->get('{count} critical medical requests pending', ['count' => $criticalRequests]),
                'action_url' => '/admin/medical-requests?urgency=critical'
            ];
        }
        
        // Check for inactive medical personnel
        $sql = "SELECT COUNT(*) as count FROM Users WHERE user_type = 'medical_personnel' AND status = 'inactive'";
        $result = $this->database->query($sql);
        $inactivePersonnel = $result[0]['count'] ?? 0;
        
        if ($inactivePersonnel > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => $this->language->get('Inactive Medical Personnel'),
                'message' => $this->language->get('{count} medical personnel accounts are inactive', ['count' => $inactivePersonnel]),
                'action_url' => '/admin/users?type=medical_personnel&status=inactive'
            ];
        }
        
        return $alerts;
    }
    
    /**
     * Get guide statistics
     */
    private function getGuideStats()
    {
        $user = $this->getUser();
        
        $stats = [
            'assigned_groups' => 0,
            'total_pilgrims' => 0,
            'active_requests' => 0,
            'completed_trips' => 0
        ];
        
        // TODO: Implement guide-specific statistics
        
        return $stats;
    }
    
    /**
     * Get assigned groups for guide
     */
    private function getAssignedGroups()
    {
        // TODO: Implement group assignment functionality
        return [];
    }
    
    /**
     * Get group activities
     */
    private function getGroupActivities()
    {
        // TODO: Implement group activities functionality
        return [];
    }
}

