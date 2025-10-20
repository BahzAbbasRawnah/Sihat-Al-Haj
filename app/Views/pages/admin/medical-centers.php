<?php
/**
 * Medical Centers Management - Admin Panel
 * 
 * Manage medical centers with CRUD operations
 */

$pageTitle = 'إدارة المراكز الطبية';
?>

<!-- Page Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-primary" data-ar="إدارة المراكز الطبية" data-en="Medical Centers Management"><?= __('medical_centers_management') ?></h2>
        <p class="text-text-secondary" data-ar="إضافة وتعديل وحذف المراكز الطبية" data-en="Add, edit, and delete medical centers"><?= __('manage_medical_centers_desc') ?></p>
    </div>
    <a href="/sihat-al-haj/admin/medical-centers/create" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        <span data-ar="إضافة مركز طبي" data-en="Add Medical Center"><?= __('add_medical_center') ?></span>
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Centers -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-hospital text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="إجمالي المراكز" data-en="Total Centers"><?= __('total_centers') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['total'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Active Centers -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="نشط" data-en="Active"><?= __('active') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['active'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Inactive Centers -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <i class="fas fa-pause-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="غير نشط" data-en="Inactive"><?= __('inactive') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['inactive'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Full Centers -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                <i class="fas fa-exclamation-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="ممتلئ" data-en="Full"><?= __('full') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['full'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="dashboard-card mb-6">
    <div class="p-4">
        <form method="GET" action="/sihat-al-haj/admin/medical-centers" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="بحث" data-en="Search"><?= __('search') ?></label>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                       placeholder="<?= __('search_centers') ?>" 
                       class="form-input w-full">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="الحالة" data-en="Status"><?= __('status') ?></label>
                <select name="status" class="form-select w-full">
                    <option value="" data-ar="الكل" data-en="All"><?= __('all') ?></option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?> data-ar="نشط" data-en="Active"><?= __('active') ?></option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?> data-ar="غير نشط" data-en="Inactive"><?= __('inactive') ?></option>
                    <option value="full" <?= ($filters['status'] ?? '') === 'full' ? 'selected' : '' ?> data-ar="ممتلئ" data-en="Full"><?= __('full') ?></option>
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

<!-- Centers Table -->
<div class="dashboard-card">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
        <h3 class="font-bold text-primary" data-ar="المراكز الطبية" data-en="Medical Centers"><?= __('medical_centers') ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الاسم" data-en="Name"><?= __('name') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="العنوان" data-en="Address"><?= __('address') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="رقم الهاتف" data-en="Phone Number"><?= __('phone_number') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="ساعات العمل" data-en="Operating Hours"><?= __('operating_hours') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الحالة" data-en="Status"><?= __('status') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الإجراءات" data-en="Actions"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($centers)): ?>
                    <?php foreach ($centers as $center): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/sihat-al-haj/admin/medical-centers/details/<?= $center['center_id'] ?>" class="hover:text-primary transition">
                                    <div class="text-sm font-medium text-gray-900 hover:text-primary">
                                        <?= htmlspecialchars($center['name_ar'] ?? '') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($center['name_en'] ?? '') ?>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($center['address_ar'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($center['phone_number'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($center['operating_hours_ar'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'active' => 'green',
                                    'inactive' => 'yellow',
                                    'full' => 'red'
                                ];
                                $statusColor = $statusColors[$center['status']] ?? 'gray';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                    <?= htmlspecialchars($center['status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/sihat-al-haj/admin/medical-centers/details/<?= $center['center_id'] ?>" class="text-green-600 hover:text-green-800 mr-3" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/sihat-al-haj/admin/medical-centers/edit/<?= $center['center_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteCenter(<?= $center['center_id'] ?>)" class="text-red-600 hover:text-red-900" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p data-ar="لا توجد مراكز طبية" data-en="No medical centers found"><?= __('no_centers_found') ?></p>
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
function deleteCenter(centerId) {
    if (confirm('<?= __('confirm_delete_center') ?>')) {
        fetch(`/sihat-al-haj/admin/medical-centers/delete/${centerId}`, {
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
                alert(data.message || '<?= __('error_deleting_center') ?>');
            }
        })
        .catch(error => {
            alert('<?= __('error_occurred') ?>');
        });
    }
}
</script>
