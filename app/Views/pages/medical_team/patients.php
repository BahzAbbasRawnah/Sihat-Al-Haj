<?php
$pageTitle = $pageTitle ?? 'إدارة المرضى';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title><?= $pageTitle ?> - صحة الحاج</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2"><?= $pageTitle ?></h1>
                <p class="text-white text-opacity-90 text-lg">متابعة ومراقبة الحالة الصحية للحجاج</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-user-injured text-8xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 mb-8">
        <div class="flex flex-wrap gap-4">
            <div class="flex-grow">
                <div class="relative">
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <input type="text" id="searchInput" placeholder="البحث عن حاج بالاسم أو رقم الحاج..." 
                           class="w-full rounded-lg border border-gray-300 pr-10 p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
            </div>
            <div>
                <select id="healthFilter" class="rounded-lg border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white">
                    <option value="">جميع الحالات</option>
                    <option value="critical">حالات حرجة</option>
                    <option value="chronic">أمراض مزمنة</option>
                    <option value="recent">فحوصات حديثة</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Patients Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($patients)): ?>
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-user-injured text-6xl"></i>
                </div>
                <p class="text-gray-500 text-lg">لا توجد بيانات مرضى متاحة</p>
            </div>
        <?php else: ?>
            <?php foreach ($patients as $patient): ?>
                <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 patient-card">
                    <!-- Patient Header -->
                    <div class="bg-gradient-to-r from-green-50 to-blue-50 p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center shadow-lg">
                                    <i class="fas fa-user text-white text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 text-lg">
                                        <?= htmlspecialchars($patient['first_name_ar'] . ' ' . $patient['last_name_ar']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600 flex items-center gap-1">
                                        <i class="fas fa-id-card text-green-600"></i>
                                        <?= $patient['pilgrim_id'] ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <?php if (($patient['health_records_count'] ?? 0) > 0): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i>
                                        <?= $patient['health_records_count'] ?> سجل
                                    </span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                        <i class="fas fa-exclamation-circle"></i>
                                        لا توجد سجلات
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Info -->
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-birthday-cake text-green-600"></i>
                                العمر
                            </span>
                            <span class="font-semibold text-gray-900"><?= $patient['age'] ?? 'غير محدد' ?> سنة</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-venus-mars text-blue-600"></i>
                                الجنس
                            </span>
                            <span class="font-semibold text-gray-900"><?= isset($patient['gender']) ? ($patient['gender'] === 'male' ? 'ذكر' : 'أنثى') : 'غير محدد' ?></span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <span class="text-gray-600 flex items-center gap-2">
                                <i class="fas fa-phone text-purple-600"></i>
                                الهاتف
                            </span>
                            <span class="font-semibold text-gray-900"><?= htmlspecialchars($patient['phone_number'] ?? 'غير محدد') ?></span>
                        </div>

                        <!-- Health Status -->
                        <?php if (($patient['health_records_count'] ?? 0) > 0): ?>
                            <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-2 text-green-700">
                                    <i class="fas fa-heartbeat"></i>
                                    <span class="font-semibold">آخر فحص:</span>
                                    <span><?= !empty($patient['recent_health']) ? date('Y-m-d', strtotime($patient['recent_health']['recorded_at'])) : 'غير محدد' ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center gap-2 text-yellow-700">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="font-semibold">لا توجد فحوصات مسجلة</span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Chronic Diseases -->
                        <?php if (!empty($patient['chronic_diseases'])): ?>
                            <div class="border-t pt-3 mt-3">
                                <h4 class="font-bold mb-2 text-sm text-gray-700 flex items-center gap-2">
                                    <i class="fas fa-notes-medical text-orange-600"></i>
                                    الأمراض المزمنة
                                </h4>
                                <?php foreach ($patient['chronic_diseases'] as $disease): ?>
                                    <div class="bg-orange-50 border border-orange-200 p-2 rounded-lg mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 text-xs bg-orange-200 text-orange-800 rounded-full font-semibold">مزمن</span>
                                            <span class="font-medium text-gray-900"><?= htmlspecialchars($disease['disease_name']) ?></span>
                                        </div>
                                        <?php if (!empty($disease['notes'])): ?>
                                            <p class="text-xs text-gray-600 mt-1 mr-2"><?= htmlspecialchars($disease['notes']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="p-6 pt-0 flex gap-2">
                        <a href="/sihat-al-haj/medical-team/patient-details/<?= $patient['pilgrim_id'] ?>" 
                           class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center gap-2 shadow-md">
                            <i class="fas fa-eye"></i>
                            عرض التفاصيل
                        </a>
                     
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const patientCards = document.querySelectorAll('.patient-card');
    
    patientCards.forEach(card => {
        const patientName = card.querySelector('h3').textContent.toLowerCase();
        const pilgrimId = card.querySelector('h3').closest('.patient-card').textContent.toLowerCase();
        if (patientName.includes(searchTerm) || pilgrimId.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filter functionality
document.getElementById('healthFilter').addEventListener('change', function() {
    const filterValue = this.value;
    const patientCards = document.querySelectorAll('.patient-card');
    
    patientCards.forEach(card => {
        // Simple filter logic - in real implementation, you'd filter based on actual health data
        if (filterValue === '') {
            card.style.display = 'block';
        } else {
            // This is a placeholder - implement actual filtering logic based on your data structure
            card.style.display = 'block';
        }
    });
});
</script>

</body>
</html>
