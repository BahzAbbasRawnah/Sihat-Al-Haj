<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Auth;
use App\Models\MedicalRequest;
use App\Models\MedicalTeam;
use App\Models\Pilgrim;
use App\Models\HealthReport;
use App\Models\Notification;

class MedicalTeamController extends Controller
{
    public function dashboard()
    {
        $this->requireAuth();
        
        $medicalRequest = new MedicalRequest();
        $medicalTeam = new MedicalTeam();
        
        $data = [
            'pageTitle' => 'لوحة التحكم الطبية',
            'urgent_requests' => $medicalRequest->getUrgentRequests(),
            'pending_requests' => $medicalRequest->getPendingRequests(),
            'stats' => $medicalRequest->getDashboardStats(),
            'team_stats' => $medicalTeam->getTeamStats(),
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/dashboard', $data);
    }

    public function requests()
    {
        $this->requireAuth();
        
        $medicalRequest = new MedicalRequest();
        $medicalTeam = new MedicalTeam();
        $status = $this->input('status');
        
        $data = [
            'pageTitle' => 'الطلبات الطبية',
            'requests' => $medicalRequest->getAllWithPilgrimInfo($status),
            'teams' => $medicalTeam->getAvailable(),
            'current_status' => $status,
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/requests', $data);
    }

    public function updateRequestStatus()
    {
        $this->requireAuth();
        header('Content-Type: application/json');
        
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            return $this->json(['success' => false, 'message' => 'Invalid input data']);
        }

        $requestId = $input['request_id'] ?? null;
        $status = $input['status'] ?? null;
        $teamId = $input['team_id'] ?? null;
        
        if (!$requestId || !$status) {
            return $this->json(['success' => false, 'message' => 'Request ID and status are required']);
        }

        try {
            $medicalRequest = new MedicalRequest();
            $result = $medicalRequest->updateStatus($requestId, $status, $teamId);
            
            if ($result) {
                if ($teamId && $status === 'in_progress') {
                    $medicalTeam = new MedicalTeam();
                    $medicalTeam->updateStatus($teamId, 'on_mission');
                }
                return $this->json(['success' => true, 'message' => 'تم تحديث حالة الطلب بنجاح']);
            }
            
            return $this->json(['success' => false, 'message' => 'فشل في تحديث حالة الطلب']);
            
        } catch (\Throwable $e) {
            error_log("Medical Request Update Error: " . $e->getMessage());
            return $this->json([
                'success' => false, 
                'message' => 'Database error occurred: ' . $e->getMessage()
            ]);
        }
    }

    public function patients()
    {
        $this->requireAuth();
        
        $pilgrimModel = new Pilgrim();
        $patients = $pilgrimModel->getAllWithHealthData();
        
        foreach ($patients as &$patient) {
            $patient['chronic_diseases'] = $pilgrimModel->getChronicDiseases($patient['pilgrim_id']);
            $patient['recent_health'] = $pilgrimModel->getRecentHealthRecord($patient['pilgrim_id']);
        }
        
        $data = [
            'pageTitle' => 'إدارة المرضى',
            'patients' => $patients,
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/patients', $data);
    }

    public function notifications()
    {
        $this->requireAuth();
        $pilgrim = new Pilgrim();
        $templateModel = new \App\Models\NotificationTemplate();
        $notificationModel = new \App\Models\Notification();

        $data = [
            'pageTitle' => 'إرسال الإشعارات',
            'pilgrims' => $pilgrim->getAll(),
            'groups' => $pilgrim->getGroups(),
            'templates' => $templateModel->getAll(),
            'notifications' => $notificationModel->getRecent(10),
            'layout' => 'medical_dashboard'
        ];
        return $this->render('pages/medical_team/notifications', $data);
    }

    public function sendNotification()
    {
        $this->requireAuth();
        
        $notification = new Notification();
        $type = $this->input('recipient_type');
        $title = $this->input('title_ar');
        $content = $this->input('content_ar');
        $priority = $this->input('priority', 'normal');

        $data = [
            'title_ar' => $title,
            'content_ar' => $content,
            'priority' => $priority,
            'category' => 'health'
        ];

        $result = false;
        $errorMsg = null;

        try {
            if ($type === 'all') {
                $result = $notification->sendToAll($data);
            } elseif ($type === 'group') {
                $groupId = $this->input('group_id');
                $result = $notification->sendToGroup($groupId, $data);
            } elseif ($type === 'individual') {
                $pilgrimId = $this->input('pilgrim_id');
                $result = $notification->sendToPilgrim($pilgrimId, $data);
            }
        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            error_log('Notification send error: ' . $errorMsg . "\n" . $e->getTraceAsString());
        }

        if ($result) {
            return $this->json(['success' => true, 'message' => 'تم إرسال الإشعار بنجاح']);
        }

        $msg = 'فشل في إرسال الإشعار';
        if ($errorMsg) {
            $msg .= ': ' . $errorMsg;
        }
        return $this->json(['success' => false, 'message' => $msg]);
    }

    public function viewRequest($id)
    {
        $this->requireAuth();
        
        $medicalRequest = new MedicalRequest();
        $request = $medicalRequest->getWithPilgrimInfo($id);
        
        if (!$request) {
            return $this->redirect('/medical/requests');
        }
        
        $data = [
            'pageTitle' => 'تفاصيل الطلب الطبي',
            'request' => $request,
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/request_details', $data);
    }

    public function createReport()
    {
        $this->requireAuth();
        
        $healthReport = new HealthReport();
        $user = $this->auth->user();
        
        $data = [
            'pilgrim_id' => $this->input('pilgrim_id'),
            'diagnosis_ar' => $this->input('diagnosis_ar'),
            'diagnosis_en' => $this->input('diagnosis_en'),
            'treatment_ar' => $this->input('treatment_ar'),
            'treatment_en' => $this->input('treatment_en'),
            'reporter_user_id' => $user['user_id']
        ];
        
        $result = $healthReport->create($data);
        
        if ($result) {
            return $this->success('تم إنشاء التقرير الطبي بنجاح');
        }
        
        return $this->error('فشل في إنشاء التقرير الطبي');
    }

    public function profile()
    {
        $this->requireAuth();
        $user = $this->auth->user();
        
        // Get medical team info
        $userId = $this->auth->id();
        $team = Database::fetchOne("
            SELECT mt.* 
            FROM medical_teams mt 
            JOIN medical_team_members mtm ON mt.team_id = mtm.team_id 
            WHERE mtm.user_id = :user_id
        ", [':user_id' => $userId]);
        
        // Get medical professional info
        try {
            $medical_info = Database::fetchOne(
                "SELECT * FROM medical_professionals WHERE user_id = :user_id",
                [':user_id' => $userId]
            );
        } catch (\Exception $e) {
            $medical_info = [
                'specialization' => '',
                'license_number' => ''
            ];
        }
        
        $user = $this->auth->user();
        if (!$user) {
            // Redirect to login if user is not found
            header('Location: /sihat-al-haj/login');
            exit;
        }
        
        // Debug output
        error_log('User data: ' . print_r($user, true));
        error_log('Team data: ' . print_r($team, true));
        error_log('Medical info: ' . print_r($medical_info, true));
        
        $data = [
            'pageTitle' => 'الملف الشخصي',
            'user' => $user,
            'team' => $team ?: null,
            'medical_info' => $medical_info,
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/profile', $data);
    }

    public function updateProfile()
    {
        $this->requireAuth();
        $userId = $this->auth->id();
        $input = $this->input();
        
        try {
            Database::beginTransaction();
            
            // Update user information
            Database::execute("
                UPDATE users 
                SET first_name_ar = :first_name_ar,
                    last_name_ar = :last_name_ar,
                    first_name_en = :first_name_en,
                    last_name_en = :last_name_en,
                    email = :email,
                    phone_number = :phone_number
                WHERE user_id = :user_id
            ", [
                ':first_name_ar' => $input['first_name_ar'],
                ':last_name_ar' => $input['last_name_ar'],
                ':first_name_en' => $input['first_name_en'],
                ':last_name_en' => $input['last_name_en'],
                ':email' => $input['email'],
                ':phone_number' => $input['phone_number'],
                ':user_id' => $userId
            ]);
            
            // Update medical professional info
            Database::execute("
                INSERT INTO medical_professionals (user_id, specialization, license_number)
                VALUES (:user_id, :specialization, :license_number)
                ON DUPLICATE KEY UPDATE
                specialization = VALUES(specialization),
                license_number = VALUES(license_number)
            ", [
                ':user_id' => $userId,
                ':specialization' => $input['specialization'],
                ':license_number' => $input['license_number']
            ]);
            
            // Handle password change if provided
            if (!empty($input['current_password']) && !empty($input['new_password'])) {
                $currentUser = Database::fetchOne(
                    "SELECT password_hash FROM users WHERE user_id = :user_id",
                    [':user_id' => $userId]
                );
                
                if (Auth::verifyPassword($input['current_password'], $currentUser['password_hash'])) {
                    if ($input['new_password'] === $input['confirm_password']) {
                        Database::execute(
                            "UPDATE users SET password_hash = :password WHERE user_id = :user_id",
                            [
                                ':password' => Auth::hashPassword($input['new_password']),
                                ':user_id' => $userId
                            ]
                        );
                    } else {
                        throw new \Exception('كلمة المرور الجديدة وتأكيدها غير متطابقين');
                    }
                } else {
                    throw new \Exception('كلمة المرور الحالية غير صحيحة');
                }
            }
            
            Database::commit();
            return $this->json(['success' => true]);
            
        } catch (\Exception $e) {
            Database::rollback();
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateProfileImage()
    {
        $this->requireAuth();
        $userId = $this->auth->id();
        
        try {
            if (!isset($_FILES['profile_image'])) {
                throw new \Exception('لم يتم اختيار صورة');
            }
            
            $file = $_FILES['profile_image'];
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            
            if (!in_array($file['type'], $allowedTypes)) {
                throw new \Exception('نوع الملف غير مسموح به');
            }
            
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                throw new \Exception('حجم الملف كبير جداً');
            }
            
            $fileName = uniqid() . '_' . $file['name'];
            $uploadPath = __DIR__ . '/../../public/uploads/profile-images/';
            
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $uploadPath . $fileName)) {
                $imageUrl = '/sihat-al-haj/public/uploads/profile-images/' . $fileName;
                
                Database::execute(
                    "UPDATE users SET profile_image_url = :image_url WHERE user_id = :user_id",
                    [
                        ':image_url' => $imageUrl,
                        ':user_id' => $userId
                    ]
                );
                
                return $this->json([
                    'success' => true,
                    'imageUrl' => $imageUrl
                ]);
            } else {
                throw new \Exception('فشل في تحميل الصورة');
            }
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
    
    /**
     * Patient details page
     */
    public function patientDetails($id)
    {
        $this->requireAuth();
        
        $pilgrimModel = new Pilgrim();
        $patient = $pilgrimModel->find($id);
        
        if (!$patient) {
            $_SESSION['flash']['error'] = 'الحاج غير موجود';
            header('Location: /sihat-al-haj/medical-team/patients');
            exit;
        }
        
        // Get chronic diseases
        $chronic_diseases = $pilgrimModel->getChronicDiseases($id);
        
        // Get all health records
        $health_records = $pilgrimModel->getHealthRecords($id);
        
        // Get latest health record
        $latest_health_record = !empty($health_records) ? $health_records[0] : null;
        
        // Count health records
        $health_records_count = count($health_records);
        
        $data = [
            'pageTitle' => 'تفاصيل الحاج',
            'patient' => $patient,
            'chronic_diseases' => $chronic_diseases,
            'health_records' => $health_records,
            'latest_health_record' => $latest_health_record,
            'health_records_count' => $health_records_count,
            'layout' => 'medical_dashboard'
        ];
        
        return $this->render('pages/medical_team/patient-details', $data);
    }
    
    /**
     * Add health record
     */
    public function addHealthRecord()
    {
        $this->requireAuth();
        
        if (!$this->isPost()) {
            $_SESSION['flash']['error'] = 'طريقة طلب غير صالحة';
            header('Location: /sihat-al-haj/medical-team/patients');
            exit;
        }
        
        $pilgrimId = $this->input('pilgrim_id');
        $bloodPressure = $this->input('blood_pressure');
        $heartRate = $this->input('heart_rate');
        $temperature = $this->input('temperature');
        $oxygenSaturation = $this->input('oxygen_saturation');
        $bloodSugar = $this->input('blood_sugar');
        $weight = $this->input('weight');
        $notes = $this->input('notes');
        
        if (!$pilgrimId) {
            $_SESSION['flash']['error'] = 'رقم الحاج مطلوب';
            header('Location: /sihat-al-haj/medical-team/patients');
            exit;
        }
        
        $pilgrimModel = new Pilgrim();
        
        $healthData = [
            'pilgrim_id' => $pilgrimId,
            'blood_pressure' => $bloodPressure,
            'heart_rate' => $heartRate,
            'temperature' => $temperature,
            'oxygen_saturation' => $oxygenSaturation,
            'blood_sugar' => $bloodSugar,
            'weight' => $weight,
            'notes' => $notes,
            'recorded_by' => $_SESSION['user']['user_id'] ?? null,
            'recorded_at' => date('Y-m-d H:i:s')
        ];
        
        $success = $pilgrimModel->addHealthRecord($healthData);
        
        if ($success) {
            $_SESSION['flash']['success'] = 'تم إضافة السجل الصحي بنجاح';
        } else {
            $_SESSION['flash']['error'] = 'حدث خطأ أثناء إضافة السجل الصحي';
        }
        
        header('Location: /sihat-al-haj/medical-team/patient-details/' . $pilgrimId);
        exit;
    }
}
