<?php
/**
 * Services Management - Admin Panel
 */

$pageTitle = 'إدارة الخدمات';
?>

<!-- Page Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-primary" data-ar="إدارة الخدمات" data-en="Services Management"><?= __('services_management') ?></h2>
        <p class="text-text-secondary" data-ar="إدارة الخدمات الرقمية المقدمة للحجاج" data-en="Manage digital services offered to pilgrims"><?= __('manage_services_desc') ?></p>
    </div>
    <a href="/sihat-al-haj/admin/services/create" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        <span data-ar="إضافة خدمة" data-en="Add Service"><?= __('add_service') ?></span>
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Total Services -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-concierge-bell text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="إجمالي الخدمات" data-en="Total Services"><?= __('total_services') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['total'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Active Services -->
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
    
    <!-- Inactive Services -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-red-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                <i class="fas fa-times-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="غير نشط" data-en="Inactive"><?= __('inactive') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['inactive'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="dashboard-card mb-6">
    <div class="p-4">
        <form method="GET" action="/sihat-al-haj/admin/services" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="بحث" data-en="Search"><?= __('search') ?></label>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                       placeholder="<?= __('search_services') ?>" 
                       class="form-input w-full">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="الحالة" data-en="Status"><?= __('status') ?></label>
                <select name="status" class="form-select w-full">
                    <option value="" data-ar="الكل" data-en="All"><?= __('all') ?></option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?> data-ar="نشط" data-en="Active"><?= __('active') ?></option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?> data-ar="غير نشط" data-en="Inactive"><?= __('inactive') ?></option>
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

<!-- Services Table -->
<div class="dashboard-card">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
        <h3 class="font-bold text-primary" data-ar="الخدمات" data-en="Services"><?= __('services') ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الأيقونة" data-en="Icon"><?= __('icon') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الاسم" data-en="Name"><?= __('name') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الوصف" data-en="Description"><?= __('description') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الحالة" data-en="Status"><?= __('status') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الإجراءات" data-en="Actions"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($services)): ?>
                    <?php foreach ($services as $service): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600">
                                    <i class="fas fa-<?= htmlspecialchars($service['icon_name'] ?? 'concierge-bell') ?>"></i>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($service['service_name_ar'] ?? '') ?>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <?= htmlspecialchars($service['service_name_en'] ?? '') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-md truncate">
                                    <?= htmlspecialchars($service['description_ar'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button onclick="toggleStatus(<?= $service['service_id'] ?>, <?= $service['is_active'] ?>)" 
                                        class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-colors <?= $service['is_active'] ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' ?>">
                                    <?= $service['is_active'] ? __('active') : __('inactive') ?>
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/sihat-al-haj/admin/services/edit/<?= $service['service_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="<?= __('edit') ?>">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteService(<?= $service['service_id'] ?>)" class="text-red-600 hover:text-red-900" title="<?= __('delete') ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p data-ar="لا توجد خدمات" data-en="No services found"><?= __('no_services_found') ?></p>
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
function toggleStatus(serviceId, currentStatus) {
    fetch(`/sihat-al-haj/admin/services/toggle-status/${serviceId}`, {
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
            alert(data.message || '<?= __('error_updating_status') ?>');
        }
    })
    .catch(error => {
        alert('<?= __('error_occurred') ?>');
    });
}

function deleteService(serviceId) {
    if (confirm('<?= __('confirm_delete_service') ?>')) {
        fetch(`/sihat-al-haj/admin/services/delete/${serviceId}`, {
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
                alert(data.message || '<?= __('error_deleting_service') ?>');
            }
        })
        .catch(error => {
            alert('<?= __('error_occurred') ?>');
        });
    }
}
</script>
