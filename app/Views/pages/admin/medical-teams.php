<?php
/**
 * Medical Teams Management - Admin Panel
 * 
 * Manage medical teams with CRUD operations
 */

$pageTitle = 'إدارة الفرق الطبية';
?>

<!-- Page Header -->
<div class="mb-6 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-primary" data-ar="إدارة الفرق الطبية" data-en="Medical Teams Management"><?= __('medical_teams_management') ?></h2>
        <p class="text-text-secondary" data-ar="إضافة وتعديل وحذف الفرق الطبية" data-en="Add, edit, and delete medical teams"><?= __('manage_medical_teams_desc') ?></p>
    </div>
    <a href="/sihat-al-haj/admin/medical-teams/create" class="btn btn-primary">
        <i class="fas fa-plus mr-2"></i>
        <span data-ar="إضافة فريق طبي" data-en="Add Medical Team"><?= __('add_medical_team') ?></span>
    </a>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Teams -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-blue-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                <i class="fas fa-users-medical text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="إجمالي الفرق" data-en="Total Teams"><?= __('total_teams') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['total'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- Available Teams -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-green-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="متاح" data-en="Available"><?= __('available') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['available'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- On Mission -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-yellow-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                <i class="fas fa-ambulance text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="في مهمة" data-en="On Mission"><?= __('on_mission') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['on_mission'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
    
    <!-- On Break -->
    <div class="dashboard-card p-4 hover:shadow-lg transition-shadow duration-300 border-t-4 border-purple-500">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <i class="fas fa-pause-circle text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500" data-ar="في استراحة" data-en="On Break"><?= __('on_break') ?></p>
                <h3 class="text-2xl font-bold"><?= $stats['on_break'] ?? 0 ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="dashboard-card mb-6">
    <div class="p-4">
        <form method="GET" action="/sihat-al-haj/admin/medical-teams" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="بحث" data-en="Search"><?= __('search') ?></label>
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                       placeholder="<?= __('search_teams') ?>" 
                       class="form-input w-full">
            </div>
            
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium mb-2" data-ar="الحالة" data-en="Status"><?= __('status') ?></label>
                <select name="status" class="form-select w-full">
                    <option value="" data-ar="الكل" data-en="All"><?= __('all') ?></option>
                    <option value="available" <?= ($filters['status'] ?? '') === 'available' ? 'selected' : '' ?> data-ar="متاح" data-en="Available"><?= __('available') ?></option>
                    <option value="on_mission" <?= ($filters['status'] ?? '') === 'on_mission' ? 'selected' : '' ?> data-ar="في مهمة" data-en="On Mission"><?= __('on_mission') ?></option>
                    <option value="on_break" <?= ($filters['status'] ?? '') === 'on_break' ? 'selected' : '' ?> data-ar="في استراحة" data-en="On Break"><?= __('on_break') ?></option>
                    <option value="unavailable" <?= ($filters['status'] ?? '') === 'unavailable' ? 'selected' : '' ?> data-ar="غير متاح" data-en="Unavailable"><?= __('unavailable') ?></option>
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

<!-- Teams Table -->
<div class="dashboard-card">
    <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-transparent">
        <h3 class="font-bold text-primary" data-ar="الفرق الطبية" data-en="Medical Teams"><?= __('medical_teams') ?></h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الاسم" data-en="Name"><?= __('name') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الموقع الحالي" data-en="Current Location"><?= __('current_location') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="رقم الاتصال" data-en="Contact Number"><?= __('contact_number') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الحالة" data-en="Status"><?= __('status') ?></th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider" data-ar="الإجراءات" data-en="Actions"><?= __('actions') ?></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="/sihat-al-haj/admin/medical-teams/details/<?= $team['team_id'] ?>" class="hover:text-primary transition">
                                    <div class="text-sm font-medium text-gray-900 hover:text-primary">
                                        <?= htmlspecialchars($team['team_name_ar'] ?? '') ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= htmlspecialchars($team['team_name_en'] ?? '') ?>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($team['current_location_ar'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <?= htmlspecialchars($team['contact_number'] ?? '-') ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'available' => 'green',
                                    'on_mission' => 'yellow',
                                    'on_break' => 'purple',
                                    'unavailable' => 'red'
                                ];
                                $statusColor = $statusColors[$team['status']] ?? 'gray';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $statusColor ?>-100 text-<?= $statusColor ?>-800">
                                    <?= htmlspecialchars($team['status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/sihat-al-haj/admin/medical-teams/details/<?= $team['team_id'] ?>" class="text-green-600 hover:text-green-800 mr-3" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="/sihat-al-haj/admin/medical-teams/edit/<?= $team['team_id'] ?>" class="text-blue-600 hover:text-blue-900 mr-3" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="deleteTeam(<?= $team['team_id'] ?>)" class="text-red-600 hover:text-red-900" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p data-ar="لا توجد فرق طبية" data-en="No medical teams found"><?= __('no_teams_found') ?></p>
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
function deleteTeam(teamId) {
    if (confirm('<?= __('confirm_delete_team') ?>')) {
        fetch(`/sihat-al-haj/admin/medical-teams/delete/${teamId}`, {
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
                alert(data.message || '<?= __('error_deleting_team') ?>');
            }
        })
        .catch(error => {
            alert('<?= __('error_occurred') ?>');
        });
    }
}
</script>
