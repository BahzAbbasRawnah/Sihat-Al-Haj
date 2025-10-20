<?php

namespace App\Core;

/**
 * Session Class
 * 
 * Handles session management and flash messages
 */
class Session
{
    /**
     * Start session if not already started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    /**
     * Set session value
     */
    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session value
     */
    public static function get($key, $default = null)
    {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session key exists
     */
    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session key
     */
    public static function remove($key)
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Clear all session data
     */
    public static function clear()
    {
        self::start();
        $_SESSION = [];
    }
    
    /**
     * Destroy session
     */
    public static function destroy()
    {
        self::start();
        session_destroy();
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate($deleteOld = true)
    {
        self::start();
        session_regenerate_id($deleteOld);
    }
    
    /**
     * Set flash message
     */
    public static function flash($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Get flash message (removes it after retrieval)
     */
    public static function getFlash($key, $default = null)
    {
        self::start();
        
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        
        return $default;
    }
    
    /**
     * Check if flash message exists
     */
    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }
    
    /**
     * Set success flash message
     */
    public static function success($message)
    {
        self::flash('success', $message);
    }
    
    /**
     * Set error flash message
     */
    public static function error($message)
    {
        self::flash('error', $message);
    }
    
    /**
     * Set warning flash message
     */
    public static function warning($message)
    {
        self::flash('warning', $message);
    }
    
    /**
     * Set info flash message
     */
    public static function info($message)
    {
        self::flash('info', $message);
    }
    
    /**
     * Get all flash messages
     */
    public static function getAllFlash()
    {
        self::start();
        
        $messages = $_SESSION['flash'] ?? [];
        $_SESSION['flash'] = [];
        
        return $messages;
    }
    
    /**
     * Set old input for form repopulation
     */
    public static function setOldInput($input)
    {
        self::set('old_input', $input);
    }
    
    /**
     * Get old input value
     */
    public static function getOldInput($key = null, $default = null)
    {
        $oldInput = self::get('old_input', []);
        
        if ($key === null) {
            return $oldInput;
        }
        
        return $oldInput[$key] ?? $default;
    }
    
    /**
     * Clear old input
     */
    public static function clearOldInput()
    {
        self::remove('old_input');
    }
    
    /**
     * Set validation errors
     */
    public static function setErrors($errors)
    {
        self::set('validation_errors', $errors);
    }
    
    /**
     * Get validation errors
     */
    public static function getErrors()
    {
        $errors = self::get('validation_errors', []);
        self::remove('validation_errors');
        return $errors;
    }
    
    /**
     * Get specific validation error
     */
    public static function getError($key, $default = null)
    {
        $errors = self::get('validation_errors', []);
        return $errors[$key] ?? $default;
    }
    
    /**
     * Check if validation error exists
     */
    public static function hasError($key)
    {
        $errors = self::get('validation_errors', []);
        return isset($errors[$key]);
    }
    
    /**
     * Get CSRF token
     */
    public static function getCsrfToken()
    {
        self::start();
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken($token)
    {
        $sessionToken = self::getCsrfToken();
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Generate new CSRF token
     */
    public static function regenerateCsrfToken()
    {
        self::start();
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
}

