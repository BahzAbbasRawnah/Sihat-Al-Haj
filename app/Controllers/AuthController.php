<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

/**
 * Authentication Controller
 * 
 * Handles user authentication (login, register, logout)
 */
class AuthController extends Controller
{
    /**
     * Show login form or handle login submission
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            return $this->redirectToDashboard();
        }
        
        // Handle POST request (login submission)
        if ($this->isPost()) {
            return $this->handleLogin();
        }
        
        // Show login form
        $data = [
            'title' => $this->language->get('auth.login'),
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'layout' => 'auth'
        ];
        
        // Clear session errors and old input
        unset($_SESSION['errors']);
        unset($_SESSION['old']);
        
        return $this->render('pages/auth/login', $data);
    }
    
    /**
     * Handle login submission
     */
    private function handleLogin()
    {
        $identifier = $this->input('identifier');
        $password = $this->input('password');
        $remember = $this->input('remember');
        
        // Validate input
        $errors = [];
        
        if (empty($identifier)) {
            $errors['identifier'] = $this->language->get('auth.identifier_required') ?: 'المعرف مطلوب';
        }
        
        if (empty($password)) {
            $errors['password'] = $this->language->get('auth.password_required');
        }
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['identifier' => $identifier];
            return $this->redirect('/login');
        }
        
        // Attempt authentication
        $credentials = [
            'identifier' => $identifier,
            'password' => $password
        ];
        
        if ($this->auth->attempt($credentials)) {
            // Set remember me cookie if requested
            if ($remember) {
                $this->setRememberMeCookie();
            }
            
            // Set success message
            $_SESSION['flash']['success'] = $this->language->get('auth.login_success');
            
            // Redirect to intended URL or dashboard
            $intendedUrl = $_SESSION['intended_url'] ?? null;
            unset($_SESSION['intended_url']);
            
            if ($intendedUrl) {
                return $this->redirect($intendedUrl);
            }
            
            return $this->redirectToDashboard();
        } else {
            // Login failed
            $_SESSION['errors'] = [
                'login' => $this->language->get('auth.invalid_credentials')
            ];
            $_SESSION['old'] = ['identifier' => $identifier];
            return $this->redirect('/login');
        }
    }
    
    /**
     * Show registration form or handle registration submission
     */
    public function register()
    {
        // Redirect if already logged in
        if ($this->isAuthenticated()) {
            return $this->redirectToDashboard();
        }
        
        // Handle POST request (registration submission)
        if ($this->isPost()) {
            return $this->handleRegister();
        }
        
        // Show registration form
        $data = [
            'title' => $this->language->get('auth.register'),
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
            'layout' => 'auth'
        ];
        
        // Clear session errors and old input
        unset($_SESSION['errors']);
        unset($_SESSION['old']);
        
        return $this->render('pages/auth/register', $data);
    }
    
    /**
     * Handle registration submission
     */
    private function handleRegister()
    {
        $data = [
            'username' => $this->input('username'),
            'email' => $this->input('email'),
            'password' => $this->input('password'),
            'password_confirmation' => $this->input('password_confirmation'),
            'first_name_ar' => $this->input('first_name_ar'),
            'first_name_en' => $this->input('first_name_en'),
            'last_name_ar' => $this->input('last_name_ar'),
            'last_name_en' => $this->input('last_name_en'),
            'phone_number' => $this->input('phone_number'),
            'user_type' => $this->input('user_type') ?: 'pilgrim', // Default to pilgrim
            'id_number' => $this->input('id_number'),
            'date_of_birth' => $this->input('date_of_birth'),
            'gender' => $this->input('gender'),
            'nationality_id' => $this->input('nationality_id')
        ];
        
        // Validate input
        $errors = $this->validateRegistration($data);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            return $this->redirect('/register');
        }
        
        // Check if username already exists
        $userModel = new User();
        $existingUser = $userModel->findByUsernameOrEmail($data['username']);
        
        if ($existingUser) {
            $_SESSION['errors'] = [
                'username' => $this->language->get('auth.username_exists')
            ];
            $_SESSION['old'] = $data;
            return $this->redirect('/register');
        }
        
        // Check if email already exists
        if (!empty($data['email'])) {
            $existingEmail = $userModel->findByUsernameOrEmail($data['email']);
            if ($existingEmail) {
                $_SESSION['errors'] = [
                    'email' => $this->language->get('auth.email_exists')
                ];
                $_SESSION['old'] = $data;
                return $this->redirect('/register');
            }
        }
        
        // Create user
        $userData = [
            'username' => $data['username'],
            'password_hash' => password_hash($data['password'], PASSWORD_DEFAULT),
            'email' => $data['email'],
            'first_name_ar' => $data['first_name_ar'],
            'first_name_en' => $data['first_name_en'],
            'last_name_ar' => $data['last_name_ar'],
            'last_name_en' => $data['last_name_en'],
            'phone_number' => $data['phone_number'],
            'user_type' => $data['user_type'],
            'id_number' => $data['id_number'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'nationality_id' => $data['nationality_id'],
            'status' => 'active'
        ];
        
        $userId = $userModel->create($userData);
        
        if ($userId) {
            // Auto-login the user
            $this->auth->loginById($userId);
            
            // Set success message
            $_SESSION['flash']['success'] = $this->language->get('auth.register_success');
            
            // Redirect to dashboard based on user type
            return $this->redirectToDashboard();
        } else {
            $_SESSION['errors'] = [
                'general' => $this->language->get('auth.register_failed')
            ];
            $_SESSION['old'] = $data;
            return $this->redirect('/register');
        }
    }
    
    /**
     * Validate registration data
     */
    private function validateRegistration($data)
    {
        $errors = [];
        
        // Username validation
        if (empty($data['username'])) {
            $errors['username'] = $this->language->get('auth.username_required');
        } elseif (strlen($data['username']) < 3) {
            $errors['username'] = $this->language->get('auth.username_min_length');
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors['username'] = $this->language->get('auth.username_invalid');
        }
        
        // Email validation (optional but must be valid if provided)
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = $this->language->get('auth.email_invalid');
        }
        
        // Password validation
        if (empty($data['password'])) {
            $errors['password'] = $this->language->get('auth.password_required');
        } elseif (strlen($data['password']) < 6) {
            $errors['password'] = $this->language->get('auth.password_min_length');
        }
        
        // Password confirmation
        if ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = $this->language->get('auth.password_mismatch');
        }
        
        // Name validation
        if (empty($data['first_name_ar'])) {
            $errors['first_name_ar'] = $this->language->get('auth.first_name_ar_required');
        }
        
        if (empty($data['first_name_en'])) {
            $errors['first_name_en'] = $this->language->get('auth.first_name_en_required');
        }
        
        if (empty($data['last_name_ar'])) {
            $errors['last_name_ar'] = $this->language->get('auth.last_name_ar_required');
        }
        
        if (empty($data['last_name_en'])) {
            $errors['last_name_en'] = $this->language->get('auth.last_name_en_required');
        }
        
        // User type validation - default to pilgrim if not provided
        $validTypes = ['pilgrim', 'medical_personnel', 'administrator'];
        if (empty($data['user_type'])) {
            $data['user_type'] = 'pilgrim'; // Set default
        } elseif (!in_array($data['user_type'], $validTypes)) {
            $errors['user_type'] = $this->language->get('auth.user_type_invalid');
        }
        
        // Phone number validation
        if (!empty($data['phone_number']) && !preg_match('/^[+]?[0-9\s\-\(\)]+$/', $data['phone_number'])) {
            $errors['phone_number'] = $this->language->get('auth.phone_invalid');
        }
        
        return $errors;
    }
    
    /**
     * Handle logout
     */
    public function logout()
    {
        $this->auth->logout();
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        $_SESSION['flash']['success'] = $this->language->get('auth.logout_success');
        
        return $this->redirect('/login');
    }
    
    /**
     * Redirect to appropriate dashboard based on user type
     */
    private function redirectToDashboard()
    {
        $user = $this->auth->user();
        
        if (!$user) {
            return $this->redirect('/login');
        }
        
        switch ($user['user_type']) {
            case 'administrator':
                return $this->redirect('/admin/dashboard');
            
            case 'medical_personnel':
                return $this->redirect('/medical-team/medical-dashboard');
            
            case 'pilgrim':
                return $this->redirect('/pilgrim/dashboard');
            
            case 'guide':
                return $this->redirect('/guide/dashboard');
            
            default:
                return $this->redirect('/dashboard');
        }
    }
    
    /**
     * Set remember me cookie
     */
    private function setRememberMeCookie()
    {
        $token = bin2hex(random_bytes(32));
        $userId = $this->auth->id();
        
        // Store token in database (you may want to create a remember_tokens table)
        // For now, we'll just set a simple cookie
        setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
        
        // Store token hash in session for validation
        $_SESSION['remember_token'] = password_hash($token, PASSWORD_DEFAULT);
    }
    
    /**
     * Show forgot password form
     */
    public function showForgotPassword()
    {
        $data = [
            'title' => $this->language->get('auth.forgot_password'),
            'layout' => 'auth'
        ];
        
        return $this->render('pages/auth/forgot-password', $data);
    }
    
    /**
     * Handle forgot password submission
     */
    public function forgotPassword()
    {
        // TODO: Implement password reset functionality
        $_SESSION['flash']['info'] = $this->language->get('auth.reset_link_sent');
        return $this->redirect('/login');
    }
    
    /**
     * Show reset password form
     */
    public function showResetPassword($token)
    {
        $data = [
            'title' => $this->language->get('auth.reset_password'),
            'token' => $token,
            'layout' => 'auth'
        ];
        
        return $this->render('pages/auth/reset-password', $data);
    }
    
    /**
     * Handle reset password submission
     */
    public function resetPassword()
    {
        // TODO: Implement password reset functionality
        $_SESSION['flash']['success'] = $this->language->get('auth.password_reset_success');
        return $this->redirect('/login');
    }
}
