<?php
/**
 * Medical Team Details - Modern Design
 */
?>


<!-- Flash Messages -->
<?php if (isset($_SESSION['flash']['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-check-circle ml-2"></i>
        <span><?= $_SESSION['flash']['success'] ?></span>
    </div>
    <?php unset($_SESSION['flash']['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash']['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-exclamation-circle ml-2"></i>
        <span><?= $_SESSION['flash']['error'] ?></span>
    </div>
    <?php unset($_SESSION['flash']['error']); ?>
<?php endif; ?>

<!-- Team Information Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            معلومات الفريق
        </h2>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">الوصف بالعربية</p>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($team['description_ar'] ?? '-') ?></p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500 mb-1">الوصف بالإنجليزية</p>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($team['description_en'] ?? '-') ?></p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500 mb-1">الموقع الحالي</p>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($team['current_location_ar'] ?? '-') ?></p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500 mb-1">رقم الاتصال</p>
                <p class="font-semibold text-gray-800"><?= htmlspecialchars($team['contact_number'] ?? '-') ?></p>
            </div>
        </div>
        
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
            <a href="/sihat-al-haj/admin/medical-teams" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للقائمة
            </a>
            <a href="/sihat-al-haj/admin/medical-teams/edit/<?= $team['team_id'] ?>" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90 transition">
                <i class="fas fa-edit ml-2"></i>
                تعديل الفريق
            </a>
        </div>
    </div>
</div>

<!-- Team Members Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-primary to-secondary p-4 flex items-center justify-between">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-users"></i>
            أعضاء الفريق (<?= count($team_members ?? []) ?>)
        </h2>
        <button onclick="openAddMemberModal()" class="px-4 py-2 bg-white text-primary rounded-lg hover:bg-gray-50 transition font-semibold">
            <i class="fas fa-user-plus ml-2"></i>
            إضافة عضو
        </button>
    </div>
    
    <div class="p-6">
        <?php if (!empty($team_members)): ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد الإلكتروني</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الدور</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ الانضمام</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($team_members as $member): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-bold ml-3">
                                            <?= mb_substr($member['first_name_ar'] ?? 'م', 0, 1) ?>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-800">
                                                <?= htmlspecialchars(($member['first_name_ar'] ?? '') . ' ' . ($member['last_name_ar'] ?? '')) ?>
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                <?= htmlspecialchars(($member['first_name_en'] ?? '') . ' ' . ($member['last_name_en'] ?? '')) ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <?= htmlspecialchars($member['email'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                        <?= htmlspecialchars($member['role_in_team_ar'] ?? 'عضو') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                    <?= date('Y-m-d', strtotime($member['joined_at'] ?? 'now')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button onclick="editMember(<?= $member['team_member_id'] ?>, '<?= htmlspecialchars($member['role_in_team_ar'] ?? '') ?>', '<?= htmlspecialchars($member['role_in_team_en'] ?? '') ?>')" 
                                            class="text-primary hover:text-opacity-80 ml-3" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="removeMember(<?= $member['team_member_id'] ?>)" 
                                            class="text-red-600 hover:text-red-800" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-12">
                <i class="fas fa-users text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">لا يوجد أعضاء في هذا الفريق</p>
                <button onclick="openAddMemberModal()" class="mt-4 px-6 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition">
                    <i class="fas fa-user-plus ml-2"></i>
                    إضافة أول عضو
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Member Modal -->
<div id="addMemberModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="bg-gradient-to-r from-primary to-secondary p-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">إضافة عضو جديد</h3>
            <button onclick="closeAddMemberModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="addMemberForm" class="p-6 space-y-4">
            <div>
                <label for="member_user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    اختر المستخدم <span class="text-red-500">*</span>
                </label>
                <select id="member_user_id" name="user_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value="">اختر مستخدم</option>
                    <?php if (!empty($available_users)): ?>
                        <?php foreach ($available_users as $user): ?>
                            <option value="<?= $user['user_id'] ?>">
                                <?= htmlspecialchars(($user['first_name_ar'] ?? '') . ' ' . ($user['last_name_ar'] ?? '')) ?> 
                            
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="role_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الدور (عربي)
                    </label>
                    <input type="text" id="role_ar" name="role_ar" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="مثال: طبيب">
                </div>
                
                <div>
                    <label for="role_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الدور (English)
                    </label>
                    <input type="text" id="role_en" name="role_en" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="Example: Doctor">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeAddMemberModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition">
                    <i class="fas fa-plus ml-2"></i>
                    إضافة
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Member Modal -->
<div id="editMemberModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4">
        <div class="bg-gradient-to-r from-primary to-secondary p-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">تعديل دور العضو</h3>
            <button onclick="closeEditMemberModal()" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        
        <form id="editMemberForm" class="p-6 space-y-4">
            <input type="hidden" id="edit_member_id" name="member_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="edit_role_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        الدور (عربي)
                    </label>
                    <input type="text" id="edit_role_ar" name="role_ar" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
                
                <div>
                    <label for="edit_role_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        الدور (English)
                    </label>
                    <input type="text" id="edit_role_en" name="role_en" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditMemberModal()" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition">
                    <i class="fas fa-save ml-2"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Modal functions
function openAddMemberModal() {
    document.getElementById('addMemberModal').classList.remove('hidden');
}

function closeAddMemberModal() {
    document.getElementById('addMemberModal').classList.add('hidden');
    document.getElementById('addMemberForm').reset();
}

function openEditMemberModal() {
    document.getElementById('editMemberModal').classList.remove('hidden');
}

function closeEditMemberModal() {
    document.getElementById('editMemberModal').classList.add('hidden');
    document.getElementById('editMemberForm').reset();
}

// Add member
document.getElementById('addMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        user_id: formData.get('user_id'),
        role_ar: formData.get('role_ar'),
        role_en: formData.get('role_en')
    };
    
    fetch('/sihat-al-haj/admin/medical-teams/add-member/<?= $team['team_id'] ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء إضافة العضو');
        }
    })
    .catch(error => {
        alert('حدث خطأ في الاتصال');
    });
});

// Edit member
function editMember(memberId, roleAr, roleEn) {
    document.getElementById('edit_member_id').value = memberId;
    document.getElementById('edit_role_ar').value = roleAr;
    document.getElementById('edit_role_en').value = roleEn;
    openEditMemberModal();
}

document.getElementById('editMemberForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = {
        member_id: formData.get('member_id'),
        role_ar: formData.get('role_ar'),
        role_en: formData.get('role_en')
    };
    
    fetch('/sihat-al-haj/admin/medical-teams/update-member', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء تعديل العضو');
        }
    })
    .catch(error => {
        alert('حدث خطأ في الاتصال');
    });
});

// Remove member
function removeMember(memberId) {
    if (confirm('هل أنت متأكد من حذف هذا العضو من الفريق؟')) {
        console.log('Removing member:', memberId);
        
        fetch(`/sihat-al-haj/admin/medical-teams/remove-member/${memberId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert('تم حذف العضو بنجاح');
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف العضو');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال: ' + error.message);
        });
    }
}
</script>
