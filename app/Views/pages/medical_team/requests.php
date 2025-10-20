<?php
$pageTitle = $pageTitle ?? 'الطلبات الطبية';
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
        <p class="text-gray-600">استقبال وإدارة طلبات المساعدة الطبية الواردة من الحجاج</p>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <div class="flex-grow">
                <select name="status" class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                    <option value="">جميع الطلبات</option>
                    <option value="pending" <?= $current_status === 'pending' ? 'selected' : '' ?>>قيد الانتظار</option>
                    <option value="in_progress" <?= $current_status === 'in_progress' ? 'selected' : '' ?>>قيد المعالجة</option>
                    <option value="resolved" <?= $current_status === 'resolved' ? 'selected' : '' ?>>مكتملة</option>
                    <option value="cancelled" <?= $current_status === 'cancelled' ? 'selected' : '' ?>>ملغية</option>
                </select>
            </div>
        </form>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحاج</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع الطلب</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الأولوية</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الفريق المعين</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">لا توجد طلبات</td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($requests as $request): 
                        $statusClass = match($request['status']) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'resolved' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-gray-100 text-gray-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                        
                        $statusLabel = match($request['status']) {
                            'pending' => 'قيد الانتظار',
                            'in_progress' => 'قيد المعالجة',
                            'resolved' => 'مكتملة',
                            'cancelled' => 'ملغية',
                            default => $request['status']
                        };
                        
                        $urgencyClass = match($request['urgency_level']) {
                            'critical' => 'bg-red-100 text-red-800',
                            'high' => 'bg-orange-100 text-orange-800',
                            'medium' => 'bg-yellow-100 text-yellow-800',
                            'low' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                        
                        $urgencyLabel = match($request['urgency_level']) {
                            'critical' => 'حرجة',
                            'high' => 'عالية',
                            'medium' => 'متوسطة',
                            'low' => 'منخفضة',
                            default => $request['urgency_level']
                        };
                    ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#<?= $request['request_id'] ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($request['first_name_ar'] . ' ' . $request['last_name_ar']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= htmlspecialchars($request['request_type_ar']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $urgencyClass ?>">
                                <?= $urgencyLabel ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= $request['assigned_team_name'] ? htmlspecialchars($request['assigned_team_name']) : '<span class="text-gray-400">غير معين</span>' ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= date('Y-m-d H:i', strtotime($request['requested_at'])) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="viewRequest(<?= htmlspecialchars(json_encode($request)) ?>)" 
                                        class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye ml-1"></i> عرض
                                </button>
                                
                                <?php if ($request['status'] === 'pending'): ?>
                                <button onclick="showAssignModal(<?= $request['request_id'] ?>)" 
                                        class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-user-md ml-1"></i> تعيين
                                </button>
                                <?php endif; ?>
                                
                                <?php if (in_array($request['status'], ['pending', 'in_progress'])): ?>
                                <button onclick="updateStatus(<?= $request['request_id'] ?>, 'resolved')" 
                                        class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-check ml-1"></i> إكمال
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Request Details Modal -->
<div id="requestModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-gray-900">تفاصيل الطلب الطبي</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="requestContent" class="mt-4">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="mt-6 flex justify-end">
            <button onclick="closeModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                إغلاق
            </button>
        </div>
    </div>
</div>

<!-- Assign Team Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-gray-900">تعيين فريق طبي</h3>
            <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="assignForm" class="mt-4">
            <input type="hidden" id="assignRequestId" name="request_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">اختر الفريق الطبي</label>
                <select name="team_id" class="w-full rounded-md border border-gray-300 p-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- اختر فريق --</option>
                    <?php foreach ($teams as $team): ?>
                    <option value="<?= $team['team_id'] ?>">
                        <?= htmlspecialchars($team['team_name_ar']) ?>
                        (<?= htmlspecialchars($team['current_location_ar'] ?: 'غير محدد') ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAssignModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    إلغاء
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    تعيين
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function viewRequest(request) {
    const modal = document.getElementById('requestModal');
    const content = document.getElementById('requestContent');
    
    const urgencyLabel = {
        'critical': 'حرجة',
        'high': 'عالية', 
        'medium': 'متوسطة',
        'low': 'منخفضة'
    }[request.urgency_level] || request.urgency_level;
    
    const statusLabel = {
        'pending': 'قيد الانتظار',
        'in_progress': 'قيد المعالجة',
        'resolved': 'مكتملة',
        'cancelled': 'ملغية'
    }[request.status] || request.status;
    
    content.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-bold mb-3">معلومات الطلب</h4>
                <div class="space-y-2">
                    <p><span class="font-medium">رقم الطلب:</span> #${request.request_id}</p>
                    <p><span class="font-medium">نوع الطلب:</span> ${request.request_type_ar}</p>
                    <p><span class="font-medium">الأولوية:</span> ${urgencyLabel}</p>
                    <p><span class="font-medium">الحالة:</span> ${statusLabel}</p>
                    <p><span class="font-medium">تاريخ الإنشاء:</span> ${new Date(request.requested_at).toLocaleString()}</p>
                </div>
            </div>
            <div>
                <h4 class="font-bold mb-3">معلومات الحاج</h4>
                <div class="space-y-2">
                    <p><span class="font-medium">الاسم:</span> ${request.first_name_ar} ${request.last_name_ar}</p>
                    <p><span class="font-medium">الفريق المعين:</span> ${request.assigned_team_name || 'غير معين'}</p>
                </div>
            </div>
        </div>
        <div class="mt-6">
            <h4 class="font-bold mb-3">وصف الحالة</h4>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p>${request.description_ar || 'لا يوجد وصف متاح'}</p>
            </div>
        </div>
    `;
    
    modal.classList.remove('hidden');
}

function showAssignModal(requestId) {
    document.getElementById('assignRequestId').value = requestId;
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('requestModal').classList.add('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

function updateStatus(requestId, status) {
    if (confirm('هل أنت متأكد من تغيير حالة الطلب؟')) {
        fetch('/sihat-al-haj/medical-team/update-request-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                request_id: requestId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('فشل في تحديث الحالة: ' + data.message);
            }
        });
    }
}

document.getElementById('assignForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = {
        request_id: formData.get('request_id'),
        team_id: formData.get('team_id'),
        status: 'in_progress'
    };
    fetch('/sihat-al-haj/medical-team/update-request-status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAssignModal();
            location.reload();
        } else {
            alert('فشل في تعيين الفريق: ' + data.message);
        }
    });
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const requestModal = document.getElementById('requestModal');
    const assignModal = document.getElementById('assignModal');
    
    if (event.target === requestModal) {
        closeModal();
    }
    if (event.target === assignModal) {
        closeAssignModal();
    }
});
</script>
</body>
</html>