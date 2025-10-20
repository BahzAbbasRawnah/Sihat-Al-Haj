<?php

/**
 * Web Routes
 * 
 * Define all web routes for the application
 */

use App\Core\Router;

$router = new Router();

// ============================================
// PUBLIC ROUTES
// ============================================

// Home routes
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// Language switching
$router->get('/lang/{lang}', 'HomeController@switchLanguage');
$router->get('/switch-language/{lang}', 'HomeController@switchLanguage');

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Login
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');

// Register
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');

// Logout
$router->get('/logout', 'AuthController@logout');
$router->post('/logout', 'AuthController@logout');

// Password Reset
$router->get('/forgot-password', 'AuthController@showForgotPassword');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password/{token}', 'AuthController@showResetPassword');
$router->post('/reset-password', 'AuthController@resetPassword');

// ============================================
// PROTECTED ROUTES (Require Authentication)
// ============================================

// General Dashboard & Profile
$router->get('/dashboard', 'DashboardController@index');
$router->get('/profile', 'UserController@profile');
$router->post('/profile', 'UserController@updateProfile');

// ============================================
// PILGRIM ROUTES
// ============================================
$router->get('/pilgrim/dashboard', 'PilgrimController@dashboard');
$router->get('/pilgrim/health', 'PilgrimController@health');
$router->get('/pilgrim/health-data', 'PilgrimController@healthData');
$router->get('/pilgrim/health-reports', 'PilgrimController@healthReports');
$router->post('/pilgrim/health', 'PilgrimController@updateHealth');
$router->post('/pilgrim/add-chronic-disease', 'PilgrimController@addChronicDisease');
$router->post('/pilgrim/add-health-data', 'PilgrimController@addHealthData');
$router->get('/pilgrim/medical', 'PilgrimController@medical');
$router->get('/pilgrim/medical-requests', 'PilgrimController@medicalRequests');
$router->get('/pilgrim/medical-request', 'PilgrimController@showMedicalRequest');
$router->post('/pilgrim/medical-request', 'PilgrimController@createMedicalRequest');
$router->post('/pilgrim/create-medical-request', 'PilgrimController@createMedicalRequest');
$router->get('/pilgrim/support', 'PilgrimController@support');
$router->get('/pilgrim/wallet', 'PilgrimController@wallet');
$router->get('/pilgrim/services', 'PilgrimController@services');
$router->get('/pilgrim/notifications', 'PilgrimController@notifications');
$router->post('/pilgrim/service-request', 'PilgrimController@createServiceRequest');

// ============================================
// MEDICAL TEAM ROUTES
// ============================================
$router->get('/medical-team/medical-dashboard', 'MedicalTeamController@dashboard');
$router->get('/medical-team/patients', 'MedicalTeamController@patients');
$router->get('/medical-team/patient-details/{id}', 'MedicalTeamController@patientDetails');
$router->post('/medical-team/add-health-record', 'MedicalTeamController@addHealthRecord');
$router->get('/medical-team/requests', 'MedicalTeamController@requests');
$router->get('/medical-team/notifications', 'MedicalTeamController@notifications');
$router->post('/medical-team/send-notification', 'MedicalTeamController@sendNotification');
$router->post('/medical-team/update-request-status', 'MedicalTeamController@updateRequestStatus');
$router->get('/medical-team/request/{id}', 'MedicalTeamController@viewRequest');
$router->post('/medical-team/request/{id}/respond', 'MedicalTeamController@respondToRequest');
$router->get('/medical-team/reports', 'MedicalTeamController@reports');
$router->post('/medical-team/report', 'MedicalTeamController@createReport');
$router->get('/medical-team/profile', 'MedicalTeamController@profile');
$router->post('/medical-team/update-profile', 'MedicalTeamController@updateProfile');
$router->post('/medical-team/update-profile-image', 'MedicalTeamController@updateProfileImage');

// ============================================
// GUIDE ROUTES
// ============================================
$router->get('/guide/dashboard', 'GuideController@dashboard');
$router->get('/guide/tracking', 'GuideController@tracking');
$router->get('/guide/notifications', 'GuideController@notifications');
$router->get('/guide/health-data', 'GuideController@healthData');
$router->get('/guide/group', 'GuideController@group');
$router->post('/guide/notification', 'GuideController@sendNotification');

// ============================================
// ADMINISTRATOR ROUTES
// ============================================

// Admin Dashboard
$router->get('/admin', 'AdminController@index');
$router->get('/admin/dashboard', 'AdminController@index');

// User Management
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/users/create', 'AdminController@createUser');
$router->post('/admin/users/create', 'AdminController@createUser');
$router->get('/admin/users/edit/{id}', 'AdminController@editUser');
$router->post('/admin/users/edit/{id}', 'AdminController@editUser');
$router->post('/admin/users/delete/{id}', 'AdminController@deleteUser');

// Medical Personnel Management
$router->get('/admin/medical-personnel', 'AdminController@medicalPersonnel');

// Medical Teams Management
$router->get('/admin/medical-teams', 'AdminController@medicalTeams');
$router->get('/admin/medical-teams/create', 'AdminController@createMedicalTeam');
$router->post('/admin/medical-teams/create', 'AdminController@createMedicalTeam');
$router->get('/admin/medical-teams/details/{id}', 'AdminController@medicalTeamDetails');
$router->get('/admin/medical-teams/edit/{id}', 'AdminController@editMedicalTeam');
$router->post('/admin/medical-teams/edit/{id}', 'AdminController@editMedicalTeam');
$router->post('/admin/medical-teams/delete/{id}', 'AdminController@deleteMedicalTeam');
$router->post('/admin/medical-teams/add-member/{id}', 'AdminController@addTeamMember');
$router->post('/admin/medical-teams/update-member', 'AdminController@updateTeamMember');
$router->post('/admin/medical-teams/remove-member/{id}', 'AdminController@removeTeamMember');

// Medical Centers Management
$router->get('/admin/medical-centers', 'AdminController@medicalCenters');
$router->get('/admin/medical-centers/create', 'AdminController@createMedicalCenter');
$router->post('/admin/medical-centers/create', 'AdminController@createMedicalCenter');
$router->get('/admin/medical-centers/details/{id}', 'AdminController@medicalCenterDetails');
$router->get('/admin/medical-centers/edit/{id}', 'AdminController@editMedicalCenter');
$router->post('/admin/medical-centers/edit/{id}', 'AdminController@editMedicalCenter');
$router->post('/admin/medical-centers/delete/{id}', 'AdminController@deleteMedicalCenter');

// Medical Requests Management
$router->get('/admin/medical-requests', 'AdminController@medicalRequests');

// Services Management
$router->get('/admin/services', 'AdminController@services');
$router->get('/admin/services/create', 'AdminController@createService');
$router->post('/admin/services/create', 'AdminController@createService');
$router->get('/admin/services/edit/{id}', 'AdminController@editService');
$router->post('/admin/services/edit/{id}', 'AdminController@editService');
$router->post('/admin/services/delete/{id}', 'AdminController@deleteService');
$router->post('/admin/services/toggle-status/{id}', 'AdminController@toggleServiceStatus');

// System Settings
$router->get('/admin/settings', 'AdminController@settings');
$router->post('/admin/settings', 'AdminController@settings');

// Reports and Analytics
$router->get('/admin/reports', 'AdminController@reports');
$router->get('/admin/analytics', 'AdminController@analytics');

// ============================================
// API ROUTES
// ============================================
$router->get('/api/locations', 'ApiController@locations');
$router->post('/api/location', 'ApiController@updateLocation');
$router->get('/api/notifications', 'ApiController@notifications');
$router->post('/api/notification/{id}/read', 'ApiController@markNotificationRead');

// ============================================
// PUBLIC PAGES
// ============================================
$router->get('/about', 'PublicController@about');
$router->get('/guidelines', 'PublicController@guidelines');
$router->get('/contact', 'PublicController@contact');
$router->post('/contact', 'PublicController@contact');
$router->get('/services', 'PublicController@services');
$router->get('/medical-centers', 'PublicController@medicalCenters');
$router->get('/medical-teams', 'PublicController@medicalTeams');
$router->get('/faqs', 'PublicController@faqs');

$router->get('/privacy', 'HomeController@privacy');
$router->get('/terms', 'HomeController@terms');

// ============================================
// ERROR PAGES
// ============================================
$router->get('/error/404', 'ErrorController@notFoundPage');
$router->get('/error/500', 'ErrorController@serverError');
$router->get('/unauthorized', 'ErrorController@unauthorized');

// ============================================
// UTILITY ROUTES
// ============================================

// File upload routes
$router->post('/upload/image', 'UploadController@image');
$router->post('/upload/document', 'UploadController@document');

// QR Code routes
$router->get('/qr/{code}', 'QRController@scan');

// Health check
$router->get('/health', function() {
    return json_encode(['status' => 'ok', 'timestamp' => date('Y-m-d H:i:s')]);
});

return $router;

