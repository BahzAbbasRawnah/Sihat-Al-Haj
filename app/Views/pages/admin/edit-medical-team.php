<?php
/**
 * Edit Medical Team - Modern Design
 */
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('edit_medical_team') ?></h1>
            <p class="text-white text-opacity-90"><?= __('edit_team_information') ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-edit text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <?= __('team_information') ?>
        </h2>
    </div>
    
    <div class="p-8">
        <form method="POST" action="/sihat-al-haj/admin/medical-teams/edit/<?= $team['team_id'] ?>" class="space-y-6">
            <!-- Team Names -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Arabic Name -->
                <div>
                    <label for="team_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_arabic') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="team_name_ar" name="team_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($team['team_name_ar'] ?? '') ?>">
                </div>
                
                <!-- English Name -->
                <div>
                    <label for="team_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('name_in_english') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="team_name_en" name="team_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($team['team_name_en'] ?? '') ?>">
                </div>
            </div>
            
            <!-- Descriptions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Description Arabic -->
                <div>
                    <label for="description_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('description_in_arabic') ?>
                    </label>
                    <textarea id="description_ar" name="description_ar" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($team['description_ar'] ?? '') ?></textarea>
                </div>
                
                <!-- Description English -->
                <div>
                    <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('description_in_english') ?>
                    </label>
                    <textarea id="description_en" name="description_en" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"><?= htmlspecialchars($team['description_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Location Arabic -->
                <div>
                    <label for="current_location_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('current_location_arabic') ?>
                    </label>
                    <input type="text" id="current_location_ar" name="current_location_ar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($team['current_location_ar'] ?? '') ?>">
                </div>
                
                <!-- Current Location English -->
                <div>
                    <label for="current_location_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('current_location_english') ?>
                    </label>
                    <input type="text" id="current_location_en" name="current_location_en"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($team['current_location_en'] ?? '') ?>">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('contact_number') ?>
                    </label>
                    <input type="text" id="contact_number" name="contact_number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($team['contact_number'] ?? '') ?>">
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('status') ?>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="available" <?= ($team['status'] ?? '') === 'available' ? 'selected' : '' ?>><?= __('available') ?></option>
                        <option value="on_mission" <?= ($team['status'] ?? '') === 'on_mission' ? 'selected' : '' ?>><?= __('on_mission') ?></option>
                        <option value="on_break" <?= ($team['status'] ?? '') === 'on_break' ? 'selected' : '' ?>><?= __('on_break') ?></option>
                        <option value="unavailable" <?= ($team['status'] ?? '') === 'unavailable' ? 'selected' : '' ?>><?= __('unavailable') ?></option>
                    </select>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/medical-teams" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
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




