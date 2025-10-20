<?php
// Extract variables for the view
$pageTitle = $pageTitle ?? 'لوحة التحكم الطبية';
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
        <p class="text-gray-600">مراقبة ومتابعة الحالات الطبية للحجاج</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Urgent Requests -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-red-600">حالات طارئة</p>
                    <p class="text-2xl font-bold text-red-900"><?= $stats['urgent'] ?></p>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-clock text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-yellow-600">في الانتظار</p>
                    <p class="text-2xl font-bold text-yellow-900"><?= $stats['pending'] ?></p>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-user-md text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-blue-600">قيد المعالجة</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $stats['in_progress'] ?></p>
                </div>
            </div>
        </div>

        <!-- Completed Today -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-green-600">مكتملة اليوم</p>
                    <p class="text-2xl font-bold text-green-900"><?= $stats['completed_today'] ?></p>
                </div>
            </div>
        </div>


    </div>

    <!-- Team Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100 text-gray-600">
                    <i class="fas fa-users-cog text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">إجمالي الفرق</p>
                    <p class="text-2xl font-bold text-gray-900"><?= $team_stats['total'] ?></p>
                </div>
            </div>
        </div>
                <!-- Total Pilgrims -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-purple-600">إجمالي الحجاج</p>
                    <p class="text-2xl font-bold text-purple-900"><?= $stats['total_pilgrims'] ?></p>
                </div>
            </div>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-green-600">فرق متاحة</p>
                    <p class="text-2xl font-bold text-green-900"><?= $team_stats['available'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-ambulance text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-blue-600">في مهمة</p>
                    <p class="text-2xl font-bold text-blue-900"><?= $team_stats['on_mission'] ?? 0 ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <i class="fas fa-pause-circle text-xl"></i>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-yellow-600">في استراحة</p>
                    <p class="text-2xl font-bold text-yellow-900"><?= $team_stats['on_break'] ?? 0 ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="/medical/requests" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 ml-4">
                    <i class="fas fa-clipboard-list text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">إدارة الطلبات</h3>
                    <p class="text-sm text-gray-600">عرض ومتابعة الطلبات الطبية</p>
                </div>
            </div>
        </a>
        
        <a href="/medical/notifications" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 ml-4">
                    <i class="fas fa-bell text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">إرسال إشعارات</h3>
                    <p class="text-sm text-gray-600">إرسال تنبيهات للحجاج</p>
                </div>
            </div>
        </a>
        
        <a href="/medical/patients" class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 ml-4">
                    <i class="fas fa-user-injured text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">إدارة المرضى</h3>
                    <p class="text-sm text-gray-600">متابعة الحالات الصحية</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Recent Urgent Requests -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">الحالات الطارئة</h2>
        </div>
        <div class="p-6">
            <?php if (empty($urgent_requests)): ?>
                <p class="text-gray-500 text-center py-8">لا توجد حالات طارئة حالياً</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($urgent_requests as $request): ?>
                        <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-full ml-3">
                                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        <?= htmlspecialchars($request['first_name_ar'] . ' ' . $request['last_name_ar']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($request['description_ar']) ?></p>
                                    <p class="text-xs text-gray-500"><?= date('Y-m-d H:i', strtotime($request['requested_at'])) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    <?= $request['urgency_level'] === 'critical' ? 'حرجة' : 'عالية' ?>
                                </span>
                                <a href="/medical/requests/<?= $request['request_id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Recent Pending Requests -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">الطلبات الحديثة</h2>
                <a href="/medical/requests" class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض الكل</a>
            </div>
        </div>
        <div class="p-6">
            <?php if (empty($pending_requests)): ?>
                <p class="text-gray-500 text-center py-8">لا توجد طلبات جديدة</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach (array_slice($pending_requests, 0, 5) as $request): ?>
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-full ml-3">
                                    <i class="fas fa-user-injured text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">
                                        <?= htmlspecialchars($request['first_name_ar'] . ' ' . $request['last_name_ar']) ?>
                                    </h3>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($request['description_ar']) ?></p>
                                    <p class="text-xs text-gray-500"><?= date('Y-m-d H:i', strtotime($request['requested_at'])) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                    <?= $request['urgency_level'] ?>
                                </span>
                                <a href="/medical/requests/<?= $request['request_id'] ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">عرض</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>