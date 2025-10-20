<?php
/**
 * Pilgrim Support Page
 * صفحة الدعم للحاج
 */
$pageTitle = __('support');
$currentLang = getCurrentLanguage();
?>

<div class="relative z-10">
    <!-- Page Header -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= __('support_center') ?></h1>
                <p class="text-gray-600 mt-1"><?= __('get_help_support') ?></p>
            </div>
        </div>
    </div>

    <!-- Emergency Contacts -->
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
        <h2 class="text-xl font-bold text-red-900 mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i><?= __('emergency_contacts') ?>
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-ambulance text-red-600 text-2xl mb-2"></i>
                    <h3 class="font-bold text-gray-900"><?= __('medical_emergency') ?></h3>
                    <p class="text-2xl font-bold text-red-600 mt-2">997</p>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-shield-alt text-blue-600 text-2xl mb-2"></i>
                    <h3 class="font-bold text-gray-900"><?= __('security') ?></h3>
                    <p class="text-2xl font-bold text-blue-600 mt-2">999</p>
                </div>
            </div>
            <div class="bg-white rounded-lg p-4">
                <div class="text-center">
                    <i class="fas fa-fire text-orange-600 text-2xl mb-2"></i>
                    <h3 class="font-bold text-gray-900"><?= __('fire_department') ?></h3>
                    <p class="text-2xl font-bold text-orange-600 mt-2">998</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Support Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
            <div class="text-center">
                <i class="fas fa-user-md text-blue-600 text-3xl mb-4"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2"><?= __('medical_support') ?></h3>
                <p class="text-gray-600 text-sm"><?= __('medical_support_desc') ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-green-600 text-3xl mb-4"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2"><?= __('location_help') ?></h3>
                <p class="text-gray-600 text-sm"><?= __('location_help_desc') ?></p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow cursor-pointer">
            <div class="text-center">
                <i class="fas fa-cog text-purple-600 text-3xl mb-4"></i>
                <h3 class="text-lg font-bold text-gray-900 mb-2"><?= __('technical_support') ?></h3>
                <p class="text-gray-600 text-sm"><?= __('technical_support_desc') ?></p>
            </div>
        </div>
    </div>

    <!-- Contact Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4"><?= __('contact_support') ?></h2>
        <form class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('subject') ?></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value=""><?= __('select_subject') ?></option>
                        <option value="medical"><?= __('medical_issue') ?></option>
                        <option value="technical"><?= __('technical_issue') ?></option>
                        <option value="location"><?= __('location_issue') ?></option>
                        <option value="other"><?= __('other') ?></option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('priority') ?></label>
                    <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="low"><?= __('low') ?></option>
                        <option value="medium"><?= __('medium') ?></option>
                        <option value="high"><?= __('high') ?></option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('message') ?></label>
                <textarea rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="<?= __('describe_issue') ?>"></textarea>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                <?= __('send_message') ?>
            </button>
        </form>
    </div>
</div>