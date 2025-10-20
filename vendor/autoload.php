<?php

/**
 * Simple Autoloader
 * 
 * This is a basic autoloader for the application classes
 */

spl_autoload_register(function ($className) {
    // Convert namespace to directory path
    $className = str_replace('\\', '/', $className);
    
    // Remove App namespace prefix
    if (strpos($className, 'App/') === 0) {
        $className = substr($className, 4);
    }
    
    // Build file path
    $filePath = APP_PATH . '/' . $className . '.php';
    
    // Include file if it exists
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

