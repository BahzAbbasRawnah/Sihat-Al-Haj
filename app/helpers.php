<?php

/**
 * Global Helper Functions
 */

if (!function_exists('url')) {
    /**
     * Generate URL with base path
     */
    function url($path = '') {
        $basePath = '/sihat-al-haj';
        if (strpos($path, $basePath) === 0) {
            return $path;
        }
        return $basePath . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     */
    function asset($path) {
        return url('public/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('route')) {
    /**
     * Generate route URL (alias for url)
     */
    function route($path = '') {
        return url($path);
    }
}

// Load multilingual helper functions
require_once __DIR__ . '/helpers/multilingual.php';