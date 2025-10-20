<?php
/**
 * Enhanced Admin Dashboard with Modern Design
 */
$s = $dashboardStats ?? [];
$currentLang = getCurrentLanguage();
?>

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('welcome_to_dashboard') ?></h1>
            <p class="text-white text-opacity-90"><?= __('system_overview') ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-chart-line text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Main Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6 relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-400 to-blue-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-blue-100 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-users text-2xl text-blue-600"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">+12%</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1"><?= __('total_users') ?></h3>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-800"><?= number_format($s['users_count'] ?? 0) ?></p>
                <p class="text-xs text-gray-500"><?= __('all_types') ?></p>
            </div>
        </div>
    </div>

    <!-- Pilgrims Card -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6 relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-green-400 to-green-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-green-100 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-50 px-3 py-1 rounded-full">نشط</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1"><?= __('pilgrims') ?></h3>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-800"><?= number_format($s['pilgrims_count'] ?? 0) ?></p>
                <p class="text-xs text-gray-500"><?= __('registered_pilgrims') ?></p>
            </div>
        </div>
    </div>

    <!-- Medical Teams Card -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6 relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-yellow-400 to-yellow-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-yellow-100 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-users-medical text-2xl text-yellow-600"></i>
                </div>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-50 px-3 py-1 rounded-full">فعال</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1"><?= __('medical_teams') ?></h3>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-800"><?= number_format($s['medical_teams_count'] ?? 0) ?></p>
                <p class="text-xs text-gray-500"><?= __('active_teams') ?></p>
            </div>
        </div>
    </div>

    <!-- Medical Centers Card -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6 relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-400 to-purple-600"></div>
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-purple-100 rounded-lg group-hover:scale-110 transition-transform">
                    <i class="fas fa-hospital text-2xl text-purple-600"></i>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">متاح</span>
            </div>
            <h3 class="text-gray-600 text-sm font-medium mb-1"><?= __('medical_centers') ?></h3>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-800"><?= number_format($s['medical_centers_count'] ?? 0) ?></p>
                <p class="text-xs text-gray-500"><?= __('available_centers') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-indigo-100 rounded-lg">
                <i class="fas fa-user-md text-xl text-indigo-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1"><?= __('medical_staff') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($s['medical_staff_count'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-pink-100 rounded-lg">
                <i class="fas fa-concierge-bell text-xl text-pink-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1"><?= __('services') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($s['services_count'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-orange-100 rounded-lg">
                <i class="fas fa-clock text-xl text-orange-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1"><?= __('pending_requests') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($s['pending_requests'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow hover:shadow-md transition p-4">
        <div class="flex items-center gap-3">
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-exclamation-triangle text-xl text-red-600"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1"><?= __('critical_requests') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($s['critical_requests'] ?? 0) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
    <div class="bg-gradient-to-r from-primary to-secondary p-4">
        <h2 class="text-xl font-bold text-white flex items-center">
            <i class="fas fa-bolt <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
            <?= __('quick_actions') ?>
        </h2>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/sihat-al-haj/admin/users/create" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 rounded-xl transition-all duration-300 transform hover:scale-105">
                <div class="p-4 bg-blue-600 rounded-full mb-3 group-hover:bg-blue-700 transition">
                    <i class="fas fa-user-plus text-2xl text-white"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700"><?= __('new_user') ?></span>
            </a>

            <a href="/sihat-al-haj/admin/medical-teams/create" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-yellow-50 to-yellow-100 hover:from-yellow-100 hover:to-yellow-200 rounded-xl transition-all duration-300 transform hover:scale-105">
                <div class="p-4 bg-yellow-600 rounded-full mb-3 group-hover:bg-yellow-700 transition">
                    <i class="fas fa-users-medical text-2xl text-white"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700"><?= __('medical_team') ?></span>
            </a>

            <a href="/sihat-al-haj/admin/medical-centers/create" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 rounded-xl transition-all duration-300 transform hover:scale-105">
                <div class="p-4 bg-purple-600 rounded-full mb-3 group-hover:bg-purple-700 transition">
                    <i class="fas fa-hospital text-2xl text-white"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700"><?= __('medical_center') ?></span>
            </a>

            <a href="/sihat-al-haj/admin/services/create" class="group flex flex-col items-center justify-center p-6 bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 rounded-xl transition-all duration-300 transform hover:scale-105">
                <div class="p-4 bg-green-600 rounded-full mb-3 group-hover:bg-green-700 transition">
                    <i class="fas fa-plus text-2xl text-white"></i>
                </div>
                <span class="text-sm font-semibold text-gray-700"><?= __('new_service') ?></span>
            </a>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-users <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                <?= __('new_users') ?>
            </h2>
        </div>
        <div class="p-6">
            <?php if (!empty($recent_users)): ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($recent_users, 0, 5) as $user): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                    <?= mb_substr($user['full_name_ar'] ?? 'م', 0, 1) ?>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800"><?= htmlspecialchars(getLocalizedField($user, 'full_name') ?: __('not_specified')) ?></p>
                                    <p class="text-xs text-gray-500"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                </div>
                            </div>
                            <span class="text-xs px-3 py-1 bg-green-100 text-green-700 rounded-full font-medium">
                                <?= htmlspecialchars($user['user_type'] ?? 'مستخدم') ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-500 py-8"><?= __('no_new_users') ?></p>
            <?php endif; ?>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-bell <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                <?= __('system_alerts') ?>
            </h2>
        </div>
        <div class="p-6">
            <?php if (!empty($system_alerts)): ?>
                <div class="space-y-3">
                    <?php foreach (array_slice($system_alerts, 0, 5) as $alert): ?>
                        <div class="flex items-start gap-3 p-3 border-r-4 <?= $alert['type'] === 'critical' ? 'border-red-500 bg-red-50' : ($alert['type'] === 'warning' ? 'border-yellow-500 bg-yellow-50' : 'border-blue-500 bg-blue-50') ?> rounded-lg">
                            <i class="fas fa-<?= $alert['type'] === 'critical' ? 'exclamation-circle text-red-600' : ($alert['type'] === 'warning' ? 'exclamation-triangle text-yellow-600' : 'info-circle text-blue-600') ?> mt-1"></i>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars($alert['title'] ?? '') ?></p>
                                <p class="text-xs text-gray-600 mt-1"><?= htmlspecialchars($alert['message'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-5xl text-green-500 mb-3"></i>
                    <p class="text-gray-600 font-medium"><?= __('no_alerts') ?></p>
                    <p class="text-sm text-gray-500"><?= __('system_running_normally') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Activity Timeline -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center">
            <i class="fas fa-history <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
            <?= __('recent_activity') ?>
        </h2>
    </div>
    <div class="p-6">
        <?php if (!empty($recent_activities)): ?>
            <div class="space-y-4">
                <?php foreach (array_slice($recent_activities, 0, 8) as $activity): ?>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-2 h-2 bg-primary rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-800"><?= htmlspecialchars($activity['description'] ?? '') ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($activity['time'] ?? '') ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500 py-8"><?= __('no_recent_activities') ?></p>
        <?php endif; ?>
    </div>
</div>
