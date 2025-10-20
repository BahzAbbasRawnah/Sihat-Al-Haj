<?php

namespace App\Core;

/**
 * Flash Message System
 * 
 * Handles temporary messages between requests
 */
class FlashMessage
{
    private static $sessionKey = 'flash_messages';
    
    /**
     * Add flash message
     */
    public static function add($type, $message, $title = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION[self::$sessionKey])) {
            $_SESSION[self::$sessionKey] = [];
        }
        
        $_SESSION[self::$sessionKey][] = [
            'type' => $type,
            'message' => $message,
            'title' => $title,
            'timestamp' => time()
        ];
    }
    
    /**
     * Add success message
     */
    public static function success($message, $title = null)
    {
        self::add('success', $message, $title);
    }
    
    /**
     * Add error message
     */
    public static function error($message, $title = null)
    {
        self::add('error', $message, $title);
    }
    
    /**
     * Add warning message
     */
    public static function warning($message, $title = null)
    {
        self::add('warning', $message, $title);
    }
    
    /**
     * Add info message
     */
    public static function info($message, $title = null)
    {
        self::add('info', $message, $title);
    }
    
    /**
     * Get all flash messages
     */
    public static function getAll()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $messages = $_SESSION[self::$sessionKey] ?? [];
        
        // Clear messages after retrieving
        unset($_SESSION[self::$sessionKey]);
        
        return $messages;
    }
    
    /**
     * Get messages by type
     */
    public static function get($type)
    {
        $allMessages = self::getAll();
        return array_filter($allMessages, function($message) use ($type) {
            return $message['type'] === $type;
        });
    }
    
    /**
     * Check if there are any messages
     */
    public static function has($type = null)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $messages = $_SESSION[self::$sessionKey] ?? [];
        
        if ($type === null) {
            return !empty($messages);
        }
        
        return !empty(array_filter($messages, function($message) use ($type) {
            return $message['type'] === $type;
        }));
    }
    
    /**
     * Clear all messages
     */
    public static function clear()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        unset($_SESSION[self::$sessionKey]);
    }
    
    /**
     * Render flash messages as HTML
     */
    public static function render()
    {
        $messages = self::getAll();
        
        if (empty($messages)) {
            return '';
        }
        
        $html = '<div id="flash-messages" class="fixed top-4 right-4 z-50 space-y-2">';
        
        foreach ($messages as $message) {
            $html .= self::renderMessage($message);
        }
        
        $html .= '</div>';
        $html .= self::getJavaScript();
        
        return $html;
    }
    
    /**
     * Render individual message
     */
    private static function renderMessage($message)
    {
        $typeClasses = [
            'success' => 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-300',
            'error' => 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-300',
            'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800 dark:bg-yellow-900/20 dark:border-yellow-800 dark:text-yellow-300',
            'info' => 'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/20 dark:border-blue-800 dark:text-blue-300'
        ];
        
        $typeIcons = [
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-circle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle'
        ];
        
        $classes = $typeClasses[$message['type']] ?? $typeClasses['info'];
        $icon = $typeIcons[$message['type']] ?? $typeIcons['info'];
        
        $html = '<div class="flash-message max-w-sm w-full border rounded-lg p-4 shadow-lg ' . $classes . '" data-type="' . $message['type'] . '">';
        $html .= '<div class="flex items-start">';
        $html .= '<div class="flex-shrink-0">';
        $html .= '<i class="' . $icon . ' text-lg"></i>';
        $html .= '</div>';
        $html .= '<div class="ml-3 flex-1">';
        
        if ($message['title']) {
            $html .= '<h4 class="font-semibold mb-1">' . htmlspecialchars($message['title']) . '</h4>';
        }
        
        $html .= '<p class="text-sm">' . htmlspecialchars($message['message']) . '</p>';
        $html .= '</div>';
        $html .= '<div class="ml-4 flex-shrink-0">';
        $html .= '<button class="flash-close text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">';
        $html .= '<i class="fas fa-times"></i>';
        $html .= '</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get JavaScript for flash messages
     */
    private static function getJavaScript()
    {
        return '
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Auto-hide flash messages after 5 seconds
            setTimeout(function() {
                const messages = document.querySelectorAll(".flash-message");
                messages.forEach(function(message) {
                    message.style.opacity = "0";
                    message.style.transform = "translateX(100%)";
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                });
            }, 5000);
            
            // Handle close button clicks
            document.querySelectorAll(".flash-close").forEach(function(button) {
                button.addEventListener("click", function() {
                    const message = this.closest(".flash-message");
                    message.style.opacity = "0";
                    message.style.transform = "translateX(100%)";
                    setTimeout(function() {
                        message.remove();
                    }, 300);
                });
            });
        });
        </script>';
    }
    
    /**
     * Render flash messages as JSON for AJAX responses
     */
    public static function toJson()
    {
        $messages = self::getAll();
        return json_encode($messages);
    }
}

