<?php

namespace App\Core;

/**
 * Main Application Class
 * 
 * This class handles the application lifecycle and coordinates
 * between different components of the framework.
 */
class Application
{
    private $router;
    private $database;
    private $language;
    private $auth;
    
    public function __construct()
    {
        $this->initializeComponents();
    }
    
    /**
     * Initialize core application components
     */
    private function initializeComponents()
    {
        // Initialize database connection
        $this->database = Database::getInstance();
        
        // Initialize language system
        $this->language = new Language();
        
        // Initialize authentication
        $this->auth = new Auth();
        
        // Initialize router
        $this->router = new Router();
    }
    
    /**
     * Run the application
     */
    public function run()
    {
        try {
            // Set language based on session or default
            $this->setLanguage();
            
            // Load routes
            global $router;
            if ($router) {
                $this->router = $router;
            }
            
            // Handle the request
            $this->router->handleRequest();
            
        } catch (\Exception $e) {
            $this->handleError($e);
        }
    }
    
    /**
     * Set application language
     */
    private function setLanguage()
    {
        $lang = $_SESSION['language'] ?? $_GET['lang'] ?? 'ar';
        
        if (in_array($lang, ['ar', 'en'])) {
            $_SESSION['language'] = $lang;
            $this->language->setLanguage($lang);
        } else {
            $_SESSION['language'] = 'ar';
            $this->language->setLanguage('ar');
        }
    }
    
    /**
     * Handle application errors
     */
    private function handleError(\Exception $e)
    {
        // Log the error
        error_log($e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());
        
        // Show error page
        if (APP_DEBUG) {
            echo '<h1>Application Error</h1>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<p>File: ' . $e->getFile() . '</p>';
            echo '<p>Line: ' . $e->getLine() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            // Redirect to error page
            header('Location: /error/500');
            exit;
        }
    }
    
    /**
     * Get database instance
     */
    public function getDatabase()
    {
        return $this->database;
    }
    
    /**
     * Get language instance
     */
    public function getLanguage()
    {
        return $this->language;
    }
    
    /**
     * Get auth instance
     */
    public function getAuth()
    {
        return $this->auth;
    }
}

