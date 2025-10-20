<?php
$pageTitle = __('my_medical_requests');
$currentLang = getCurrentLanguage();
?>

<div class="relative z-10">
    <div class="flex flex-wrap items-start">
        <!-- Page Header -->
        <div class="w-full mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-primary mb-2"><?= __('my_medical_requests') ?></h1>
                    <p style="color: var(--text-secondary);"><?= __('manage_medical_requests') ?></p>
                </div>
                <button onclick="openMedicalRequestModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition font-medium">
                    <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('create_new_request') ?>
                </button>
            </div>
        </div>
        
        <!-- Medical Requests -->
        <div class="w-full">
            <?php if (!empty($medicalRequests)): ?>
                <div class="space-y-4">
                    <?php foreach ($medicalRequests as $request): ?>
                        <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-primary mb-2">
                                        <?= getLocalizedField($request, 'request_type') ?>
                                    </h3>
                                    <div class="flex items-center space-x-4 space-x-reverse text-sm" style="color: var(--text-secondary);">
                                        <span><i class="fas fa-calendar <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= formatLocalizedDateTime($request['requested_at']) ?></span>
                                        <?php if ($request['team_name_ar']): ?>
                                            <span><i class="fas fa-users <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i><?= getLocalizedField($request, 'team_name') ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="flex flex-col items-end space-y-2">
                                    <span class="px-3 py-1 rounded-full text-sm font-medium
                                        <?php 
                                        switch($request['status']) {
                                            case 'pending': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'in_progress': echo 'bg-blue-100 text-blue-800'; break;
                                            case 'resolved': echo 'bg-green-100 text-green-800'; break;
                                            case 'cancelled': echo 'bg-red-100 text-red-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= __('status_' . $request['status']) ?>
                                    </span>
                                    <span class="px-2 py-1 rounded text-xs
                                        <?php 
                                        switch($request['urgency_level']) {
                                            case 'critical': echo 'bg-red-100 text-red-800'; break;
                                            case 'high': echo 'bg-orange-100 text-orange-800'; break;
                                            case 'medium': echo 'bg-yellow-100 text-yellow-800'; break;
                                            case 'low': echo 'bg-green-100 text-green-800'; break;
                                            default: echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= __('urgency_' . $request['urgency_level']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Request Description -->
                            <?php $description = getLocalizedField($request, 'description'); ?>
                            <?php if ($description): ?>
                                <div class="mb-4">
                                    <h4 class="font-semibold text-primary mb-2"><?= __('request_description') ?></h4>
                                    <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                        <p><?= nl2br(htmlspecialchars($description)) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Response Details -->
                            <?php $responseDetails = getLocalizedField($request, 'response_details'); ?>
                            <?php if ($responseDetails): ?>
                                <div class="mb-4">
                                    <h4 class="font-semibold text-primary mb-2"><?= __('response_details') ?></h4>
                                    <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                        <p><?= nl2br(htmlspecialchars($responseDetails)) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Outcome -->
                            <?php $outcome = getLocalizedField($request, 'outcome'); ?>
                            <?php if ($outcome): ?>
                                <div class="mb-4">
                                    <h4 class="font-semibold text-primary mb-2"><?= __('outcome') ?></h4>
                                    <div class="p-3 rounded" style="background-color: var(--bg-primary);">
                                        <p><?= nl2br(htmlspecialchars($outcome)) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Timeline -->
                            <div class="border-t pt-4" style="border-color: var(--border-color);">
                                <div class="flex justify-between text-sm" style="color: var(--text-secondary);">
                                    <span><?= __('request_date') ?>: <?= formatLocalizedDateTime($request['requested_at']) ?></span>
                                    <?php if ($request['resolved_at']): ?>
                                        <span><?= __('resolved_date') ?>: <?= formatLocalizedDateTime($request['resolved_at']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="dashboard-card p-6 border border-gray-200 shadow-lg rounded-lg">
                    <div class="text-center py-12 text-secondary">
                        <i class="fas fa-ambulance text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2"><?= __('no_medical_requests') ?></h3>
                        <p class="mb-6"><?= __('no_requests_sent_yet') ?></p>
                        <button onclick="openMedicalRequestModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition font-medium">
                            <i class="fas fa-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= __('send_new_request') ?>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Add Medical Request Modal -->
<div id="medicalRequestModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b sticky top-0 bg-white">
            <h3 class="text-xl font-bold text-primary"><?= __('send_medical_request') ?></h3>
            <button onclick="closeMedicalRequestModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="medicalRequestForm" class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع الطلب *</label>
                <select name="request_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">اختر نوع الطلب</option>
                    <option value="طلب إسعاف|Ambulance Request">طلب إسعاف</option>
                    <option value="استشارة طبية|Medical Consultation">استشارة طبية</option>
                    <option value="أدوية|Medication">طلب أدوية</option>
                    <option value="فحص طبي|Medical Checkup">فحص طبي</option>
                    <option value="حالة طارئة|Emergency">حالة طارئة</option>
                    <option value="أخرى|Other">أخرى</option>
                </select>
            </div>
            
            <input type="hidden" name="request_type_ar" id="request_type_ar">
            <input type="hidden" name="request_type_en" id="request_type_en">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">مستوى الأولوية *</label>
                <select name="urgency_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">اختر مستوى الأولوية</option>
                    <option value="low" class="text-green-600">● منخفض - Low</option>
                    <option value="medium" class="text-yellow-600">● متوسط - Medium</option>
                    <option value="high" class="text-orange-600">● عالي - High</option>
                    <option value="critical" class="text-red-600">● حرج - Critical</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الحالة والموقع (عربي) *</label>
                <textarea name="description_ar" required rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="صف حالتك الصحية بالتفصيل وحدد موقعك الحالي (مثال: مخيم عرفات - خيمة 123)&#10;&#10;مثال:&#10;- الحالة: ألم شديد في الصدر&#10;- الموقع: مخيم منى - خيمة رقم 456"></textarea>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">وصف الحالة والموقع (إنجليزي)</label>
                <textarea name="description_en" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="Describe your condition in detail and specify your current location&#10;&#10;Example:&#10;- Condition: Severe chest pain&#10;- Location: Mina Camp - Tent 456"></textarea>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 ml-2"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">ملاحظة هامة:</p>
                        <p>في حالات الطوارئ الحرجة، يرجى الاتصال فوراً على الرقم <strong class="font-bold">997</strong></p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-primary text-white px-4 py-3 rounded-lg hover:bg-secondary transition font-medium">
                    <i class="fas fa-paper-plane <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('submit_request') ?>
                </button>
                <button type="button" onclick="closeMedicalRequestModal()" class="flex-1 bg-gray-300 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-400 transition font-medium">
                    <?= __('cancel') ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openMedicalRequestModal() {
    document.getElementById('medicalRequestModal').classList.remove('hidden');
}

function closeMedicalRequestModal() {
    document.getElementById('medicalRequestModal').classList.add('hidden');
    document.getElementById('medicalRequestForm').reset();
}

// Handle request type selection
document.querySelector('select[name="request_type"]').addEventListener('change', function() {
    if (this.value) {
        const parts = this.value.split('|');
        document.getElementById('request_type_ar').value = parts[0];
        document.getElementById('request_type_en').value = parts[1];
    }
});

document.getElementById('medicalRequestForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    
    // Validate request type is selected
    if (!data.request_type_ar || !data.request_type_en) {
        alert('الرجاء اختيار نوع الطلب');
        return;
    }
    
    try {
        const response = await fetch('/sihat-al-haj/pilgrim/create-medical-request', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('تم إرسال الطلب بنجاح. سيتم التواصل معك قريباً');
            closeMedicalRequestModal();
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
document.getElementById('medicalRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMedicalRequestModal();
    }
});
</script>
