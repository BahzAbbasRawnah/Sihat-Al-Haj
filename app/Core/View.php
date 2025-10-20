<?php

namespace App\Core;

/**
 * View Class
 * 
 * Handles view rendering and template management
 */
class View
{
    private $viewsPath;
    private $globalData = [];
    private $sections = [];
    private $currentSection = null;
    
    public function __construct()
    {
        $this->viewsPath = VIEWS_PATH;
    }
    
    /**
     * Render a view
     */
    public function render($view, $data = [])
    {
        $viewFile = $this->getViewFile($view);
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: {$view} (looking for: {$viewFile})");
        }
        
        // Merge global data with view data
        $data = array_merge($this->globalData, $data);
        
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        include $viewFile;
        
        // Get the content
        $content = ob_get_clean();
        
        // If we have a layout, wrap the content
        if (isset($data['layout'])) {
            $layoutFile = $this->getViewFile('layouts/' . $data['layout']);
            if (file_exists($layoutFile)) {
                $data['content'] = $content;
                extract($data);
                ob_start();
                include $layoutFile;
                $content = ob_get_clean();
            }
        }
        
        echo $content;
        return $content;
    }
    
    /**
     * Set global data available to all views
     */
    public function setGlobal($key, $value)
    {
        $this->globalData[$key] = $value;
    }
    
    /**
     * Get global data
     */
    public function getGlobal($key = null)
    {
        if ($key === null) {
            return $this->globalData;
        }
        
        return $this->globalData[$key] ?? null;
    }
    
    /**
     * Include a partial view
     */
    public function include($view, $data = [])
    {
        echo $this->render($view, $data);
    }
    
    /**
     * Start a section
     */
    public function startSection($name)
    {
        $this->currentSection = $name;
        ob_start();
    }
    
    /**
     * End a section
     */
    public function endSection()
    {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = null;
        }
    }
    
    /**
     * Yield a section
     */
    public function yieldSection($name, $default = '')
    {
        return $this->sections[$name] ?? $default;
    }
    
    /**
     * Extend a layout
     */
    public function extend($layout, $data = [])
    {
        return $this->render($layout, $data);
    }
    
    /**
     * Get view file path
     */
    private function getViewFile($view)
    {
        // Convert dot notation to directory separator
        $view = str_replace('.', '/', $view);
        
        return $this->viewsPath . '/' . $view . '.php';
    }
    
    /**
     * Escape HTML output
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Generate asset URL
     */
    public function asset($path)
    {
        return '/public/assets/' . ltrim($path, '/');
    }
    
    /**
     * Generate URL
     */
    public function url($path = '')
    {
        $basePath = '/sihat-al-haj';
        if (strpos($path, $basePath) === 0) {
            return $path;
        }
        return $basePath . ($path[0] === '/' ? $path : '/' . $path);
    }
    
    /**
     * Get base URL
     */
    private function getBaseUrl()
    {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        
        return $protocol . '://' . $host;
    }
    
    /**
     * Check if current page matches URL
     */
    public function isActive($url)
    {
        $currentUrl = $_SERVER['REQUEST_URI'];
        return $currentUrl === $url;
    }
    
    /**
     * Add CSS class if condition is true
     */
    public function addClass($condition, $class)
    {
        return $condition ? $class : '';
    }
    
    /**
     * Format date for display
     */
    public function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }
        
        return $date->format($format);
    }
    
    /**
     * Truncate text
     */
    public function truncate($text, $length = 100, $suffix = '...')
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . $suffix;
    }
    
    /**
     * Get old input value (for form repopulation)
     */
    public function old($key, $default = '')
    {
        return $_SESSION['old_input'][$key] ?? $default;
    }
    
    /**
     * Get validation error
     */
    public function error($key)
    {
        return $_SESSION['validation_errors'][$key] ?? null;
    }
    
    /**
     * Check if validation error exists
     */
    public function hasError($key)
    {
        return isset($_SESSION['validation_errors'][$key]);
    }
    
    /**
     * Get flash message
     */
    public function flash($key)
    {
        $message = $_SESSION['flash'][$key] ?? null;
        
        if ($message) {
            unset($_SESSION['flash'][$key]);
        }
        
        return $message;
    }
    
    /**
     * Set flash message
     */
    public function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }
}

