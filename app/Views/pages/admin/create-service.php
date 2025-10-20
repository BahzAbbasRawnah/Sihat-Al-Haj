<?php
/**
 * Create Service - Modern Design
 */
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">إضافة خدمة جديدة</h1>
            <p class="text-white text-opacity-90">إضافة خدمة رقمية جديدة للحجاج</p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-concierge-bell text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            معلومات الخدمة
        </h2>
    </div>
    
    <div class="p-8">
        <form method="POST" action="/sihat-al-haj/admin/services/create" class="space-y-6">
            <!-- Service Names -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="service_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الاسم بالعربية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="service_name_ar" name="service_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['service_name_ar'] ?? '') ?>"
                           placeholder="مثال: حجز الفنادق">
                </div>
                
                <div>
                    <label for="service_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الاسم بالإنجليزية <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="service_name_en" name="service_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['service_name_en'] ?? '') ?>"
                           placeholder="Example: Hotel Booking">
                </div>
            </div>
            
            <!-- Descriptions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <label for="description_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوصف بالعربية
                    </label>
                    <textarea id="description_ar" name="description_ar" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="وصف الخدمة..."><?= htmlspecialchars($_SESSION['old_input']['description_ar'] ?? '') ?></textarea>
                </div>
                
                <div>
                    <label for="description_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الوصف بالإنجليزية
                    </label>
                    <textarea id="description_en" name="description_en" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                              placeholder="Service description..."><?= htmlspecialchars($_SESSION['old_input']['description_en'] ?? '') ?></textarea>
                </div>
            </div>
            
            <!-- Icon and Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="icon_name" class="block text-sm font-semibold text-gray-700 mb-2">
                        اسم الأيقونة
                    </label>
                    <input type="text" id="icon_name" name="icon_name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['icon_name'] ?? 'concierge-bell') ?>"
                           placeholder="concierge-bell">
                    <p class="text-sm text-gray-500 mt-1">FontAwesome icon name</p>
                </div>
                
                <div>
                    <label for="is_active" class="block text-sm font-semibold text-gray-700 mb-2">
                        الحالة
                    </label>
                    <select id="is_active" name="is_active" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="1" selected>نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/services" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span>إلغاء</span>
                </a>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-opacity-90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>حفظ الخدمة</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['old_input']); ?>
