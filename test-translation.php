<?php
// Test translation system
session_start();

define('APP_ROOT', __DIR__);
define('APP_PATH', APP_ROOT . '/app');

// Load helper functions
require_once APP_PATH . '/Core/helpers.php';
require_once APP_PATH . '/helpers/multilingual.php';

// Load Language class
require_once APP_PATH . '/Core/Language.php';

echo "Current Language: " . getCurrentLanguage() . "<br>";
echo "Hero Title: " . __('hero_title') . "<br>";
echo "Hero Subtitle: " . __('hero_subtitle') . "<br>";
echo "Login: " . __('login') . "<br>";
echo "Register: " . __('register') . "<br>";
echo "Feature Secure: " . __('feature_secure') . "<br>";

// Test switching language
$_SESSION['language'] = 'en';
echo "<hr>After switching to English:<br>";
echo "Current Language: " . getCurrentLanguage() . "<br>";
echo "Hero Title: " . __('hero_title') . "<br>";
echo "Hero Subtitle: " . __('hero_subtitle') . "<br>";
echo "Login: " . __('login') . "<br>";
echo "Register: " . __('register') . "<br>";
echo "Feature Secure: " . __('feature_secure') . "<br>";
