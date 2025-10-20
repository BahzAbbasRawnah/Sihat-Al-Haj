<?php
/**
 * Users Management - Modern Design
 */
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('user_management') ?></h1>
            <p class="text-white text-opacity-90"><?= __('manage_platform_users') ?></p>
        </div>
        
        <div class="flex gap-3 mt-4 md:mt-0">
            <a href="/sihat-al-haj/admin/users/create" class="bg-white text-primary px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition flex items-center gap-2">
                <i class="fas fa-user-plus"></i>
                <span><?= __('add_user') ?></span>
            </a>
            <button id="export-users" class="bg-white bg-opacity-20 text-white px-6 py-3 rounded-lg font-semibold hover:bg-opacity-30 transition flex items-center gap-2">
                <i class="fas fa-download"></i>
                <span><?= __('export') ?></span>
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-users text-2xl text-blue-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500"><?= __('total_users') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($pagination['total_items'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-user-check text-2xl text-green-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500"><?= __('active') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['active'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-yellow-100 rounded-lg">
                <i class="fas fa-user-clock text-2xl text-yellow-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500"><?= __('inactive') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['inactive'] ?? 0) ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center gap-4">
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-user-slash text-2xl text-red-600"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500"><?= __('suspended') ?></p>
                <p class="text-2xl font-bold text-gray-800"><?= number_format($stats['suspended'] ?? 0) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden">
    <div class="bg-gray-50 p-4 border-b">
        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-filter"></i>
            <?= __('filter_results') ?>
        </h2>
    </div>
    <div class="p-6">
        <form method="GET" action="/sihat-al-haj/admin/users" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2"><?= __('search') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input 
                        type="text" 
                        id="search" 
                        name="search" 
                        class="w-full pr-10 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" 
                        placeholder="<?= __('search_for_user') ?>"
                        value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                    >
                </div>
            </div>

            <!-- User Type -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2"><?= __('user_type') ?></label>
                <select id="type" name="type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value=""><?= __('all_types') ?></option>
                    <option value="pilgrim" <?= ($filters['type'] ?? '') === 'pilgrim' ? 'selected' : '' ?>><?= __('pilgrim') ?></option>
                    <option value="guide" <?= ($filters['type'] ?? '') === 'guide' ? 'selected' : '' ?>><?= __('guide') ?></option>
                    <option value="medical_personnel" <?= ($filters['type'] ?? '') === 'medical_personnel' ? 'selected' : '' ?>><?= __('medical_personnel') ?></option>
                    <option value="administrator" <?= ($filters['type'] ?? '') === 'administrator' ? 'selected' : '' ?>><?= __('administrator') ?></option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2"><?= __('status') ?></label>
                <select id="status" name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value=""><?= __('all_statuses') ?></option>
                    <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>><?= __('active') ?></option>
                    <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>><?= __('inactive') ?></option>
                    <option value="suspended" <?= ($filters['status'] ?? '') === 'suspended' ? 'selected' : '' ?>><?= __('suspended') ?></option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 bg-primary text-white px-6 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    <span><?= __('search') ?></span>
                </button>
                <a href="/sihat-al-haj/admin/users" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gray-50 p-4 border-b flex items-center justify-between">
        <h2 class="text-lg font-bold text-gray-800">
            <?= __('users_list') ?>
            <span class="text-sm font-normal text-gray-500">(<?= number_format($pagination['total_items'] ?? 0) ?> <?= __('user') ?>)</span>
        </h2>
        
        <!-- Bulk Actions -->
        <div class="flex items-center gap-2">
            <select id="bulk-action" class="px-4 py-2 border border-gray-300 rounded-lg text-sm" disabled>
                <option value=""><?= __('bulk_actions') ?></option>
                <option value="activate"><?= __('activate') ?></option>
                <option value="deactivate"><?= __('deactivate') ?></option>
                <option value="delete"><?= __('delete') ?></option>
            </select>
            <button id="apply-bulk-action" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-opacity-90 transition" disabled>
                <?= __('apply') ?>
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-6 py-4 text-right">
                        <input type="checkbox" id="select-all" class="rounded border-gray-300 text-primary focus:ring-primary">
                    </th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('user') ?></th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('email') ?></th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('type') ?></th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('status') ?></th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('registration_date') ?></th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="user-checkbox rounded border-gray-300 text-primary focus:ring-primary" value="<?= $user['id'] ?? '' ?>">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                                        <?= mb_substr($user['first_name_ar'] ?? $user['first_name_en'] ?? $user['full_name_ar'] ?? $user['full_name_en'] ?? 'م', 0, 1) ?>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800"><?= htmlspecialchars(getLocalizedField($user, 'first_name') . ' ' . getLocalizedField($user, 'last_name') ?: __('not_specified')) ?></p>
                                        <p class="text-xs text-gray-500">ID: <?= $user['id'] ?? 'N/A' ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($user['email'] ?? __('not_specified')) ?></td>
                            <td class="px-6 py-4">
                                <?php
                                $typeColors = [
                                    'pilgrim' => 'bg-blue-100 text-blue-700',
                                    'guide' => 'bg-green-100 text-green-700',
                                    'medical_personnel' => 'bg-purple-100 text-purple-700',
                                    'administrator' => 'bg-red-100 text-red-700'
                                ];
                                $typeLabels = [
                                    'pilgrim' => __('pilgrim'),
                                    'guide' => __('guide'),
                                    'medical_personnel' => __('medical_personnel'),
                                    'administrator' => __('administrator')
                                ];
                                $userType = $user['user_type'] ?? 'pilgrim';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $typeColors[$userType] ?? 'bg-gray-100 text-gray-700' ?>">
                                    <?= $typeLabels[$userType] ?? $userType ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-700',
                                    'inactive' => 'bg-gray-100 text-gray-700',
                                    'suspended' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'active' => __('active'),
                                    'inactive' => __('inactive'),
                                    'suspended' => __('suspended')
                                ];
                                $status = $user['status'] ?? 'inactive';
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $statusColors[$status] ?? 'bg-gray-100 text-gray-700' ?>">
                                    <?= $statusLabels[$status] ?? $status ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                <?= date('Y-m-d', strtotime($user['created_at'] ?? 'now')) ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (isset($user['id'])): ?>
                                    <div class="flex items-center gap-2">
                                        <a href="/sihat-al-haj/admin/users/edit/<?= $user['id'] ?>" class="text-blue-600 hover:text-blue-800 transition" title="<?= __('edit') ?>">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteUser(<?= $user['id'] ?>)" class="text-red-600 hover:text-red-800 transition" title="<?= __('delete') ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <a href="/sihat-al-haj/admin/users/view/<?= $user['id'] ?>" class="text-green-600 hover:text-green-800 transition" title="<?= __('view') ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm"><?= __('not_available') ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-3 text-gray-300"></i>
                            <p><?= __('no_users_found') ?></p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($users) && $pagination['total_pages'] > 1): ?>
        <div class="bg-gray-50 px-6 py-4 border-t flex items-center justify-between">
            <div class="text-sm text-gray-600">
                <?= __('showing') ?> <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?> 
                <?= __('to') ?> <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']) ?> 
                <?= __('of') ?> <?= $pagination['total_items'] ?> <?= __('user') ?>
            </div>
            
            <div class="flex gap-2">
                <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?= $pagination['current_page'] - 1 ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?><?= !empty($filters['type']) ? '&type=' . $filters['type'] : '' ?><?= !empty($filters['status']) ? '&status=' . $filters['status'] : '' ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                    <a href="?page=<?= $i ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?><?= !empty($filters['type']) ? '&type=' . $filters['type'] : '' ?><?= !empty($filters['status']) ? '&status=' . $filters['status'] : '' ?>" 
                       class="px-4 py-2 <?= $i === $pagination['current_page'] ? 'bg-primary text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?= $pagination['current_page'] + 1 ?><?= !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : '' ?><?= !empty($filters['type']) ? '&type=' . $filters['type'] : '' ?><?= !empty($filters['status']) ? '&status=' . $filters['status'] : '' ?>" 
                       class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
// Select all checkbox functionality
const selectAllCheckbox = document.getElementById('select-all');
const userCheckboxes = document.querySelectorAll('.user-checkbox');
const bulkActionSelect = document.getElementById('bulk-action');
const applyBulkActionBtn = document.getElementById('apply-bulk-action');

selectAllCheckbox?.addEventListener('change', function() {
    userCheckboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkActions();
});

userCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateBulkActions);
});

function updateBulkActions() {
    const checkedCount = document.querySelectorAll('.user-checkbox:checked').length;
    bulkActionSelect.disabled = checkedCount === 0;
    applyBulkActionBtn.disabled = checkedCount === 0;
}

// Delete user function
function deleteUser(userId) {
    if (confirm('<?= __('confirm_delete_user') ?>')) {
        fetch(`/sihat-al-haj/admin/users/delete/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '<?= __('delete_error') ?>');
            }
        })
        .catch(error => {
            alert('<?= __('connection_error') ?>');
        });
    }
}

// Export users
document.getElementById('export-users')?.addEventListener('click', function() {
    window.location.href = '/sihat-al-haj/admin/users/export';
});
</script>
