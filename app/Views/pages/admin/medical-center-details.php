<?php
/**
 * Medical Center Details - Modern Design
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

<!-- Header Section -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-8 mb-8 text-white">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-4">
                <div class="bg-white bg-opacity-20 rounded-full p-4">
                    <i class="fas fa-hospital text-4xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1"><?= htmlspecialchars($center['name_ar'] ?? 'المركز الطبي') ?></h1>
                    <p class="text-white text-opacity-90 text-lg"><?= htmlspecialchars($center['name_en'] ?? '') ?></p>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="inline-block">
                <?php
                $statusColors = [
                    'active' => ['bg' => 'bg-green-500', 'text' => 'نشط', 'icon' => 'check-circle'],
                    'inactive' => ['bg' => 'bg-red-500', 'text' => 'غير نشط', 'icon' => 'times-circle'],
                    'full' => ['bg' => 'bg-yellow-500', 'text' => 'ممتلئ', 'icon' => 'exclamation-circle']
                ];
                $status = $center['status'] ?? 'inactive';
                $statusInfo = $statusColors[$status] ?? ['bg' => 'bg-gray-500', 'text' => $status, 'icon' => 'info-circle'];
                ?>
                <span class="<?= $statusInfo['bg'] ?> text-white px-4 py-2 rounded-full text-sm font-semibold inline-flex items-center gap-2">
                    <i class="fas fa-<?= $statusInfo['icon'] ?>"></i>
                    <?= $statusInfo['text'] ?>
                </span>
            </div>
        </div>
        
        <div class="hidden md:block">
            <i class="fas fa-<?= htmlspecialchars($center['icon_name'] ?? 'hospital') ?> text-8xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Contact Information Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-address-card"></i>
                معلومات الاتصال
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 rounded-lg p-3">
                    <i class="fas fa-phone text-blue-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">رقم الهاتف</p>
                    <p class="font-semibold text-gray-800"><?= htmlspecialchars($center['phone_number'] ?? '-') ?></p>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <div class="bg-green-100 rounded-lg p-3">
                    <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">الموقع</p>
                    <p class="text-sm text-gray-700"><?= htmlspecialchars($center['address_ar'] ?? '-') ?></p>
                </div>
            </div>
            
            <div class="flex items-start gap-3">
                <div class="bg-purple-100 rounded-lg p-3">
                    <i class="fas fa-globe text-purple-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">الإحداثيات</p>
                    <p class="text-sm text-gray-700">
                        <?= htmlspecialchars($center['latitude'] ?? '-') ?>, 
                        <?= htmlspecialchars($center['longitude'] ?? '-') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Operating Hours Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-clock"></i>
                ساعات العمل
            </h3>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-green-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-2">بالعربية</p>
                <p class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($center['operating_hours_ar'] ?? '-') ?></p>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-2">English</p>
                <p class="font-semibold text-gray-800 text-lg"><?= htmlspecialchars($center['operating_hours_en'] ?? '-') ?></p>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <i class="fas fa-cog"></i>
                الإجراءات
            </h3>
        </div>
        <div class="p-6 space-y-3">
            <a href="/sihat-al-haj/admin/medical-centers/edit/<?= $center['center_id'] ?>" 
               class="block w-full px-4 py-3 bg-primary text-white rounded-lg hover:bg-opacity-90 transition text-center font-semibold">
                <i class="fas fa-edit ml-2"></i>
                تعديل المركز
            </a>
            
            <a href="/sihat-al-haj/admin/medical-centers" 
               class="block w-full px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-center font-semibold">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة للقائمة
            </a>
            
            <button onclick="window.print()" 
                    class="block w-full px-4 py-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-center font-semibold">
                <i class="fas fa-print ml-2"></i>
                طباعة
            </button>
        </div>
    </div>
</div>

<!-- Addresses Section -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-primary to-secondary p-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="fas fa-map-marked-alt"></i>
            العناوين
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <i class="fas fa-flag text-primary"></i>
                    العنوان بالعربية
                </p>
                <p class="text-gray-800"><?= htmlspecialchars($center['address_ar'] ?? '-') ?></p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                    <i class="fas fa-flag text-primary"></i>
                    العنوان بالإنجليزية
                </p>
                <p class="text-gray-800"><?= htmlspecialchars($center['address_en'] ?? '-') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Services Offered Section -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4">
        <h3 class="text-lg font-bold text-white flex items-center gap-2">
            <i class="fas fa-stethoscope"></i>
            الخدمات المقدمة
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-orange-50 rounded-lg p-6">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-language text-orange-600"></i>
                    الخدمات بالعربية
                </p>
                <div class="text-gray-800 leading-relaxed">
                    <?= nl2br(htmlspecialchars($center['services_offered_ar'] ?? 'لا توجد خدمات محددة')) ?>
                </div>
            </div>
            
            <div class="bg-blue-50 rounded-lg p-6">
                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-language text-blue-600"></i>
                    Services in English
                </p>
                <div class="text-gray-800 leading-relaxed">
                    <?= nl2br(htmlspecialchars($center['services_offered_en'] ?? 'No services specified')) ?>
                </div>
            </div>
        </div>
    </div>
</div>
