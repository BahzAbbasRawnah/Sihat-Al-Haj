<?php

namespace App\Core;

/**
 * Base Controller Class
 * 
 * All controllers should extend this class to inherit common functionality
 */
abstract class Controller
{
    protected $view;
    protected $language;
    protected $auth;
    protected $database;
    
    public function __construct()
    {
        $this->view = new View();
        $this->language = new Language();
        $this->auth = new Auth();
        $this->database = Database::getInstance();
        
        // Set global view variables
        $this->setGlobalViewData();
    }
    
    /**
     * Set global data available to all views
     */
    protected function setGlobalViewData()
    {
        $this->view->setGlobal('currentLang', $this->language->getCurrentLanguage());
        $this->view->setGlobal('isRTL', $this->language->getCurrentLanguage() === 'ar');
        $this->view->setGlobal('user', $this->auth->user());
        $this->view->setGlobal('isLoggedIn', $this->auth->check());
        $this->view->setGlobal('lang', $this->language);
        $this->view->setGlobal('basePath', '/sihat-al-haj');
    }
    
    /**
     * Render a view
     */
    protected function render($view, $data = [])
    {
        return $this->view->render($view, $data);
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Return success JSON response
     */
    protected function success($message = null, $data = null)
    {
        $response = ['success' => true];
        
        if ($message) {
            $response['message'] = $message;
        }
        
        if ($data) {
            $response['data'] = $data;
        }
        
        return $this->json($response);
    }
    
    /**
     * Return error JSON response
     */
    protected function error($message, $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message
        ];
        
        if ($errors) {
            $response['errors'] = $errors;
        }
        
        return $this->json($response, $statusCode);
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url, $statusCode = 302)
    {
        error_log("Controller::redirect called with URL: " . $url);
        
        // Add base path if not already present
        if (strpos($url, '/sihat-al-haj') !== 0 && $url[0] === '/') {
            $url = '/sihat-al-haj' . $url;
        }
        
        error_log("Final redirect URL: " . $url);
        error_log("Calling Router::redirect...");
        
        Router::redirect($url, $statusCode);
        
        error_log("After Router::redirect - THIS SHOULD NEVER APPEAR");
    }
    
    /**
     * Redirect back to previous page
     */
    protected function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * Get request input
     */
    protected function input($key = null, $default = null)
    {
        static $jsonBody = null;
        if ($jsonBody === null && $this->isPost() && strpos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') !== false) {
            $raw = file_get_contents('php://input');
            $jsonBody = json_decode($raw, true) ?: [];
        } elseif ($jsonBody === null) {
            $jsonBody = [];
        }
        if ($key === null) {
            return array_merge($_GET, $_POST, $jsonBody);
        }
        return $_POST[$key] ?? $_GET[$key] ?? $jsonBody[$key] ?? $default;
    }
    
    /**
     * Validate request input
     */
    protected function validate($rules)
    {
        $validator = new Validator($this->input(), $rules);
        
        if ($validator->fails()) {
            if ($this->isAjaxRequest()) {
                return $this->error(
                    $this->language->get('validation.failed'),
                    422,
                    $validator->errors()
                );
            } else {
                $_SESSION['validation_errors'] = $validator->errors();
                $_SESSION['old_input'] = $this->input();
                $this->back();
            }
        }
        
        return $validator->validated();
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Get current user
     */
    protected function user()
    {
        return $this->auth->user();
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated()
    {
        return $this->auth->check();
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth()
    {
        if (!$this->isAuthenticated()) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('auth.required'), 401);
            } else {
                $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
                $this->redirect('/login');
                exit; // Ensure execution stops after redirect
            }
        }
    }
    
    /**
     * Check user permissions
     */
    protected function authorize($permission)
    {
        if (!$this->auth->can($permission)) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('auth.unauthorized'), 403);
            } else {
                $this->redirect('/unauthorized');
            }
        }
    }
    
    /**
     * Require specific user role
     */
    protected function requireRole($role)
    {
        $user = $this->user();
        if (!$user || $user['user_type'] !== $role) {
            if ($this->isAjaxRequest()) {
                return $this->error($this->language->get('auth.unauthorized'), 403);
            } else {
                $this->redirect('/unauthorized');
                exit; // Ensure execution stops after redirect
            }
        }
    }
    
    /**
     * Get current authenticated user
     */
    protected function getUser()
    {
        return $this->user();
    }
    
    /**
     * Check if request is POST
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is GET
     */
    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Return 404 not found response
     */
    protected function notFound($message = null)
    {
        $message = $message ?? $this->language->get('Page not found');
        
        if ($this->isAjaxRequest()) {
            return $this->error($message, 404);
        } else {
            http_response_code(404);
            return $this->render('errors/404', ['message' => $message]);
        }
    }
    
    /**
     * Generate URL with base path
     */
    protected function url($path)
    {
        $basePath = '/sihat-al-haj';
        if (strpos($path, $basePath) === 0) {
            return $path;
        }
        return $basePath . ($path[0] === '/' ? $path : '/' . $path);
    }
}

