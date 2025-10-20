<?php
/**
 * Medical Personnel Management - Admin Panel
 * 
 * Manage medical personnel users
 */

$pageTitle = 'إدارة الطاقم الطبي';
?>

<!-- Page Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-primary" data-ar="إدارة الطاقم الطبي" data-en="Medical Personnel Management"><?= __('medical_personnel_management') ?></h2>
        <p class="text-text-secondary" data-ar="إدارة حسابات الطاقم الطبي" data-en="Manage medical personnel accounts"><?= __('manage_medical_personnel_desc') ?></p>
    </div>
    <a href="/admin/users/create?type=medical_personnel" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        <span data-ar="إضافة طاقم طبي" data-en="Add Medical Personnel"><?= __('add_medical_personnel') ?></span>
    </a>
</div>

<!-- Filters and Search -->
<div class="dashboard-card mb-6">
    <div class="p-4">
        <form method="GET" action="/admin/medical-personnel" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="بحث" data-en="Search"><?= __('search') ?></label>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                       placeholder="<?= __('search_by_name_email') ?>" 
                       class="form-input w-full">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="الحالة" data-en="Status"><?= __('status') ?></label>
                <select name="status" class="form-select w-full">
                    <option value="" data-ar="الكل" data-en="All"><?= __('all') ?></option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?> data-ar="نشط" data-en="Active"><?= __('active') ?></option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?> data-ar="غير نشط" data-en="Inactive"><?= __('inactive') ?></option>
                    <option value="suspended" <?= ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' ?> data-ar="موقوف" data-en="Suspended"><?= __('suspended') ?></option>
                </select>
            </div>
            
            <!-- Submit -->
            <div class="flex items-end">
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fas fa-search mr-2"></i>
                    <span data-ar="بحث" data-en="Search"><?= __('search') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Personnel Table -->
<div class="dashboard-card">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
        <h3 class="font-bold text-primary" data-ar="الطاقم الطبي" data-en="Medical Personnel"><?= __('medical_personnel') ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الاسم" data-en="Name"><?= __('name') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="البريد الإلكتروني" data-en="Email"><?= __('email') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="رقم الهاتف" data-en="Phone Number"><?= __('phone_number') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الجنس" data-en="Gender"><?= __('gender') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الحالة" data-en="Status"><?= __('status') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="تاريخ الإنشاء" data-en="Created At"><?= __('created_at') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الإجراءات" data-en="Actions"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <?php if (!empty($user['profile_image_url'])): ?>
                                            <img class="h-10 w-10 rounded-full" src="<?= htmlspecialchars($user['profile_image_url']) ?>" alt="">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold">
                                                <?= strtoupper(substr($user['first_name_ar'] ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mr-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($user['first_name_ar'] ?? '') ?> <?= htmlspecialchars($user['last_name_ar'] ?? '') ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars($user['first_name_en'] ?? '') ?> <?= htmlspecialchars($user['last_name_en'] ?? '') ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email'] ?? '-') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900"><?= htmlspecialchars($user['phone_number'] ?? '-') ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?php
                                    $genderLabels = [
                                        'male' => __('male'),
                                        'female' => __('female'),
                                        'other' => __('other')
                                    ];
                                    echo $genderLabels[$user['gender']] ?? '-';
                                    ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'active' => 'green',
                                    'inactive' => 'yellow',
                                    'suspended' => 'red'
                                ];
                                $statusColor = $statusColors[$user['status']] ?? 'gray';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                    <?= htmlspecialchars($user['status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('Y-m-d', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/admin/users/edit/<?= $user['user_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="<?= __('edit') ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteUser(<?= $user['user_id'] ?>)" class="text-red-600 hover:text-red-900" title="<?= __('delete') ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p data-ar="لا يوجد طاقم طبي" data-en="No medical personnel found"><?= __('no_personnel_found') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="p-4 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    <span data-ar="عرض" data-en="Showing"><?= __('showing') ?></span>
                    <span class="font-medium"><?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?></span>
                    <span data-ar="إلى" data-en="to"><?= __('to') ?></span>
                    <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']) ?></span>
                    <span data-ar="من" data-en="of"><?= __('of') ?></span>
                    <span class="font-medium"><?= $pagination['total_items'] ?></span>
                    <span data-ar="نتيجة" data-en="results"><?= __('results') ?></span>
                </div>
                
                <div class="flex gap-2">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?page=<?= $pagination['current_page'] - 1 ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?><?= !empty($filters['status']) ? '&status=' . urlencode($filters['status']) : '' ?>" 
                           class="btn btn-secondary">
                            <span data-ar="السابق" data-en="Previous"><?= __('previous') ?></span>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['current_page'] + 1 ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?><?= !empty($filters['status']) ? '&status=' . urlencode($filters['status']) : '' ?>" 
                           class="btn btn-primary">
                            <span data-ar="التالي" data-en="Next"><?= __('next') ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function deleteUser(userId) {
    if (confirm('<?= __('confirm_delete_user') ?>')) {
        fetch(`/admin/users/delete/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= __('error_deleting_user') ?>');
            }
        })
        .catch(error => {
            alert('<?= __('error_occurred') ?>');
        });
    }
}
</script>
