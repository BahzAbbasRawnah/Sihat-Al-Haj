<?php
$pageTitle = __('my_notifications');
$currentLang = getCurrentLanguage();
?>

<div class="relative z-10">
    <div class="flex flex-wrap items-start">
        <!-- Page Header -->
        <div class="w-full mb-6">
            <h1 class="text-3xl font-bold text-primary mb-2"><?= __('my_notifications') ?></h1>
            <p style="color: var(--text-secondary);"><?= __('all_notifications') ?></p>
        </div>
        
        <!-- Notifications -->
        <div class="w-full">
            <?php if (!empty($notifications)): ?>
                <div class="space-y-4">
                    <?php foreach ($notifications as $notification): ?>
                        <div class="card shadow-lg <?= $notification['is_read'] ? 'opacity-75' : '' ?>" style="background-color: var(--bg-secondary);">
                            <div class="flex items-start space-x-4 space-x-reverse">
                                <!-- Icon -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center
                                        <?php 
                                        switch($notification['category']) {
                                            case 'emergency': echo 'bg-red-100'; break;
                                            case 'health': echo 'bg-green-100'; break;
                                            case 'warning': echo 'bg-yellow-100'; break;
                                            case 'information': echo 'bg-blue-100'; break;
                                            default: echo 'bg-gray-100';
                                        }
                                        ?>">
                                        <i class="fas 
                                            <?php 
                                            if ($notification['icon_name']) {
                                                echo htmlspecialchars($notification['icon_name']);
                                            } else {
                                                switch($notification['category']) {
                                                    case 'emergency': echo 'fa-exclamation-triangle text-red-500'; break;
                                                    case 'health': echo 'fa-heartbeat text-green-500'; break;
                                                    case 'warning': echo 'fa-exclamation-circle text-yellow-500'; break;
                                                    case 'information': echo 'fa-info-circle text-blue-500'; break;
                                                    default: echo 'fa-bell text-gray-500';
                                                }
                                            }
                                            ?>"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold text-primary">
                                            <?= getLocalizedField($notification, 'title') ?>
                                        </h3>
                                        <div class="flex items-center space-x-2 space-x-reverse">
                                            <!-- Priority Badge -->
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                <?php 
                                                switch($notification['priority']) {
                                                    case 'urgent': echo 'bg-red-100 text-red-800'; break;
                                                    case 'high': echo 'bg-orange-100 text-orange-800'; break;
                                                    case 'normal': echo 'bg-blue-100 text-blue-800'; break;
                                                    case 'low': echo 'bg-gray-100 text-gray-800'; break;
                                                    default: echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= __('priority_' . $notification['priority']) ?>
                                            </span>
                                            <!-- Read Status -->
                                            <?php if (!$notification['is_read']): ?>
                                                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Message Content -->
                                    <div class="mb-3">
                                        <p class="text-gray-700"><?= nl2br(htmlspecialchars(getLocalizedField($notification, 'content'))) ?></p>
                                    </div>
                                    
                                    <!-- Action Button -->
                                    <?php if ($notification['action_url']): ?>
                                        <div class="mb-3">
                                            <a href="<?= htmlspecialchars($notification['action_url']) ?>" class="btn-secondary inline-block">
                                                <i class="fas fa-external-link-alt <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                                <?= __('view_details') ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Timestamp -->
                                    <div class="flex justify-between items-center text-sm" style="color: var(--text-secondary);">
                                        <span>
                                            <i class="fas fa-clock <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                            <?= formatLocalizedDateTime($notification['created_at']) ?>
                                        </span>
                                        <?php if ($notification['sent_at']): ?>
                                            <span>
                                                <i class="fas fa-paper-plane <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                                <?= __('sent') ?>: <?= formatLocalizedDateTime($notification['sent_at']) ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($notification['read_at']): ?>
                                            <span>
                                                <i class="fas fa-eye <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                                <?= __('read_at') ?>: <?= formatLocalizedDateTime($notification['read_at']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Load More Button -->
                <div class="text-center mt-8">
                    <button class="btn-secondary">
                        <i class="fas fa-chevron-down <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                        <?= __('load_more') ?>
                    </button>
                </div>
            <?php else: ?>
                <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                    <div class="text-center py-12" style="color: var(--text-secondary);">
                        <i class="fas fa-bell-slash text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold mb-2"><?= __('no_notifications') ?></h3>
                        <p class="mb-6"><?= __('no_notifications_received') ?></p>
                        <a href="<?= url('/pilgrim/dashboard') ?>" class="btn-primary">
                            <i class="fas fa-home <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= __('back_to_home') ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
