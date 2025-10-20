<?php
$pageTitle = __('my_health_data');
$currentLang = getCurrentLanguage();
?>

<!-- Health Data Content -->
<div class="relative z-10">
    <div class="flex flex-wrap items-start">
        <!-- Page Header -->
        <div class="w-full mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-primary mb-2"><?= __('my_health_data') ?></h1>
                    <p style="color: var(--text-secondary);"><?= __('health_monitoring') ?></p>
                </div>
                <button onclick="openHealthDataModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition font-medium">
                    <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('add_health_reading') ?>
                </button>
            </div>
        </div>
        
        <!-- Health Statistics -->
        <div class="w-full mb-6">
            <?php if (!empty($stats)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php foreach ($stats as $stat): ?>
                        <div class="dashboard-card p-4 border border-gray-200 shadow-lg rounded-lg border-r-4 border-blue-500">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-primary"><?= getLocalizedField($stat, 'measurement_type') ?></h3>
                                <i class="fas fa-chart-bar text-blue-500 text-xl"></i>
                            </div>
                            <div class="text-2xl font-bold text-primary mb-1">
                                <?= round($stat['avg_value'], 1) ?>
                                <span class="text-sm font-normal"><?= __('average') ?></span>
                            </div>
                            <div class="text-sm" style="color: var(--text-secondary);">
                                <?= __('range') ?>: <?= round($stat['min_value'], 1) ?> - <?= round($stat['max_value'], 1) ?><br>
                                <?= __('count') ?>: <?= $stat['count'] ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                    <div class="text-center py-8 text-secondary">
                        <i class="fas fa-chart-line text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2"><?= __('no_statistics') ?></h3>
                        <p><?= __('no_health_data_recorded') ?></p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Chronic Diseases -->
        <?php if (!empty($chronicDiseases)): ?>
        <div class="w-full mb-6">
            <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-primary"><?= __('chronic_diseases') ?></h2>
                    <i class="fas fa-exclamation-triangle text-orange-500 text-xl"></i>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($chronicDiseases as $disease): ?>
                        <div class="p-4 rounded border" style="background-color: var(--bg-primary); border-color: var(--border-color);">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold"><?= getLocalizedField($disease, 'name') ?></h4>
                                <span class="px-2 py-1 rounded text-xs <?= $disease['risk_level'] == 'high' ? 'bg-red-100 text-red-800' : ($disease['risk_level'] == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') ?>">
                                    <?= __('risk_' . $disease['risk_level']) ?>
                                </span>
                            </div>
                            <?php if ($disease['diagnosed_at']): ?>
                                <p class="text-sm mb-2" style="color: var(--text-secondary);"><?= __('diagnosis_date') ?>: <?= formatLocalizedDate($disease['diagnosed_at']) ?></p>
                            <?php endif; ?>
                            <?php $notes = getLocalizedField($disease, 'notes'); ?>
                            <?php if ($notes): ?>
                                <p class="text-sm"><?= htmlspecialchars($notes) ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Health Records Table -->
        <div class="w-full">
            <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-primary"><?= __('health_records') ?></h2>
                    <button class="btn-primary">
                        <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                        <?= __('add_health_reading') ?>
                    </button>
                </div>
                
                <?php if (!empty($healthRecords)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b" style="border-color: var(--border-color);">
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('date_time') ?></th>
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('measurement_type') ?></th>
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('value') ?></th>
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('unit') ?></th>
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('recorded_by') ?></th>
                                    <th class="<?= getTextAlignClass() ?> py-3 px-4 font-semibold text-primary"><?= __('details') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($healthRecords as $record): ?>
                                    <tr class="border-b hover:bg-opacity-50" style="border-color: var(--border-color);">
                                        <td class="py-3 px-4" style="color: var(--text-secondary);">
                                            <?= formatLocalizedDateTime($record['recorded_at']) ?>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= getLocalizedField($record, 'measurement_type') ?>
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 font-semibold text-primary">
                                            <?= htmlspecialchars($record['measurement_value']) ?>
                                        </td>
                                        <td class="py-3 px-4" style="color: var(--text-secondary);">
                                            <?= getLocalizedField($record, 'unit') ?>
                                        </td>
                                        <td class="py-3 px-4" style="color: var(--text-secondary);">
                                            <?php if ($record['recorded_by_user_id']): ?>
                                                <?= __('medical_staff') ?>
                                            <?php else: ?>
                                                <?= __('self_recorded') ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 px-4" style="color: var(--text-secondary);">
                                            <?= getLocalizedField($record, 'measurement_type') . ': ' . $record['measurement_value'] . ' ' . getLocalizedField($record, 'unit') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8" style="color: var(--text-secondary);">
                        <i class="fas fa-chart-line text-4xl mb-4"></i>
                        <h3 class="text-lg font-semibold mb-2"><?= __('no_health_data') ?></h3>
                        <p class="mb-4"><?= __('add_first_reading') ?></p>
                        <button class="btn-primary">
                            <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= __('add_health_reading') ?>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Health Data Modal -->
<div id="healthDataModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-primary"><?= __('add_health_reading') ?></h3>
            <button onclick="closeHealthDataModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="healthDataForm" class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع القياس *</label>
                <select id="measurementTypeSelect" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">اختر نوع القياس</option>
                    <option value="ضغط الدم|Blood Pressure|mmHg|mmHg">ضغط الدم</option>
                    <option value="سكر الدم|Blood Sugar|mg/dL|mg/dL">سكر الدم</option>
                    <option value="درجة الحرارة|Temperature|°C|°C">درجة الحرارة</option>
                    <option value="معدل ضربات القلب|Heart Rate|نبضة/دقيقة|bpm">معدل ضربات القلب</option>
                    <option value="تشبع الأكسجين|Oxygen Saturation|%|%">تشبع الأكسجين</option>
                    <option value="الوزن|Weight|كجم|kg">الوزن</option>
                    <option value="الطول|Height|سم|cm">الطول</option>
                    <option value="custom">أخرى</option>
                </select>
            </div>
            
            <input type="hidden" name="measurement_type_ar" id="measurement_type_ar">
            <input type="hidden" name="measurement_type_en" id="measurement_type_en">
            <input type="hidden" name="unit_ar" id="unit_ar">
            <input type="hidden" name="unit_en" id="unit_en">
            
            <div id="customMeasurementType" class="hidden space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع القياس (عربي) *</label>
                    <input type="text" id="custom_measurement_ar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="مثال: مستوى الكوليسترول">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">نوع القياس (إنجليزي) *</label>
                    <input type="text" id="custom_measurement_en" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Example: Cholesterol Level">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوحدة (عربي)</label>
                        <input type="text" id="custom_unit_ar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="مثال: ملغ/ديسيلتر">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">الوحدة (إنجليزي)</label>
                        <input type="text" id="custom_unit_en" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Example: mg/dL">
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">القيمة *</label>
                <input type="text" name="measurement_value" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="مثال: 120">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">التاريخ والوقت *</label>
                <input type="datetime-local" name="recorded_at" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="أي ملاحظات إضافية..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition font-medium">
                    <i class="fas fa-save <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('save_changes') ?>
                </button>
                <button type="button" onclick="closeHealthDataModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition font-medium">
                    <?= __('cancel') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openHealthDataModal() {
    document.getElementById('healthDataModal').classList.remove('hidden');
    // Set current date and time
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.querySelector('input[name="recorded_at"]').value = now.toISOString().slice(0, 16);
}

function closeHealthDataModal() {
    document.getElementById('healthDataModal').classList.add('hidden');
    document.getElementById('healthDataForm').reset();
    document.getElementById('customMeasurementType').classList.add('hidden');
}

// Handle measurement type selection
document.getElementById('measurementTypeSelect').addEventListener('change', function() {
    const customField = document.getElementById('customMeasurementType');
    
    if (this.value === 'custom') {
        customField.classList.remove('hidden');
        document.getElementById('custom_measurement_ar').required = true;
        document.getElementById('custom_measurement_en').required = true;
    } else if (this.value) {
        customField.classList.add('hidden');
        document.getElementById('custom_measurement_ar').required = false;
        document.getElementById('custom_measurement_en').required = false;
        
        // Parse the selected value (format: "ar|en|unit_ar|unit_en")
        const parts = this.value.split('|');
        document.getElementById('measurement_type_ar').value = parts[0];
        document.getElementById('measurement_type_en').value = parts[1];
        document.getElementById('unit_ar').value = parts[2];
        document.getElementById('unit_en').value = parts[3];
    } else {
        customField.classList.add('hidden');
    }
});

document.getElementById('healthDataForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Handle custom measurement type
    const selectValue = document.getElementById('measurementTypeSelect').value;
    if (selectValue === 'custom') {
        data.measurement_type_ar = document.getElementById('custom_measurement_ar').value;
        data.measurement_type_en = document.getElementById('custom_measurement_en').value;
        data.unit_ar = document.getElementById('custom_unit_ar').value;
        data.unit_en = document.getElementById('custom_unit_en').value;
    }
    
    try {
        const response = await fetch('/sihat-al-haj/pilgrim/add-health-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('تم إضافة القياس الصحي بنجاح');
            closeHealthDataModal();
            location.reload();
        } else {
            alert('حدث خطأ: ' + (result.message || 'حاول مرة أخرى'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    }
});

// Close modal when clicking outside
document.getElementById('healthDataModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeHealthDataModal();
    }
});
</script>
