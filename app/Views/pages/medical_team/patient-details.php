<?php
$pageTitle = $pageTitle ?? 'تفاصيل الحاج';
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
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['flash']['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle ml-2"></i>
            <span><?= $_SESSION['flash']['success'] ?></span>
        </div>
        <?php unset($_SESSION['flash']['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash']['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
            <i class="fas fa-exclamation-circle ml-2"></i>
            <span><?= $_SESSION['flash']['error'] ?></span>
        </div>
        <?php unset($_SESSION['flash']['error']); ?>
    <?php endif; ?>
    
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-white bg-opacity-20 rounded-full p-4">
                        <i class="fas fa-user-injured text-5xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1">
                            <?= htmlspecialchars(($patient['first_name_ar'] ?? '') . ' ' . ($patient['last_name_ar'] ?? '')) ?>
                        </h1>
                        <p class="text-white text-opacity-90 text-lg flex items-center gap-2">
                            <i class="fas fa-id-card"></i>
                            رقم الحاج: <?= htmlspecialchars($patient['pilgrim_id'] ?? '') ?>
                        </p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex gap-4 mt-4">
                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <div class="text-white text-opacity-80 text-sm">السجلات الصحية</div>
                        <div class="text-2xl font-bold"><?= $health_records_count ?? 0 ?></div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg px-4 py-2">
                        <div class="text-white text-opacity-80 text-sm">الأمراض المزمنة</div>
                        <div class="text-2xl font-bold"><?= count($chronic_diseases ?? []) ?></div>
                    </div>
                </div>
            </div>
            
            <div class="hidden md:block">
                <i class="fas fa-heartbeat text-8xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Patient Information Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-user"></i>
                    المعلومات الشخصية
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start gap-3">
                    <div class="bg-blue-100 rounded-lg p-3">
                        <i class="fas fa-birthday-cake text-blue-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">العمر</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($patient['age'] ?? 'غير محدد') ?> سنة</p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-purple-100 rounded-lg p-3">
                        <i class="fas fa-venus-mars text-purple-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">الجنس</p>
                        <p class="font-semibold text-gray-800">
                            <?= isset($patient['gender']) ? ($patient['gender'] === 'male' ? 'ذكر' : 'أنثى') : 'غير محدد' ?>
                        </p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-green-100 rounded-lg p-3">
                        <i class="fas fa-phone text-green-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">رقم الهاتف</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($patient['phone_number'] ?? 'غير محدد') ?></p>
                    </div>
                </div>
                
                <div class="flex items-start gap-3">
                    <div class="bg-orange-100 rounded-lg p-3">
                        <i class="fas fa-envelope text-orange-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">البريد الإلكتروني</p>
                        <p class="font-semibold text-gray-800"><?= htmlspecialchars($patient['email'] ?? 'غير محدد') ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Latest Vital Signs Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-heartbeat"></i>
                    آخر القياسات الحيوية
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <?php if (!empty($latest_health_record)): ?>
                    <div class="bg-red-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">ضغط الدم</p>
                        <p class="text-2xl font-bold text-red-600"><?= htmlspecialchars($latest_health_record['blood_pressure'] ?? '-') ?></p>
                    </div>
                    
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">معدل النبض</p>
                        <p class="text-2xl font-bold text-blue-600"><?= htmlspecialchars($latest_health_record['heart_rate'] ?? '-') ?> bpm</p>
                    </div>
                    
                    <div class="bg-orange-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">درجة الحرارة</p>
                        <p class="text-2xl font-bold text-orange-600"><?= htmlspecialchars($latest_health_record['temperature'] ?? '-') ?> °C</p>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-4">
                        <p class="text-xs text-gray-600 mb-1">تشبع الأكسجين</p>
                        <p class="text-2xl font-bold text-purple-600"><?= htmlspecialchars($latest_health_record['oxygen_saturation'] ?? '-') ?> %</p>
                    </div>
                    
                    <div class="text-center text-sm text-gray-500 mt-4">
                        <i class="fas fa-clock ml-1"></i>
                        آخر تحديث: <?= date('Y-m-d H:i', strtotime($latest_health_record['recorded_at'])) ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-4xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">لا توجد قياسات حيوية مسجلة</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class="fas fa-cog"></i>
                    الإجراءات
                </h3>
            </div>
            <div class="p-6 space-y-3">
                <button onclick="openAddHealthRecordModal()" 
                        class="block w-full px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-center font-semibold">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة سجل صحي
                </button>
                
                <a href="/sihat-al-haj/medical-team/patients" 
                   class="block w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center font-semibold">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للقائمة
                </a>
                
                <button onclick="window.print()" 
                        class="block w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-center font-semibold">
                    <i class="fas fa-print ml-2"></i>
                    طباعة
                </button>
            </div>
        </div>
    </div>

    <!-- Chronic Diseases Section -->
    <?php if (!empty($chronic_diseases)): ?>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-notes-medical"></i>
                الأمراض المزمنة
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php foreach ($chronic_diseases as $disease): ?>
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <div class="bg-orange-200 rounded-full p-2">
                                <i class="fas fa-disease text-orange-700"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 mb-1"><?= htmlspecialchars($disease['disease_name']) ?></h4>
                                <?php if (!empty($disease['notes'])): ?>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($disease['notes']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($disease['diagnosed_date'])): ?>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-calendar ml-1"></i>
                                        تاريخ التشخيص: <?= date('Y-m-d', strtotime($disease['diagnosed_date'])) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Health Records History -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-history"></i>
                سجل الفحوصات الطبية
            </h3>
            <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold">
                <?= count($health_records ?? []) ?> سجل
            </span>
        </div>
        <div class="p-6">
            <?php if (!empty($health_records)): ?>
                <div class="space-y-4">
                    <?php foreach ($health_records as $record): ?>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="bg-blue-100 rounded-full p-3">
                                        <i class="fas fa-stethoscope text-blue-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">
                                            فحص طبي - <?= date('Y-m-d', strtotime($record['recorded_at'])) ?>
                                        </h4>
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-clock ml-1"></i>
                                            <?= date('H:i', strtotime($record['recorded_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <?php if (!empty($record['blood_pressure'])): ?>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">ضغط الدم</p>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($record['blood_pressure']) ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($record['heart_rate'])): ?>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">النبض</p>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($record['heart_rate']) ?> bpm</p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($record['temperature'])): ?>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">الحرارة</p>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($record['temperature']) ?> °C</p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($record['oxygen_saturation'])): ?>
                                    <div class="bg-gray-50 rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">الأكسجين</p>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($record['oxygen_saturation']) ?> %</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (!empty($record['notes'])): ?>
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-comment-medical ml-1 text-blue-600"></i>
                                        <strong>ملاحظات:</strong> <?= htmlspecialchars($record['notes']) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد سجلات صحية مسجلة</p>
                    <button onclick="openAddHealthRecordModal()" 
                            class="mt-4 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-plus ml-2"></i>
                        إضافة أول سجل صحي
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Health Record Modal -->
<div id="addHealthRecordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-white">إضافة سجل صحي جديد</h3>
            <button onclick="closeAddHealthRecordModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="healthRecordForm" method="POST" action="/sihat-al-haj/medical-team/add-health-record" class="p-6 space-y-6">
            <input type="hidden" name="pilgrim_id" value="<?= htmlspecialchars($patient['pilgrim_id'] ?? '') ?>">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ضغط الدم
                    </label>
                    <input type="text" name="blood_pressure" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 120/80">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        معدل النبض (bpm)
                    </label>
                    <input type="number" name="heart_rate" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 75">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        درجة الحرارة (°C)
                    </label>
                    <input type="number" step="0.1" name="temperature" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 37.5">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        تشبع الأكسجين (%)
                    </label>
                    <input type="number" name="oxygen_saturation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 98">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        سكر الدم (mg/dL)
                    </label>
                    <input type="number" name="blood_sugar" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 110">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        الوزن (kg)
                    </label>
                    <input type="number" step="0.1" name="weight" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           placeholder="مثال: 75.5">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ملاحظات
                </label>
                <textarea name="notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                          placeholder="أي ملاحظات أو تعليقات إضافية..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeAddHealthRecordModal()" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition">
                    إلغاء
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-save ml-2"></i>
                    حفظ السجل
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddHealthRecordModal() {
    document.getElementById('addHealthRecordModal').classList.remove('hidden');
}

function closeAddHealthRecordModal() {
    document.getElementById('addHealthRecordModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addHealthRecordModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddHealthRecordModal();
    }
});
</script>

</body>
</html>
