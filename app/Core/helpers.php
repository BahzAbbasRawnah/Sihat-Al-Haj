<?php

/**
 * Helper Functions
 */

if (!function_exists('__')) {
    /**
     * Get translation for given key using Language class
     */
    function __($key, $params = []) {
        static $language = null;
        
        // Initialize Language class once
        if ($language === null) {
            $language = new \App\Core\Language();
        }
        
        return $language->get($key, $params);
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL
     */
    function url($path = '') {
        $baseUrl = rtrim(APP_URL, '/');
        $path = ltrim($path, '/');
        return $baseUrl . ($path ? '/' . $path : '');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     */
    function asset($path) {
        return url('public/' . ltrim($path, '/'));
    }
}

if (!function_exists('getCurrentLanguage')) {
    /**
     * Get current language
     */
    function getCurrentLanguage() {
        return $_SESSION['language'] ?? 'ar';
    }
}

if (!function_exists('getLocalizedField')) {
    /**
     * Get localized field from array
     */
    function getLocalizedField($data, $fieldName) {
        if (!is_array($data)) {
            return '';
        }
        
        $lang = getCurrentLanguage();
        $field = $fieldName . '_' . $lang;
        
        // Try current language
        if (isset($data[$field]) && !empty($data[$field])) {
            return $data[$field];
        }
        
        // Fallback to Arabic
        if (isset($data[$fieldName . '_ar']) && !empty($data[$fieldName . '_ar'])) {
            return $data[$fieldName . '_ar'];
        }
        
        // Fallback to English
        if (isset($data[$fieldName . '_en']) && !empty($data[$fieldName . '_en'])) {
            return $data[$fieldName . '_en'];
        }
        
        // Fallback to field without suffix
        if (isset($data[$fieldName])) {
            return $data[$fieldName];
        }
        
        return '';
    }
}

if (!function_exists('getTextAlignClass')) {
    /**
     * Get text alignment class based on language
     */
    function getTextAlignClass() {
        $lang = getCurrentLanguage();
        return $lang === 'ar' ? 'text-right' : 'text-left';
    }
}

if (!function_exists('formatLocalizedDateTime')) {
    /**
     * Format datetime based on language
     */
    function formatLocalizedDateTime($datetime) {
        if (empty($datetime)) {
            return '';
        }
        
        $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
        $lang = getCurrentLanguage();
        
        if ($lang === 'ar') {
            return date('Y/m/d H:i', $timestamp);
        } else {
            return date('M d, Y H:i', $timestamp);
        }
    }
}