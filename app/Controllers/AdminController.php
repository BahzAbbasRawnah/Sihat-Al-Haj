<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Pilgrim;
use App\Models\MedicalTeam;
use App\Models\MedicalCenter;
use App\Models\Service;

/**
 * Admin Controller
 * 
 * Handles admin dashboard and management functionality
 */
class AdminController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Require authentication and admin role
        $this->requireAuth();
        $this->requireRole('administrator');
    }
    
    /**
     * Render admin view with admin layout
     */
    protected function renderAdmin($view, $data = [])
    {
        // Render view content
        ob_start();
        extract($data);
        $viewFile = VIEWS_PATH . '/pages/admin/' . $view . '.php';
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<div class='container mx-auto px-4 py-8'><div class='bg-white rounded-lg shadow p-6'><p class='text-gray-600'>الصفحة قيد التطوير</p></div></div>";
        }
        $content = ob_get_clean();
        
        // Use admin dashboard layout
        include VIEWS_PATH . '/layouts/admin_dashboard.php';
    }
    
    /**
     * Admin dashboard
     */
    public function index()
    {
        $userModel = new User();
        $pilgrimModel = new Pilgrim();
        $teamModel = new MedicalTeam();
        $centerModel = new MedicalCenter();
        $serviceModel = new Service();
        
        // Get user counts by type
        $sql = "SELECT user_type, COUNT(*) as count FROM Users GROUP BY user_type";
        $stmt = $this->database->query($sql);
        $userTypes = $stmt->fetchAll();
        $userCounts = [];
        foreach ($userTypes as $type) {
            $userCounts[$type['user_type']] = (int)$type['count'];
        }
        
        // Get medical requests statistics
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
                    SUM(CASE WHEN urgency_level = 'critical' THEN 1 ELSE 0 END) as critical
                FROM Medical_Requests";
        $stmt = $this->database->query($sql);
        $medicalRequestsStats = $stmt->fetch() ?? [];
        
        // Get pilgrim health status distribution
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN blood_type IS NOT NULL THEN 1 ELSE 0 END) as with_blood_type,
                    SUM(CASE WHEN allergies_ar IS NOT NULL OR allergies_en IS NOT NULL THEN 1 ELSE 0 END) as with_allergies
                FROM Pilgrims";
        $stmt = $this->database->query($sql);
        $pilgrimHealthStats = $stmt->fetch() ?? [];
        
        // Get monthly registration trends (last 6 months)
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as count
                FROM Users
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC";
        $stmt = $this->database->query($sql);
        $registrationTrends = $stmt->fetchAll();
        
        // Get medical requests by urgency
        $sql = "SELECT urgency_level, COUNT(*) as count 
                FROM Medical_Requests 
                GROUP BY urgency_level";
        $stmt = $this->database->query($sql);
        $requestsByUrgency = $stmt->fetchAll();
        
        // Get team status distribution
        $teamStats = $teamModel->getStatistics();
        
        // Get center status distribution
        $centerStats = $centerModel->getStatistics();
        
        // Get service statistics
        $serviceStats = $serviceModel->getStatistics();
        
        // Get dashboard statistics
        $dashboardStats = [
            'users_count' => $userModel->count(),
            'pilgrims_count' => $userCounts['pilgrim'] ?? 0,
            'medical_staff_count' => $userCounts['medical_personnel'] ?? 0,
            'guides_count' => $userCounts['guide'] ?? 0,
            'administrators_count' => $userCounts['administrator'] ?? 0,
            'medical_teams_count' => $teamModel->count(),
            'medical_centers_count' => $centerModel->count(),
            'services_count' => $serviceModel->count(),
            'medical_requests_count' => $medicalRequestsStats['total'] ?? 0,
            'pending_requests' => $medicalRequestsStats['pending'] ?? 0,
            'critical_requests' => $medicalRequestsStats['critical'] ?? 0,
            'server_performance' => 85,
            'memory_usage' => 65,
            'database_usage' => 45,
            'available_updates' => 0
        ];
        
        $data = [
            'title' => $this->language->get('admin.admin_panel'),
            'dashboardStats' => $dashboardStats,
            'stats' => [
                'users' => $userCounts,
                'teams' => $teamStats,
                'centers' => $centerStats,
                'services' => $serviceStats,
                'medical_requests' => $medicalRequestsStats,
                'pilgrim_health' => $pilgrimHealthStats,
                'system' => $this->getSystemStats()
            ],
            'charts' => [
                'registration_trends' => $registrationTrends,
                'requests_by_urgency' => $requestsByUrgency,
                'team_status' => $teamStats,
                'center_status' => $centerStats,
                'user_distribution' => $userCounts
            ],
            'recent_users' => $userModel->all(5, 0),
            'system_alerts' => $this->getSystemAlerts(),
            'recent_activities' => $this->getRecentActivities()
        ];
        
        // Render dashboard content
        ob_start();
        extract($data);
        include VIEWS_PATH . '/pages/admin/dashboard.php';
        $content = ob_get_clean();
        
        // Use admin dashboard layout
        include VIEWS_PATH . '/layouts/admin_dashboard.php';
    }
    
    /**
     * User management
     */
    public function users()
    {
        $userModel = new User();
        
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->input('search');
        $userType = $this->input('type');
        $status = $this->input('status');
        
        if ($search) {
            $users = $userModel->search($search, $userType, $limit, $offset);
            $total = count($userModel->search($search, $userType));
        } else {
            $conditions = [];
            if ($userType) $conditions['user_type'] = $userType;
            if ($status) $conditions['status'] = $status;
            
            $users = $userModel->where($conditions, $limit, $offset);
            $total = $userModel->count($conditions);
        }
        
        // Get user statistics
        $stats = [
            'active' => $userModel->count(['status' => 'active']),
            'inactive' => $userModel->count(['status' => 'inactive']),
            'suspended' => $userModel->count(['status' => 'suspended'])
        ];
        
        $data = [
            'title' => 'إدارة المستخدمين',
            'users' => $users,
            'stats' => $stats,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'search' => $search,
                'type' => $userType,
                'status' => $status
            ]
        ];
        
        return $this->renderAdmin('users', $data);
    }
    
    /**
     * Create user
     */
    public function createUser()
    {
        if ($this->isPost()) {
            return $this->handleCreateUser();
        }
        
        $data = [
            'title' => $this->language->get('admin.create_user'),
            'countries' => $this->getCountries()
        ];
        
        return $this->renderAdmin('create-user', $data);
    }
    
    /**
     * Handle user creation
     */
    private function handleCreateUser()
    {
        $userData = $this->input();
        $userModel = new User();
        
        // Basic validation
        $errors = [];
        
        if (empty($userData['first_name_ar'])) {
            $errors['first_name_ar'] = 'الاسم الأول بالعربية مطلوب';
        }
        
        if (empty($userData['last_name_ar'])) {
            $errors['last_name_ar'] = 'اسم العائلة بالعربية مطلوب';
        }
        
        if (empty($userData['email'])) {
            $errors['email'] = 'البريد الإلكتروني مطلوب';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'البريد الإلكتروني غير صحيح';
        }
        
        if (empty($userData['phone'])) {
            $errors['phone'] = 'رقم الهاتف مطلوب';
        }
        
        if (empty($userData['password'])) {
            $errors['password'] = 'كلمة المرور مطلوبة';
        } elseif (strlen($userData['password']) < 6) {
            $errors['password'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
        }
        
        if ($userData['password'] !== $userData['password_confirmation']) {
            $errors['password_confirmation'] = 'كلمة المرور غير متطابقة';
        }
        
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $userData;
            $_SESSION['flash']['error'] = 'يرجى تصحيح الأخطاء في النموذج';
            header('Location: /sihat-al-haj/admin/users/create');
            exit;
        }
        
        // Hash password
        $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        unset($userData['password'], $userData['confirm_password']);
        
        $userId = $userModel->create($userData);
        
        if ($userId) {
            $_SESSION['flash']['success'] = 'تم إضافة المستخدم بنجاح';
            header('Location: /sihat-al-haj/admin/users');
            exit;
        } else {
            $_SESSION['flash']['error'] = 'حدث خطأ أثناء إضافة المستخدم';
            $_SESSION['old_input'] = $userData;
            header('Location: /sihat-al-haj/admin/users/create');
            exit;
        }
    }
    
    /**
     * Edit user
     */
    public function editUser($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);
        
        if (!$user) {
            $this->notFound();
        }
        
        if ($this->isPost()) {
            return $this->handleEditUser($id);
        }
        
        $data = [
            'title' => $this->language->get('admin.edit_user'),
            'user' => $user,
            'countries' => $this->getCountries()
        ];
        
        return $this->renderAdmin('edit-user', $data);
    }
    
    /**
     * Handle user editing
     */
    private function handleEditUser($userId)
    {
        $userData = $this->input();
        $userModel = new User();
        
        // Validate input
        $errors = $userModel->validateUserData($userData, true);
        
        if (!empty($errors)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('validation.failed'), 422, $errors);
            }
            
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $userData;
            $this->back();
        }
        
        // Hash password if provided
        if (!empty($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }
        unset($userData['password'], $userData['confirm_password']);
        
        $success = $userModel->update($userId, $userData);
        
        if ($success) {
            $message = $this->language->get('admin.user_updated_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
            $this->redirect('/admin/users');
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
    }
    
    /**
     * Delete user
     */
    public function deleteUser($userId)
    {
        $userModel = new User();
        $user = $userModel->find($userId);
        
        if (!$user) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('User not found'), 404);
            }
            $this->notFound();
        }
        
        // Prevent deleting own account
        if ($userId == $this->getUser()['user_id']) {
            $message = $this->language->get('admin.cannot_delete_own_account');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 400);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
        
        $success = $userModel->delete($userId);
        
        if ($success) {
            $message = $this->language->get('admin.user_deleted_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
        }
        
        $this->redirect('/admin/users');
    }
    
    /**
     * Medical requests management
     */
    public function medicalRequests()
    {
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $status = $this->input('status');
        $urgency = $this->input('urgency');
        
        $conditions = [];
        if ($status) $conditions['mr.status'] = $status;
        if ($urgency) $conditions['mr.urgency_level'] = $urgency;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = 'WHERE ' . implode(' AND ', array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
            
            foreach ($conditions as $key => $value) {
                $params[":$key"] = $value;
            }
        }
        
        $sql = "SELECT mr.*, p.*, u.first_name_ar, u.first_name_en, u.last_name_ar, u.last_name_en,
                       mt.team_name_ar, mt.team_name_en
                FROM Medical_Requests mr
                JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                JOIN Users u ON p.user_id = u.user_id
                LEFT JOIN Medical_Teams mt ON mr.assigned_team_id = mt.team_id
                $whereClause
                ORDER BY 
                    CASE mr.urgency_level 
                        WHEN 'critical' THEN 1 
                        WHEN 'high' THEN 2 
                        WHEN 'medium' THEN 3 
                        ELSE 4 
                    END,
                    mr.requested_at DESC
                LIMIT $limit OFFSET $offset";
        
        $stmt = $this->database->prepare($sql);
        $stmt->execute($params);
        $requests = $stmt->fetchAll();
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total
                     FROM Medical_Requests mr
                     JOIN Pilgrims p ON mr.pilgrim_id = p.pilgrim_id
                     JOIN Users u ON p.user_id = u.user_id
                     $whereClause";
        
        $stmtCount = $this->database->prepare($countSql);
        $stmtCount->execute($params);
        $totalResult = $stmtCount->fetch();
        $total = $totalResult['total'] ?? 0;
        
        $data = [
            'title' => $this->language->get('admin.medical_requests'),
            'requests' => $requests,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'status' => $status,
                'urgency' => $urgency
            ]
        ];
        
        return $this->renderAdmin('medical-requests', $data);
    }
    
    /**
     * System settings
     */
    public function settings()
    {
        if ($this->isPost()) {
            return $this->handleSettings();
        }
        
        $data = [
            'title' => $this->language->get('admin.system_settings'),
            'settings' => $this->getSystemSettings()
        ];
        
        return $this->renderAdmin('settings', $data);
    }
    
    /**
     * Handle settings update
     */
    private function handleSettings()
    {
        $settings = $this->input();
        
        // TODO: Implement settings update logic
        
        $_SESSION['flash']['success'] = $this->language->get('admin.settings_updated_successfully');
        $this->redirect('/admin/settings');
    }
    
    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        $stats = [];
        
        // Database size
        $sql = "SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()";
        $stmt = $this->database->query($sql);
        $result = $stmt->fetch();
        $stats['database_size'] = $result['size_mb'] ?? 0;
        
        // Active sessions
        $stats['active_sessions'] = count(glob(session_save_path() . '/sess_*'));
        
        // System uptime (placeholder)
        $stats['system_uptime'] = '24 hours';
        
        return $stats;
    }
    
    /**
     * Get system alerts
     */
    private function getSystemAlerts()
    {
        $alerts = [];
        
        // Critical medical requests
        $sql = "SELECT COUNT(*) as count FROM Medical_Requests WHERE urgency_level = 'critical' AND status = 'pending'";
        $stmt = $this->database->query($sql);
        $result = $stmt->fetch();
        $criticalRequests = $result['count'] ?? 0;
        
        if ($criticalRequests > 0) {
            $alerts[] = [
                'type' => 'emergency',
                'title' => $this->language->get('Critical Medical Requests'),
                'message' => $this->language->get('{count} critical medical requests pending', ['count' => $criticalRequests]),
                'action_url' => '/admin/medical-requests?urgency=critical'
            ];
        }
        
        // Inactive users
        $sql = "SELECT COUNT(*) as count FROM Users WHERE status = 'inactive' AND user_type = 'medical_personnel'";
        $stmt = $this->database->query($sql);
        $result = $stmt->fetch();
        $inactivePersonnel = $result['count'] ?? 0;
        
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
     * Get recent activities
     */
    private function getRecentActivities()
    {
        // TODO: Implement activity logging and retrieval
        return [];
    }
    
    /**
     * Get system settings
     */
    private function getSystemSettings()
    {
        // TODO: Implement settings storage and retrieval
        return [
            'site_name' => 'Sihat Al-Hajj Platform',
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'email_notifications' => true
        ];
    }
    
    /**
     * Medical Teams Management
     */
    public function medicalTeams()
    {
        $teamModel = new MedicalTeam();
        
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->input('search');
        $status = $this->input('status');
        
        if ($search) {
            $teams = $teamModel->search($search, $limit, $offset);
            $total = count($teamModel->search($search));
        } else {
            $conditions = [];
            if ($status) $conditions['status'] = $status;
            
            if (!empty($conditions)) {
                $teams = $teamModel->where($conditions, $limit, $offset);
                $total = $teamModel->count($conditions);
            } else {
                $teams = $teamModel->getAll($limit, $offset);
                $total = $teamModel->count();
            }
        }
        
        $data = [
            'title' => $this->language->get('admin.medical_teams'),
            'teams' => $teams,
            'stats' => $teamModel->getStatistics(),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'search' => $search,
                'status' => $status
            ]
        ];
        
        return $this->renderAdmin('medical-teams', $data);
    }
    
    /**
     * Create medical team
     */
    public function createMedicalTeam()
    {
        if ($this->isPost()) {
            return $this->handleCreateMedicalTeam();
        }
        
        $data = [
            'title' => $this->language->get('admin.create_medical_team')
        ];
        
        return $this->renderAdmin('create-medical-team', $data);
    }
    
    /**
     * Handle medical team creation
     */
    private function handleCreateMedicalTeam()
    {
        $teamData = $this->input();
        $teamModel = new MedicalTeam();
        
        // Validate input
        $errors = $teamModel->validateTeamData($teamData);
        
        if (!empty($errors)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('validation.failed'), 422, $errors);
            }
            
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $teamData;
            $this->back();
        }
        
        $teamId = $teamModel->create($teamData);
        
        if ($teamId) {
            $message = $this->language->get('admin.team_created_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message, ['team_id' => $teamId]);
            }
            
            $_SESSION['flash']['success'] = $message;
            $this->redirect('/admin/medical-teams');
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
    }
    
    /**
     * Edit medical team
     */
    public function editMedicalTeam($id)
    {
        $teamModel = new MedicalTeam();
        $team = $teamModel->find($id);
        
        if (!$team) {
            $this->notFound();
        }
        
        if ($this->isPost()) {
            return $this->handleEditMedicalTeam($id);
        }
        
        $data = [
            'title' => 'تعديل الفريق الطبي',
            'team' => $team,
            'team_members' => $teamModel->getTeamMembers($id) ?? []
        ];
        
        return $this->renderAdmin('edit-medical-team', $data);
    }
    
    /**
     * Handle medical team editing
     */
    private function handleEditMedicalTeam($teamId)
    {
        $teamData = $this->input();
        $teamModel = new MedicalTeam();
        
        // Validate input
        $errors = $teamModel->validateTeamData($teamData, true);
        
        if (!empty($errors)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('validation.failed'), 422, $errors);
            }
            
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $teamData;
            $this->back();
        }
        
        $success = $teamModel->update($teamId, $teamData);
        
        if ($success) {
            $message = $this->language->get('admin.team_updated_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
            $this->redirect('/admin/medical-teams');
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
    }
    
    /**
     * Delete medical team
     */
    public function deleteMedicalTeam($teamId)
    {
        $teamModel = new MedicalTeam();
        $team = $teamModel->find($teamId);
        
        if (!$team) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('Team not found'), 404);
            }
            $this->notFound();
        }
        
        $success = $teamModel->delete($teamId);
        
        if ($success) {
            $message = $this->language->get('admin.team_deleted_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
        }
        
        $this->redirect('/admin/medical-teams');
    }
    
    /**
     * Medical team details
     */
    public function medicalTeamDetails($id)
    {
        $teamModel = new MedicalTeam();
        $team = $teamModel->find($id);
        
        if (!$team) {
            $_SESSION['flash']['error'] = 'الفريق غير موجود';
            header('Location: /sihat-al-haj/admin/medical-teams');
            exit;
        }
        
        $userModel = new User();
        
        $data = [
            'title' => 'تفاصيل الفريق',
            'team' => $team,
            'team_members' => $teamModel->getTeamMembers($id) ?? [],
            'available_users' => $userModel->getAll(100) ?? []
        ];
        
        return $this->renderAdmin('medical-team-details', $data);
    }
    
    /**
     * Add team member
     */
    public function addTeamMember($id)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        
        $teamModel = new MedicalTeam();
        $input = json_decode(file_get_contents('php://input'), true);
        
        $userId = $input['user_id'] ?? null;
        $roleAr = $input['role_ar'] ?? 'عضو';
        $roleEn = $input['role_en'] ?? 'Member';
        
        if (!$userId) {
            echo json_encode(['success' => false, 'message' => 'يرجى اختيار مستخدم']);
            exit;
        }
        
        $success = $teamModel->addMember($id, $userId, $roleAr, $roleEn);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'تم إضافة العضو بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء إضافة العضو']);
        }
        exit;
    }
    
    /**
     * Update team member
     */
    public function updateTeamMember()
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        $memberId = $input['member_id'] ?? null;
        $roleAr = $input['role_ar'] ?? '';
        $roleEn = $input['role_en'] ?? '';
        
        if (!$memberId) {
            echo json_encode(['success' => false, 'message' => 'معرف العضو مطلوب']);
            exit;
        }
        
        $sql = "UPDATE Medical_Team_Members SET role_in_team_ar = :role_ar, role_in_team_en = :role_en WHERE team_member_id = :member_id";
        $stmt = $this->database->prepare($sql);
        $success = $stmt->execute([
            'role_ar' => $roleAr,
            'role_en' => $roleEn,
            'member_id' => $memberId
        ]);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'تم تحديث دور العضو بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء التحديث']);
        }
        exit;
    }
    
    /**
     * Remove team member
     */
    public function removeTeamMember($memberId)
    {
        // Clear all output buffers
        while (ob_get_level()) {
            ob_end_clean();
        }
        
        header('Content-Type: application/json');
        
        if (!$memberId) {
            echo json_encode(['success' => false, 'message' => 'معرف العضو مطلوب']);
            exit;
        }
        
        $teamModel = new MedicalTeam();
        
        try {
            $success = $teamModel->removeMember($memberId);
            
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'تم حذف العضو بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'خطأ: ' . $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * Medical Centers Management
     */
    public function medicalCenters()
    {
        $centerModel = new MedicalCenter();
        
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->input('search');
        $status = $this->input('status');
        
        if ($search) {
            $centers = $centerModel->search($search, $limit, $offset);
            $total = count($centerModel->search($search));
        } else {
            $conditions = [];
            if ($status) $conditions['status'] = $status;
            
            if (!empty($conditions)) {
                $centers = $centerModel->where($conditions, $limit, $offset);
                $total = $centerModel->count($conditions);
            } else {
                $centers = $centerModel->getAll($limit, $offset);
                $total = $centerModel->count();
            }
        }
        
        $data = [
            'title' => $this->language->get('admin.medical_centers'),
            'centers' => $centers,
            'stats' => $centerModel->getStatistics(),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'search' => $search,
                'status' => $status
            ]
        ];
        
        return $this->renderAdmin('medical-centers', $data);
    }
    
    /**
     * Create medical center
     */
    public function createMedicalCenter()
    {
        if ($this->isPost()) {
            return $this->handleCreateMedicalCenter();
        }
        
        $data = [
            'title' => $this->language->get('admin.create_medical_center')
        ];
        
        return $this->renderAdmin('create-medical-center', $data);
    }
    
    /**
     * Handle medical center creation
     */
    private function handleCreateMedicalCenter()
    {
        $centerData = $this->input();
        $centerModel = new MedicalCenter();
        
        // Basic validation
        $errors = [];
        
        if (empty($centerData['name_ar'])) {
            $errors['name_ar'] = 'الاسم بالعربية مطلوب';
        }
        
        if (empty($centerData['name_en'])) {
            $errors['name_en'] = 'الاسم بالإنجليزية مطلوب';
        }
        
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $centerData;
            $_SESSION['flash']['error'] = 'يرجى تصحيح الأخطاء في النموذج';
            header('Location: /sihat-al-haj/admin/medical-centers/create');
            exit;
        }
        
        $centerId = $centerModel->create($centerData);
        
        if ($centerId) {
            $_SESSION['flash']['success'] = 'تم إضافة المركز الطبي بنجاح';
            header('Location: /sihat-al-haj/admin/medical-centers');
            exit;
        } else {
            $_SESSION['flash']['error'] = 'حدث خطأ أثناء إضافة المركز الطبي';
            $_SESSION['old_input'] = $centerData;
            header('Location: /sihat-al-haj/admin/medical-centers/create');
            exit;
        }
    }
    
    /**
     * Edit medical center
     */
    public function editMedicalCenter($id)
    {
        $centerModel = new MedicalCenter();
        $center = $centerModel->find($id);
        
        if (!$center) {
            $this->notFound();
        }
        
        if ($this->isPost()) {
            return $this->handleEditMedicalCenter($id);
        }
        
        $data = [
            'title' => $this->language->get('admin.edit_medical_center'),
            'center' => $center
        ];
        
        return $this->renderAdmin('edit-medical-center', $data);
    }
    
    /**
     * Handle medical center editing
     */
    private function handleEditMedicalCenter($centerId)
    {
        $centerData = $this->input();
        $centerModel = new MedicalCenter();
        
        // Basic validation
        $errors = [];
        
        if (empty($centerData['name_ar'])) {
            $errors['name_ar'] = 'الاسم بالعربية مطلوب';
        }
        
        if (empty($centerData['name_en'])) {
            $errors['name_en'] = 'الاسم بالإنجليزية مطلوب';
        }
        
        if (!empty($errors)) {
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $centerData;
            $_SESSION['flash']['error'] = 'يرجى تصحيح الأخطاء في النموذج';
            header('Location: /sihat-al-haj/admin/medical-centers/edit/' . $centerId);
            exit;
        }
        
        $success = $centerModel->update($centerId, $centerData);
        
        if ($success) {
            $_SESSION['flash']['success'] = 'تم تحديث المركز الطبي بنجاح';
            header('Location: /sihat-al-haj/admin/medical-centers');
            exit;
        } else {
            $_SESSION['flash']['error'] = 'حدث خطأ أثناء تحديث المركز الطبي';
            header('Location: /sihat-al-haj/admin/medical-centers/edit/' . $centerId);
            exit;
        }
    }
    
    /**
     * Delete medical center
     */
    public function deleteMedicalCenter($centerId)
    {
        $centerModel = new MedicalCenter();
        $center = $centerModel->find($centerId);
        
        if (!$center) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('Center not found'), 404);
            }
            $this->notFound();
        }
        
        $success = $centerModel->delete($centerId);
        
        if ($success) {
            $message = $this->language->get('admin.center_deleted_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
        }
        
        $this->redirect('/admin/medical-centers');
    }
    
    /**
     * Medical center details
     */
    public function medicalCenterDetails($id)
    {
        $centerModel = new MedicalCenter();
        $center = $centerModel->find($id);
        
        if (!$center) {
            $_SESSION['flash']['error'] = 'المركز الطبي غير موجود';
            header('Location: /sihat-al-haj/admin/medical-centers');
            exit;
        }
        
        $data = [
            'title' => 'تفاصيل المركز الطبي',
            'center' => $center
        ];
        
        return $this->renderAdmin('medical-center-details', $data);
    }
    
    /**
     * Medical Personnel Management
     */
    public function medicalPersonnel()
    {
        $userModel = new User();
        
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->input('search');
        $status = $this->input('status');
        
        $conditions = ['user_type' => 'medical_personnel'];
        if ($status) $conditions['status'] = $status;
        
        if ($search) {
            $users = $userModel->search($search, 'medical_personnel', $limit, $offset);
            $total = count($userModel->search($search, 'medical_personnel'));
        } else {
            $users = $userModel->where($conditions, $limit, $offset);
            $total = $userModel->count($conditions);
        }
        
        $data = [
            'title' => $this->language->get('admin.medical_personnel'),
            'users' => $users,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'search' => $search,
                'status' => $status
            ]
        ];
        
        return $this->renderAdmin('medical-personnel', $data);
    }
    
    /**
     * Services Management
     */
    public function services()
    {
        $serviceModel = new Service();
        
        $page = (int)($this->input('page') ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        $search = $this->input('search');
        $status = $this->input('status');
        
        if ($search) {
            $services = $serviceModel->search($search, $limit, $offset);
            $total = count($serviceModel->search($search));
        } else {
            if ($status === 'active') {
                $services = $serviceModel->getAllActive($limit, $offset);
                $total = $serviceModel->count(['is_active' => 1]);
            } elseif ($status === 'inactive') {
                $services = $serviceModel->getInactive($limit, $offset);
                $total = $serviceModel->count(['is_active' => 0]);
            } else {
                $services = $serviceModel->getAll($limit, $offset);
                $total = $serviceModel->count();
            }
        }
        
        $data = [
            'title' => $this->language->get('admin.services'),
            'services' => $services,
            'stats' => $serviceModel->getStatistics(),
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_items' => $total,
                'per_page' => $limit
            ],
            'filters' => [
                'search' => $search,
                'status' => $status
            ]
        ];
        
        return $this->renderAdmin('services', $data);
    }
    
    /**
     * Create service
     */
    public function createService()
    {
        if ($this->isPost()) {
            return $this->handleCreateService();
        }
        
        $data = [
            'title' => $this->language->get('admin.create_service')
        ];
        
        return $this->renderAdmin('create-service', $data);
    }
    
    /**
     * Handle service creation
     */
    private function handleCreateService()
    {
        $serviceData = $this->input();
        $serviceModel = new Service();
        
        // Validate input
        $errors = $serviceModel->validateServiceData($serviceData);
        
        if (!empty($errors)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('validation.failed'), 422, $errors);
            }
            
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $serviceData;
            $this->back();
        }
        
        // Set default values
        if (!isset($serviceData['is_active'])) {
            $serviceData['is_active'] = 1;
        }
        
        $serviceId = $serviceModel->create($serviceData);
        
        if ($serviceId) {
            $message = $this->language->get('admin.service_created_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message, ['service_id' => $serviceId]);
            }
            
            $_SESSION['flash']['success'] = $message;
            $this->redirect('/admin/services');
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
    }
    
    /**
     * Edit service
     */
    public function editService($id)
    {
        $serviceModel = new Service();
        $service = $serviceModel->find($id);
        
        if (!$service) {
            $this->notFound();
        }
        
        if ($this->isPost()) {
            return $this->handleEditService($id);
        }
        
        $data = [
            'title' => $this->language->get('admin.edit_service'),
            'service' => $service
        ];
        
        return $this->renderAdmin('edit-service', $data);
    }
    
    /**
     * Handle service editing
     */
    private function handleEditService($serviceId)
    {
        $serviceData = $this->input();
        $serviceModel = new Service();
        
        // Validate input
        $errors = $serviceModel->validateServiceData($serviceData, true);
        
        if (!empty($errors)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('validation.failed'), 422, $errors);
            }
            
            $_SESSION['validation_errors'] = $errors;
            $_SESSION['old_input'] = $serviceData;
            $this->back();
        }
        
        $success = $serviceModel->update($serviceId, $serviceData);
        
        if ($success) {
            $message = $this->language->get('admin.service_updated_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
            $this->redirect('/admin/services');
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
            $this->back();
        }
    }
    
    /**
     * Delete service
     */
    public function deleteService($serviceId)
    {
        $serviceModel = new Service();
        $service = $serviceModel->find($serviceId);
        
        if (!$service) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('Service not found'), 404);
            }
            $this->notFound();
        }
        
        $success = $serviceModel->delete($serviceId);
        
        if ($success) {
            $message = $this->language->get('admin.service_deleted_successfully');
            
            if ($this->isAjaxRequest()) {
                return $this->success($message);
            }
            
            $_SESSION['flash']['success'] = $message;
        } else {
            $message = $this->language->get('messages.error');
            
            if ($this->isAjaxRequest()) {
                return $this->error($message, 500);
            }
            
            $_SESSION['flash']['error'] = $message;
        }
        
        $this->redirect('/admin/services');
    }
    
    /**
     * Toggle service status
     */
    public function toggleServiceStatus($serviceId)
    {
        $serviceModel = new Service();
        $success = $serviceModel->toggleStatus($serviceId);
        
        if ($success) {
            return $this->success('Status updated successfully');
        } else {
            return $this->error('Failed to update status', 500);
        }
    }
    
    /**
     * Get available medical personnel (not assigned to a specific team)
     */
    private function getAvailableMedicalPersonnel($excludeTeamId = null)
    {
        $sql = "SELECT u.* FROM Users u
                WHERE u.user_type = 'medical_personnel' 
                AND u.status = 'active'";
        
        if ($excludeTeamId) {
            $sql .= " AND u.user_id NOT IN (
                        SELECT user_id FROM Medical_Team_Members 
                        WHERE team_id = :team_id
                     )";
        }
        
        $sql .= " ORDER BY u.first_name_ar";
        
        $stmt = $this->database->prepare($sql);
        
        if ($excludeTeamId) {
            $stmt->bindParam(':team_id', $excludeTeamId);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get countries for forms
     */
    private function getCountries()
    {
        $sql = "SELECT * FROM Countries ORDER BY name_ar, name_en";
        $stmt = $this->database->query($sql);
        return $stmt->fetchAll();
    }
    
    /**
     * Reports page
     */
    public function reports()
    {
        $data = [
            'title' => 'التقارير',
            'reports' => []
        ];
        
        // Render reports content
        ob_start();
        extract($data);
        ?>
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-primary mb-6">التقارير</h2>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600">صفحة التقارير قيد التطوير</p>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        
        // Use admin dashboard layout
        include VIEWS_PATH . '/layouts/admin_dashboard.php';
    }
    
    /**
     * Analytics page
     */
    public function analytics()
    {
        $data = [
            'title' => 'التحليلات',
            'analytics' => []
        ];
        
        // Render analytics content
        ob_start();
        extract($data);
        ?>
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold text-primary mb-6">التحليلات</h2>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600">صفحة التحليلات قيد التطوير</p>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        
        // Use admin dashboard layout
        include VIEWS_PATH . '/layouts/admin_dashboard.php';
    }
}

