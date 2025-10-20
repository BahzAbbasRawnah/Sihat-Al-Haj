<?php
/**
 * Edit Service - Modern Design
 */
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('edit_service') ?></h1>
            <p class="text-white text-opacity-90"><?= __('edit_service_information') ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <?= __('service_information') ?>
        </h2>
    </div>
    
    <div class="p-8">
        <form method="POST" action="/sihat-al-haj/admin/services/edit/<?= $service['service_id'] ?>" class="space-y-6">
            <!-- Service Names -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="service_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_arabic') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="service_name_ar" name="service_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($service['service_name_ar'] ?? '') ?>">
                </div>
                
                <div>
                    <label for="service_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_english') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="service_name_en" name="service_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($service['service_name_en'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Descriptions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="description_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('description_in_arabic') ?>
                    </label>
                    <textarea id="description_ar" name="description_ar" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($service['description_ar'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('description_in_english') ?>
                    </label>
                    <textarea id="description_en" name="description_en" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($service['description_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Icon and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="icon_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('icon_name') ?>
                    </label>
                    <input type="text" id="icon_name" name="icon_name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($service['icon_name'] ?? 'concierge-bell') ?>">
                    <p class="text-sm text-gray-500 mt-1">FontAwesome icon name</p>
                </div>
                
                <div>
                    <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('status') ?>
                    </label>
                    <select id="is_active" name="is_active" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="1" <?= ($service['is_active'] ?? 1) == 1 ? 'selected' : '' ?>><?= __('active') ?></option>
                        <option value="0" <?= ($service['is_active'] ?? 1) == 0 ? 'selected' : '' ?>><?= __('inactive') ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/services" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span><?= __('cancel') ?></span>
                </a>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-opacity-90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span><?= __('save_changes') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
