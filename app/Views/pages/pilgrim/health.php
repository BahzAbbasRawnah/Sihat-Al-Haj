<?php
$pageTitle = __('my_health');
$currentLang = getCurrentLanguage();
?>

<div class="relative z-10">
    <div class="flex flex-wrap items-start">
        <!-- Page Header -->
        <div class="w-full mb-6">
            <h1 class="text-3xl font-bold text-primary mb-2"><?= __('my_health') ?></h1>
            <p style="color: var(--text-secondary);"><?= __('personal_health_information') ?></p>
        </div>
        
        <!-- Recent Health Data -->
        <div class="w-full md:w-1/2 p-3">
            <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-primary"><?= __('latest_readings') ?></h2>
                    <i class="fas fa-chart-line text-blue-500 text-xl"></i>
                </div>
                <?php if (!empty($recentHealthData)): ?>
                    <div class="space-y-3">
                        <?php foreach ($recentHealthData as $data): ?>
                            <div class="flex justify-between items-center p-3 rounded" style="background-color: var(--bg-primary);">
                                <div>
                                    <p class="font-medium"><?= getLocalizedField($data, 'measurement_type') ?></p>
                                    <p class="text-sm" style="color: var(--text-secondary);"><?= formatLocalizedDateTime($data['recorded_at']) ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-primary"><?= htmlspecialchars($data['measurement_value']) ?></p>
                                    <p class="text-sm" style="color: var(--text-secondary);"><?= getLocalizedField($data, 'unit') ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <a href="<?= url('/pilgrim/health-data') ?>" class="btn-primary w-full text-center block">
                            <?= __('view_details') ?>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8" style="color: var(--text-secondary);">
                        <i class="fas fa-chart-line text-4xl mb-4"></i>
                        <p class="mb-4"><?= __('no_readings_yet') ?></p>
                        <a href="<?= url('/pilgrim/health-data') ?>" class="btn-primary"><?= __('add_reading') ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Chronic Diseases -->
        <div class="w-full md:w-1/2 p-3">
            <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-primary"><?= __('chronic_diseases') ?></h2>
                    <button onclick="openChronicDiseaseModal()" class="bg-primary text-white px-3 py-1 rounded-lg hover:bg-secondary transition text-sm">
                        <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                        <?= __('add') ?>
                    </button>
                </div>
                <?php if (!empty($chronicDiseases)): ?>
                    <div class="space-y-3">
                        <?php foreach ($chronicDiseases as $disease): ?>
                            <div class="p-3 rounded border-r-4 <?= $disease['risk_level'] == 'high' ? 'border-red-500' : ($disease['risk_level'] == 'medium' ? 'border-yellow-500' : 'border-green-500') ?>" style="background-color: var(--bg-primary);">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold"><?= getLocalizedField($disease, 'name') ?></h4>
                                    <span class="px-2 py-1 rounded text-xs <?= $disease['risk_level'] == 'high' ? 'bg-red-100 text-red-800' : ($disease['risk_level'] == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') ?>">
                                        <?= __('risk_' . $disease['risk_level']) ?>
                                    </span>
                                </div>
                                <?php $notes = getLocalizedField($disease, 'notes'); ?>
                                <?php if ($notes): ?>
                                    <p class="text-sm" style="color: var(--text-secondary);"><?= htmlspecialchars($notes) ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8" style="color: var(--text-secondary);">
                        <i class="fas fa-heart text-4xl mb-4 text-green-500"></i>
                        <p><?= __('no_chronic_diseases_recorded') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="w-full p-3">
            <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                <h2 class="text-xl font-semibold text-primary mb-4"><?= __('quick_actions') ?></h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="<?= url('/pilgrim/health-data') ?>" class="flex flex-col items-center p-4 rounded hover:bg-primary hover:text-white transition-colors" style="background-color: var(--bg-primary);">
                        <i class="fas fa-plus text-2xl mb-2 text-green-500"></i>
                        <span class="text-center font-medium"><?= __('add_reading') ?></span>
                    </a>
                    <a href="<?= url('/pilgrim/health-reports') ?>" class="flex flex-col items-center p-4 rounded hover:bg-primary hover:text-white transition-colors" style="background-color: var(--bg-primary);">
                        <i class="fas fa-file-medical text-2xl mb-2 text-blue-500"></i>
                        <span class="text-center font-medium"><?= __('health_reports') ?></span>
                    </a>
                    <a href="<?= url('/pilgrim/medical-requests') ?>" class="flex flex-col items-center p-4 rounded hover:bg-primary hover:text-white transition-colors" style="background-color: var(--bg-primary);">
                        <i class="fas fa-ambulance text-2xl mb-2 text-red-500"></i>
                        <span class="text-center font-medium"><?= __('request_medical_help') ?></span>
                    </a>
                    <a href="tel:997" class="flex flex-col items-center p-4 rounded hover:bg-primary hover:text-white transition-colors" style="background-color: var(--bg-primary);">
                        <i class="fas fa-phone text-2xl mb-2 text-red-600"></i>
                        <span class="text-center font-medium"><?= __('emergency') ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Chronic Disease Modal -->
<div id="chronicDiseaseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-primary"><?= __('add') ?> <?= __('chronic_diseases') ?></h3>
            <button onclick="closeChronicDiseaseModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="chronicDiseaseForm" class="p-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">اسم المرض *</label>
                <select name="disease_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">اختر المرض</option>
                    <option value="1">السكري - Diabetes</option>
                    <option value="2">ضغط الدم - Hypertension</option>
                    <option value="3">الربو - Asthma</option>
                    <option value="4">أمراض القلب - Heart Disease</option>
                    <option value="5">الكلى - Kidney Disease</option>
                    <option value="custom">مرض آخر - Other</option>
                </select>
            </div>
            
            <div id="customDiseaseFields" class="hidden space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المرض (عربي) *</label>
                    <input type="text" name="custom_name_ar" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="مثال: التهاب المفاصل">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم المرض (إنجليزي) *</label>
                    <input type="text" name="custom_name_en" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Example: Arthritis">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">مستوى الخطورة *</label>
                    <select name="custom_risk_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">اختر مستوى الخطورة</option>
                        <option value="low">منخفض</option>
                        <option value="medium">متوسط</option>
                        <option value="high">عالي</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">تاريخ التشخيص</label>
                <input type="date" name="diagnosed_at" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ملاحظات (عربي)</label>
                <textarea name="notes_ar" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="أي ملاحظات إضافية..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition font-medium">
                    <i class="fas fa-save <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('save_changes') ?>
                </button>
                <button type="button" onclick="closeChronicDiseaseModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition font-medium">
                    <?= __('cancel') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openChronicDiseaseModal() {
    document.getElementById('chronicDiseaseModal').classList.remove('hidden');
}

function closeChronicDiseaseModal() {
    document.getElementById('chronicDiseaseModal').classList.add('hidden');
    document.getElementById('chronicDiseaseForm').reset();
    document.getElementById('customDiseaseFields').classList.add('hidden');
}

// Show/hide custom disease fields
document.querySelector('select[name="disease_id"]').addEventListener('change', function() {
    const customFields = document.getElementById('customDiseaseFields');
    if (this.value === 'custom') {
        customFields.classList.remove('hidden');
        document.querySelector('input[name="custom_name_ar"]').required = true;
        document.querySelector('input[name="custom_name_en"]').required = true;
        document.querySelector('select[name="custom_risk_level"]').required = true;
    } else {
        customFields.classList.add('hidden');
        document.querySelector('input[name="custom_name_ar"]').required = false;
        document.querySelector('input[name="custom_name_en"]').required = false;
        document.querySelector('select[name="custom_risk_level"]').required = false;
    }
});

document.getElementById('chronicDiseaseForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch('/sihat-al-haj/pilgrim/add-chronic-disease', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('تم إضافة المرض المزمن بنجاح');
            closeChronicDiseaseModal();
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
document.getElementById('chronicDiseaseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeChronicDiseaseModal();
    }
});
</script>
