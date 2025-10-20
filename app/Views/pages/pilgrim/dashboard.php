<?php
/**
 * Pilgrim Dashboard - Fully Multilingual
 * Displays pilgrim dashboard with dynamic language support
 */

$pageTitle = 'لوحة التحكم';
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="mb-6">
    <h2 class="text-2xl font-bold text-primary"><?= __('welcome_back') ?>, <?= htmlspecialchars(getLocalizedField($user, 'first_name')) ?>!</h2>
    <p class="text-text-secondary <?= getTextAlignClass() ?>"><?= __('dashboard_overview_message') ?></p>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Health Status -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500"><?= __('health_status') ?></p>
                <h3 class="text-2xl font-bold text-green-600"><?= __('good') ?></h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-heartbeat text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Medical Requests -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500"><?= __('medical_requests') ?></p>
                <h3 class="text-2xl font-bold text-blue-600"><?= $stats['medical_requests'] ?? 0 ?></h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-file-medical text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Notifications -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500"><?= __('notifications') ?></p>
                <h3 class="text-2xl font-bold text-yellow-600"><?= $stats['unread_notifications'] ?? 0 ?></h3>
            </div>
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                <i class="fas fa-bell text-2xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Group Members -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500"><?= __('group_members') ?></p>
                <h3 class="text-2xl font-bold text-purple-600"><?= $stats['group_members'] ?? 0 ?></h3>
            </div>
            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="dashboard-card mb-6">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
        <h3 class="font-bold text-primary <?= getTextAlignClass() ?>"><?= __('quick_actions') ?></h3>
    </div>
    <div class="p-4 grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="/pilgrim/medical-request" class="flex flex-col items-center p-4 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
            <i class="fas fa-ambulance text-3xl text-red-600 mb-2"></i>
            <span class="text-sm text-center"><?= __('request_medical_help') ?></span>
        </a>
        <a href="/pilgrim/health" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
            <i class="fas fa-heartbeat text-3xl text-green-600 mb-2"></i>
            <span class="text-sm text-center"><?= __('health_profile') ?></span>
        </a>
        <a href="/pilgrim/notifications" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
            <i class="fas fa-bell text-3xl text-yellow-600 mb-2"></i>
            <span class="text-sm text-center"><?= __('notifications') ?></span>
        </a>
        <a href="/pilgrim/support" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
            <i class="fas fa-headset text-3xl text-purple-600 mb-2"></i>
            <span class="text-sm text-center"><?= __('support') ?></span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Recent Medical Requests -->
    <div class="dashboard-card">
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
            <h3 class="font-bold text-primary <?= getTextAlignClass() ?>"><?= __('recent_medical_requests') ?></h3>
        </div>
        <div class="p-4">
            <?php if (!empty($recent_requests)): ?>
                <div class="space-y-3">
                    <?php foreach ($recent_requests as $request): ?>
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0 <?= $currentLang === 'ar' ? 'ml-3' : 'mr-3' ?>">
                                <?php
                                $urgencyColors = [
                                    'low' => 'bg-green-100 text-green-600',
                                    'medium' => 'bg-yellow-100 text-yellow-600',
                                    'high' => 'bg-orange-100 text-orange-600',
                                    'critical' => 'bg-red-100 text-red-600'
                                ];
                                $urgencyColor = $urgencyColors[$request['urgency_level']] ?? 'bg-gray-100 text-gray-600';
                                ?>
                                <div class="w-10 h-10 rounded-full <?= $urgencyColor ?> flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 <?= getTextAlignClass() ?>">
                                    <?= htmlspecialchars(getLocalizedField($request, 'request_type')) ?>
                                </h4>
                                <p class="text-sm text-gray-600 <?= getTextAlignClass() ?>">
                                    <?= htmlspecialchars(getLocalizedField($request, 'description')) ?>
                                </p>
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <i class="fas fa-clock <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                    <span><?= formatLocalizedDateTime($request['requested_at']) ?></span>
                                    <span class="mx-2">•</span>
                                    <span class="px-2 py-1 rounded-full <?= $urgencyColor ?>">
                                        <?= __('urgency_' . $request['urgency_level']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p><?= __('no_medical_requests') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Recent Notifications -->
    <div class="dashboard-card">
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-transparent">
            <h3 class="font-bold text-primary <?= getTextAlignClass() ?>"><?= __('recent_notifications') ?></h3>
        </div>
        <div class="p-4">
            <?php if (!empty($recent_notifications)): ?>
                <div class="space-y-3">
                    <?php foreach ($recent_notifications as $notification): ?>
                        <div class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors <?= !$notification['is_read'] ? 'border-l-4 border-blue-500' : '' ?>">
                            <div class="flex-shrink-0 <?= $currentLang === 'ar' ? 'ml-3' : 'mr-3' ?>">
                                <?php
                                $categoryColors = [
                                    'emergency' => 'bg-red-100 text-red-600',
                                    'health' => 'bg-green-100 text-green-600',
                                    'general' => 'bg-blue-100 text-blue-600',
                                    'warning' => 'bg-yellow-100 text-yellow-600'
                                ];
                                $categoryColor = $categoryColors[$notification['category']] ?? 'bg-gray-100 text-gray-600';
                                ?>
                                <div class="w-10 h-10 rounded-full <?= $categoryColor ?> flex items-center justify-center">
                                    <i class="<?= htmlspecialchars($notification['icon_name'] ?? 'fas fa-bell') ?>"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 <?= getTextAlignClass() ?>">
                                    <?= htmlspecialchars(getLocalizedField($notification, 'title')) ?>
                                </h4>
                                <p class="text-sm text-gray-600 <?= getTextAlignClass() ?>">
                                    <?= htmlspecialchars(getLocalizedField($notification, 'content')) ?>
                                </p>
                                <div class="flex items-center mt-2 text-xs text-gray-500">
                                    <i class="fas fa-clock <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                    <span><?= formatLocalizedDateTime($notification['created_at']) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p><?= __('no_notifications') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Health Summary -->
<div class="dashboard-card mb-6">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-transparent">
        <h3 class="font-bold text-primary <?= getTextAlignClass() ?>"><?= __('health_summary') ?></h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Blood Type -->
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-tint text-red-600 text-2xl <?= $currentLang === 'ar' ? 'ml-3' : 'mr-3' ?>"></i>
                <div>
                    <p class="text-xs text-gray-500"><?= __('blood_type') ?></p>
                    <p class="font-semibold"><?= htmlspecialchars($pilgrim['blood_type'] ?? __('not_specified')) ?></p>
                </div>
            </div>
            
            <!-- Allergies -->
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-allergies text-orange-600 text-2xl <?= $currentLang === 'ar' ? 'ml-3' : 'mr-3' ?>"></i>
                <div>
                    <p class="text-xs text-gray-500"><?= __('allergies') ?></p>
                    <p class="font-semibold">
                        <?= !empty($pilgrim['allergies_ar']) || !empty($pilgrim['allergies_en']) ? __('yes') : __('none') ?>
                    </p>
                </div>
            </div>
            
            <!-- Chronic Diseases -->
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <i class="fas fa-notes-medical text-blue-600 text-2xl <?= $currentLang === 'ar' ? 'ml-3' : 'mr-3' ?>"></i>
                <div>
                    <p class="text-xs text-gray-500"><?= __('chronic_diseases') ?></p>
                    <p class="font-semibold"><?= $stats['chronic_diseases'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Contact -->
<div class="fixed bottom-6 <?= $currentLang === 'ar' ? 'left-6' : 'right-6' ?> z-50">
    <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
        <i class="fas fa-phone text-xl"></i>
    </a>
    <div class="absolute bottom-20 <?= $currentLang === 'ar' ? 'left-0' : 'right-0' ?> bg-red-600 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 hover:opacity-100 transition-opacity duration-300">
        <span><?= __('emergency_997') ?></span>
    </div>
</div>
