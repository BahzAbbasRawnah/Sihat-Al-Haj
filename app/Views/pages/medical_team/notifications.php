<?php
$pageTitle = $pageTitle ?? 'إرسال الإشعارات';
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - صحة الحاج</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans">
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= $pageTitle ?></h1>
        <p class="text-gray-600">إرسال إشعارات وتنبيهات طبية للحجاج</p>
    </div>

    <!-- Notification Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">إرسال إشعار جديد</h2>
        
        <form id="notificationForm" class="space-y-6">
            <!-- Recipient Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نوع المستقبل</label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="recipient_type" value="all" class="mr-3" checked>
                        <div>
                            <div class="font-medium">جميع الحجاج</div>
                            <div class="text-sm text-gray-500">إرسال لجميع الحجاج المسجلين</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="recipient_type" value="group" class="mr-3">
                        <div>
                            <div class="font-medium">مجموعة محددة</div>
                            <div class="text-sm text-gray-500">إرسال لمجموعة من الحجاج</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="recipient_type" value="individual" class="mr-3">
                        <div>
                            <div class="font-medium">حاج محدد</div>
                            <div class="text-sm text-gray-500">إرسال لحاج واحد فقط</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Group Selection (hidden by default) -->
            <div id="groupSelection" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">اختر المجموعة</label>
                <select name="group_id" class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- اختر مجموعة --</option>
                    <?php if (isset($groups)): ?>
                        <?php foreach ($groups as $group): ?>
                        <option value="<?= $group['group_id'] ?>">
                            <?= htmlspecialchars($group['group_name_ar']) ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Individual Selection (hidden by default) -->
            <div id="individualSelection" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">اختر الحاج</label>
                <select name="pilgrim_id" class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- اختر حاج --</option>
                    <?php if (isset($pilgrims)): ?>
                        <?php foreach ($pilgrims as $pilgrim): ?>
                        <option value="<?= $pilgrim['user_id'] ?>">
                            <?= htmlspecialchars($pilgrim['first_name_ar'] . ' ' . $pilgrim['last_name_ar']) ?>
                        </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Message Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">عنوان الإشعار</label>
                    <input type="text" name="title_ar" required 
                           class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="أدخل عنوان الإشعار">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">أولوية الإشعار</label>
                    <select name="priority" class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="normal">عادي</option>
                        <option value="high">عالي</option>
                        <option value="urgent">عاجل</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">محتوى الإشعار</label>
                <textarea name="content_ar" rows="4" required
                          class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="أدخل محتوى الإشعار..."></textarea>
            </div>

            <!-- Quick Templates -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">قوالب سريعة</label>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                <?php if (!empty($templates)): ?>
                    <?php foreach ($templates as $template): ?>
                        <button type="button" class="p-3 text-right border border-gray-300 rounded-lg hover:bg-gray-50"
                            onclick="useTemplateFromDb(<?= htmlspecialchars(json_encode($template), ENT_QUOTES, 'UTF-8') ?>)">
                            <div class="font-medium text-blue-600"><?= htmlspecialchars($template['title_ar']) ?></div>
                            <div class="text-sm text-gray-500"><?= htmlspecialchars($template['description_ar'] ?? $template['content_ar'] ?? '') ?></div>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-400">لا توجد قوالب إشعار محفوظة</div>
                <?php endif; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">
                    <i class="fas fa-paper-plane ml-2"></i>
                    إرسال الإشعار
                </button>
            </div>
        </form>
    </div>

    <!-- Recent Notifications -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">الإشعارات المرسلة مؤخراً</h2>
        <div class="space-y-4">
        <?php if (!empty($notifications)): ?>
            <?php foreach ($notifications as $notification): ?>
                <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 rounded-full ml-3">
                            <i class="fas fa-bell text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900"><?= htmlspecialchars($notification['title_ar']) ?></h3>
                            <p class="text-sm text-gray-600">
                                <?php if ($notification['recipient_user_id']): ?>
                                    تم الإرسال لحاج
                                <?php elseif ($notification['recipient_group_id']): ?>
                                    تم الإرسال لمجموعة
                                <?php else: ?>
                                    تم الإرسال لجميع الحجاج
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-gray-500"><?= date('Y-m-d H:i', strtotime($notification['created_at'])) ?></p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                        تم الإرسال
                    </span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-gray-400">لا توجد إشعارات مرسلة مؤخراً</div>
        <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Handle recipient type changes
document.querySelectorAll('input[name="recipient_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const groupSelection = document.getElementById('groupSelection');
        const individualSelection = document.getElementById('individualSelection');
        
        groupSelection.classList.add('hidden');
        individualSelection.classList.add('hidden');
        
        if (this.value === 'group') {
            groupSelection.classList.remove('hidden');
        } else if (this.value === 'individual') {
            individualSelection.classList.remove('hidden');
        }
    });
});

// Use template from DB
function useTemplateFromDb(template) {
    const titleInput = document.querySelector('input[name="title_ar"]');
    const contentInput = document.querySelector('textarea[name="content_ar"]');
    const prioritySelect = document.querySelector('select[name="priority"]');
    titleInput.value = template.title_ar || '';
    contentInput.value = template.content_ar || '';
    if (template.priority) prioritySelect.value = template.priority;
}

// Handle form submission
document.getElementById('notificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i>جاري الإرسال...';
    submitBtn.disabled = true;
    fetch('/sihat-al-haj/medical-team/send-notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إرسال الإشعار بنجاح');
            this.reset();
            document.querySelector('input[name="recipient_type"][value="all"]').checked = true;
            document.getElementById('groupSelection').classList.add('hidden');
            document.getElementById('individualSelection').classList.add('hidden');
        } else {
            alert('فشل في إرسال الإشعار: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إرسال الإشعار');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});
</script>
</body>
</html>