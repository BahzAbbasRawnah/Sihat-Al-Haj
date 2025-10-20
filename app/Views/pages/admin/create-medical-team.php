<?php
/**
 * Create Medical Team - Modern Design
 */
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">إضافة فريق طبي جديد</h1>
            <p class="text-white text-opacity-90">إضافة فريق طبي جديد إلى النظام</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-users-medical text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            معلومات الفريق
        </h2>
    </div>
    
    <div class="p-8">
        <form method="POST" action="/sihat-al-haj/admin/medical-teams/create" class="space-y-6">
            <!-- Team Names -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Arabic Name -->
                <div>
                    <label for="team_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الاسم بالعربية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="team_name_ar" name="team_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($errors['team_name_ar']) ? 'border-red-500' : '' ?>"
                           value="<?= htmlspecialchars($_SESSION['old_input']['team_name_ar'] ?? '') ?>"
                           placeholder="مثال: فريق الطوارئ الأول">
                    <?php if (isset($errors['team_name_ar'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['team_name_ar'] ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- English Name -->
                <div>
                    <label for="team_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الاسم بالإنجليزية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="team_name_en" name="team_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($errors['team_name_en']) ? 'border-red-500' : '' ?>"
                           value="<?= htmlspecialchars($_SESSION['old_input']['team_name_en'] ?? '') ?>"
                           placeholder="Example: Emergency Team One">
                    <?php if (isset($errors['team_name_en'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['team_name_en'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Descriptions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Description Arabic -->
                <div>
                    <label for="description_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوصف بالعربية
                    </label>
                    <textarea id="description_ar" name="description_ar" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="وصف الفريق الطبي..."><?= htmlspecialchars($_SESSION['old_input']['description_ar'] ?? '') ?></textarea>
                </div>
                
                <!-- Description English -->
                <div>
                    <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوصف بالإنجليزية
                    </label>
                    <textarea id="description_en" name="description_en" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Medical team description..."><?= htmlspecialchars($_SESSION['old_input']['description_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Location Arabic -->
                <div>
                    <label for="current_location_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الموقع الحالي بالعربية
                    </label>
                    <input type="text" id="current_location_ar" name="current_location_ar"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['current_location_ar'] ?? '') ?>"
                           placeholder="مكة المكرمة">
                </div>
                
                <!-- Current Location English -->
                <div>
                    <label for="current_location_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الموقع الحالي بالإنجليزية
                    </label>
                    <input type="text" id="current_location_en" name="current_location_en"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['current_location_en'] ?? '') ?>"
                           placeholder="Makkah">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-semibold text-gray-700 mb-2">
                        رقم الاتصال
                    </label>
                    <input type="text" id="contact_number" name="contact_number"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($errors['contact_number']) ? 'border-red-500' : '' ?>"
                           value="<?= htmlspecialchars($_SESSION['old_input']['contact_number'] ?? '') ?>"
                           placeholder="+966 50 123 4567">
                    <?php if (isset($errors['contact_number'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['contact_number'] ?></p>
                    <?php endif; ?>
                </div>
                
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        الحالة
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="available">متاح</option>
                        <option value="on_mission">في مهمة</option>
                        <option value="on_break">في استراحة</option>
                        <option value="unavailable">غير متاح</option>
                    </select>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/medical-teams" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>إلغاء</span>
                </a>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-opacity-90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>حفظ الفريق</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php 
// Clear old input after displaying
unset($_SESSION['old_input']);
unset($_SESSION['validation_errors']);
?>
