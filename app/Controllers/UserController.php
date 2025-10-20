<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

/**
 * User Controller
 * Handles user profile and settings
 */
class UserController extends Controller
{
    /**
     * Show user profile
     */
    public function profile()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sihat-al-haj/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);

        if (!$user) {
            $_SESSION['flash']['error'] = 'المستخدم غير موجود';
            header('Location: /sihat-al-haj/dashboard');
            exit;
        }

        $data = [
            'title' => 'الملف الشخصي',
            'user' => $user
        ];

        return $this->render('pages/profile', $data);
    }

    /**
     * Update user profile
     */
    public function updateProfile()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: /sihat-al-haj/login');
            exit;
        }

        if ($this->isPost()) {
            $userModel = new User();
            $userId = $_SESSION['user_id'];
            
            $data = [
                'first_name_ar' => $this->input('first_name_ar'),
                'last_name_ar' => $this->input('last_name_ar'),
                'first_name_en' => $this->input('first_name_en'),
                'last_name_en' => $this->input('last_name_en'),
                'phone' => $this->input('phone'),
                'address' => $this->input('address')
            ];

            // Update password if provided
            $newPassword = $this->input('new_password');
            if (!empty($newPassword)) {
                $currentPassword = $this->input('current_password');
                $user = $userModel->find($userId);
                
                if (!password_verify($currentPassword, $user['password'])) {
                    $_SESSION['flash']['error'] = 'كلمة المرور الحالية غير صحيحة';
                    header('Location: /sihat-al-haj/profile');
                    exit;
                }
                
                $data['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
            }

            $success = $userModel->update($userId, $data);

            if ($success) {
                $_SESSION['flash']['success'] = 'تم تحديث الملف الشخصي بنجاح';
            } else {
                $_SESSION['flash']['error'] = 'حدث خطأ أثناء تحديث الملف الشخصي';
            }

            header('Location: /sihat-al-haj/profile');
            exit;
        }

        header('Location: /sihat-al-haj/profile');
        exit;
    }
}
