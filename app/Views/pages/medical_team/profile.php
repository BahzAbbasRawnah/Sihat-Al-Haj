<?php $pageTitle = 'الملف الشخصي'; ?>

<div class="container mx-auto px-4 py-6">
    <!-- Profile Header -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-6">
        <div class="flex flex-col md:flex-row items-center">
            <div class="w-32 h-32 relative mb-4 md:mb-0 md:mr-6">
                <?php if (!empty($user['profile_image_url'])): ?>
                    <img src="<?= htmlspecialchars($user['profile_image_url']) ?>" 
                         alt="<?= htmlspecialchars(($user['first_name_ar'] ?? '') . ' ' . ($user['last_name_ar'] ?? '')) ?>"
                         class="w-full h-full object-cover rounded-full">
                <?php else: ?>
                    <div class="w-full h-full rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user-md text-4xl text-blue-500"></i>
                    </div>
                <?php endif; ?>
                
                <button onclick="document.getElementById('profileImageInput').click()" 
                        class="absolute bottom-0 right-0 bg-blue-500 text-white p-2 rounded-full hover:bg-blue-600 transition-colors">
                    <i class="fas fa-camera"></i>
                </button>
                <input type="file" id="profileImageInput" class="hidden" accept="image/*" onchange="updateProfileImage(this)">
            </div>
            
            <div class="flex-1 text-center md:text-right">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">
                    <?= htmlspecialchars(($user['first_name_ar'] ?? '') . ' ' . ($user['last_name_ar'] ?? '')) ?>
                </h1>
                <p class="text-gray-600">
                    <?php if ($team): ?>
                        <?= htmlspecialchars($team['team_name_ar']) ?>
                        -
                        <span class="<?= $team['status'] === 'available' ? 'text-green-500' : 'text-blue-500' ?>">
                            <?= $team['status'] === 'available' ? 'متاح' : 'في مهمة' ?>
                        </span>
                    <?php else: ?>
                        <span class="text-gray-400">لا يوجد فريق مسجل</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">المعلومات الشخصية</h2>
        
        <form id="profileForm" class="space-y-6" onsubmit="updateProfile(event)">
            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول (بالعربية)</label>
                    <input type="text" name="first_name_ar" value="<?= htmlspecialchars($user['first_name_ar'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأخير (بالعربية)</label>
                    <input type="text" name="last_name_ar" value="<?= htmlspecialchars($user['last_name_ar'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأول (بالإنجليزية)</label>
                    <input type="text" name="first_name_en" value="<?= htmlspecialchars($user['first_name_en'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">الاسم الأخير (بالإنجليزية)</label>
                    <input type="text" name="last_name_en" value="<?= htmlspecialchars($user['last_name_en'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الجوال</label>
                    <input type="tel" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>"
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Medical Specialization -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">المعلومات المهنية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                        <input type="text" name="specialization" value="<?= htmlspecialchars($medical_info['specialization'] ?? '') ?>"
                               class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">رقم الترخيص المهني</label>
                        <input type="text" name="license_number" value="<?= htmlspecialchars($medical_info['license_number'] ?? '') ?>"
                               class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Change Password Section -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">تغيير كلمة المرور</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الحالية</label>
                        <input type="password" name="current_password"
                               class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور الجديدة</label>
                        <input type="password" name="new_password"
                               class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور الجديدة</label>
                        <input type="password" name="confirm_password"
                               class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600 transition-colors">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
async function updateProfileImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_image', input.files[0]);

        try {
            const response = await fetch('/sihat-al-haj/medical-team/update-profile-image', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                location.reload();
            } else {
                alert('فشل في تحديث الصورة: ' + data.message);
            }
        } catch (error) {
            alert('حدث خطأ أثناء تحديث الصورة');
            console.error(error);
        }
    }
}

async function updateProfile(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    try {
        const response = await fetch('/sihat-al-haj/medical-team/update-profile', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('تم تحديث الملف الشخصي بنجاح');
            location.reload();
        } else {
            alert('فشل في تحديث الملف الشخصي: ' + data.message);
        }
    } catch (error) {
        alert('حدث خطأ أثناء تحديث الملف الشخصي');
        console.error(error);
    }
}
</script>