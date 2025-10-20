<?php

/**
 * Application Configuration
 */

// Application settings
define('APP_NAME', 'Sihat Al-Hajj');
define('APP_VERSION', '1.0.0');
define('APP_DEBUG', true); // Set to false in production
define('APP_TIMEZONE', 'Asia/Riyadh');
define('APP_LOCALE', 'ar');

// URL settings
define('APP_URL', 'http://localhost:8080/sihat-al-haj');

// Security settings
define('APP_KEY', 'sihat-al-haj-2024-secure-key-change-in-production');

// File upload settings
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_UPLOAD_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Pagination settings
define('DEFAULT_PAGE_SIZE', 20);
define('MAX_PAGE_SIZE', 100);

// Cache settings
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 3600); // 1 hour

// Session settings
// ini_set('session.cookie_lifetime', 86400); // 24 hours
// ini_set('session.gc_maxlifetime', 86400);
// ini_set('session.cookie_secure', false); // Set to true in production with HTTPS
// ini_set('session.cookie_httponly', true);
// ini_set('session.use_strict_mode', true);

// Error reporting
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Set locale
setlocale(LC_ALL, 'ar_SA.UTF-8', 'Arabic_Saudi Arabia.1256');

return [
    'name' => APP_NAME,
    'version' => APP_VERSION,
    'debug' => APP_DEBUG,
    'timezone' => APP_TIMEZONE,
    'locale' => APP_LOCALE,
    'url' => APP_URL,
    'key' => APP_KEY,
    
    'upload' => [
        'max_size' => MAX_UPLOAD_SIZE,
        'allowed_types' => ALLOWED_UPLOAD_TYPES,
        'path' => PUBLIC_PATH . '/uploads'
    ],
    
    'pagination' => [
        'default_size' => DEFAULT_PAGE_SIZE,
        'max_size' => MAX_PAGE_SIZE
    ],
    
    'cache' => [
        'enabled' => CACHE_ENABLED,
        'lifetime' => CACHE_LIFETIME,
        'path' => STORAGE_PATH . '/cache'
    ],
    
    'logs' => [
        'path' => STORAGE_PATH . '/logs',
        'level' => APP_DEBUG ? 'debug' : 'error'
    ]
];

