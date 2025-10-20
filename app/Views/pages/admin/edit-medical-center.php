<?php
/**
 * Edit Medical Center - Modern Design
 */
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('edit_medical_center') ?></h1>
            <p class="text-white text-opacity-90"><?= __('edit_center_information') ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-hospital-alt text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <?= __('center_information') ?>
        </h2>
    </div>
    
    <div class="p-8">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    <span class="font-semibold"><?= $_SESSION['flash']['error'] ?></span>
                </div>
                <?php if (isset($_SESSION['validation_errors']) && !empty($_SESSION['validation_errors'])): ?>
                    <ul class="list-disc list-inside mt-2 mr-6">
                        <?php foreach ($_SESSION['validation_errors'] as $field => $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>
        
        <form method="POST" action="/sihat-al-haj/admin/medical-centers/edit/<?= $center['center_id'] ?>" class="space-y-6">
            <!-- Center Names -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Arabic Name -->
                <div>
                    <label for="name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_arabic') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name_ar" name="name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['name_ar'] ?? '') ?>">
                </div>
                
                <!-- English Name -->
                <div>
                    <label for="name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_english') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name_en" name="name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['name_en'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Addresses -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Address Arabic -->
                <div>
                    <label for="address_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('address_in_arabic') ?>
                    </label>
                    <textarea id="address_ar" name="address_ar" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($center['address_ar'] ?? '') ?></textarea>
                </div>
                
                <!-- Address English -->
                <div>
                    <label for="address_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('address_in_english') ?>
                    </label>
                    <textarea id="address_en" name="address_en" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($center['address_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Latitude -->
                <div>
                    <label for="latitude" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('latitude') ?>
                    </label>
                    <input type="number" step="0.00000001" id="latitude" name="latitude"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['latitude'] ?? '') ?>">
                </div>
                
                <!-- Longitude -->
                <div>
                    <label for="longitude" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('longitude') ?>
                    </label>
                    <input type="number" step="0.00000001" id="longitude" name="longitude"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['longitude'] ?? '') ?>">
                </div>
                
                <!-- Phone Number -->
                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('phone_number') ?>
                    </label>
                    <input type="text" id="phone_number" name="phone_number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['phone_number'] ?? '') ?>">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Operating Hours Arabic -->
                <div>
                    <label for="operating_hours_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('operating_hours_arabic') ?>
                    </label>
                    <input type="text" id="operating_hours_ar" name="operating_hours_ar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['operating_hours_ar'] ?? '') ?>">
                </div>
                
                <!-- Operating Hours English -->
                <div>
                    <label for="operating_hours_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('operating_hours_english') ?>
                    </label>
                    <input type="text" id="operating_hours_en" name="operating_hours_en"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['operating_hours_en'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Services Offered -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Services Offered Arabic -->
                <div>
                    <label for="services_offered_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('services_offered_arabic') ?>
                    </label>
                    <textarea id="services_offered_ar" name="services_offered_ar" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($center['services_offered_ar'] ?? '') ?></textarea>
                </div>
                
                <!-- Services Offered English -->
                <div>
                    <label for="services_offered_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('services_offered_english') ?>
                    </label>
                    <textarea id="services_offered_en" name="services_offered_en" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($center['services_offered_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('status') ?>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="active" <?= ($center['status'] ?? '') === 'active' ? 'selected' : '' ?>><?= __('active') ?></option>
                        <option value="inactive" <?= ($center['status'] ?? '') === 'inactive' ? 'selected' : '' ?>><?= __('inactive') ?></option>
                        <option value="full" <?= ($center['status'] ?? '') === 'full' ? 'selected' : '' ?>><?= __('full') ?></option>
                    </select>
                </div>
                
                <!-- Icon Name -->
                <div>
                    <label for="icon_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('icon_name') ?>
                    </label>
                    <input type="text" id="icon_name" name="icon_name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($center['icon_name'] ?? 'hospital') ?>">
                    <p class="text-sm text-gray-500 mt-1">FontAwesome icon name</p>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/medical-centers" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
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
