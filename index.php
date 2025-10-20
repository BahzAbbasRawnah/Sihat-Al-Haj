<?php
/**
 * Sihat Al-Hajj Platform
 * Main Entry Point
 * 
 * This file serves as the front controller for the entire application.
 * All requests are routed through this file.
 */

// Start output buffering to prevent "headers already sent" errors
ob_start();

// Start session
session_start();

// Define application constants
define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');
define('CONFIG_PATH', APP_ROOT . '/config');
define('PUBLIC_PATH', APP_ROOT . '/public');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('VIEWS_PATH', APP_PATH . '/Views');

// Set default timezone
date_default_timezone_set('Asia/Riyadh');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoloader (skip if doesn't exist)
if (file_exists(APP_ROOT . '/vendor/autoload.php')) {
    require_once APP_ROOT . '/vendor/autoload.php';
}

// Load configuration
require_once CONFIG_PATH . '/app.php';
require_once CONFIG_PATH . '/database.php';

// Load helper functions
require_once APP_PATH . '/Core/helpers.php';
require_once APP_PATH . '/helpers/multilingual.php';

// Load core classes
require_once APP_PATH . '/Core/Application.php';
require_once APP_PATH . '/Core/Router.php';
require_once APP_PATH . '/Core/Controller.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Core/View.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Language.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/Session.php';

// Load all models
foreach (glob(APP_PATH . '/Models/*.php') as $modelFile) {
    require_once $modelFile;
}

// Load all controllers
foreach (glob(APP_PATH . '/Controllers/*.php') as $controllerFile) {
    require_once $controllerFile;
}

// Load routes
$router = require_once APP_ROOT . '/routes/web.php';

// Initialize and run the application
$app = new \App\Core\Application();
$app->run();

