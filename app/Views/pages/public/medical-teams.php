<?php 
/**
 * Medical Teams Page - Fully Multilingual
 */

$currentLang = getCurrentLanguage();
$title = __('medical_teams');
?>
<div class="main-content min-h-screen relative">
    <div class="container mx-auto px-4 py-8 relative z-10">
        <!-- Page Header -->
        <div class="w-full mb-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-primary mb-4"><?= __('medical_teams') ?></h1>
            <p class="text-lg <?= getTextAlignClass() ?> text-secondary"><?= __('medical_teams_description') ?></p>
        </div>
        
        <!-- Medical Teams Grid -->
        <?php if (!empty($teams)): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($teams as $team): ?>
                    <div class="card shadow-lg hover:shadow-xl transition-shadow bg-secondary">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-primary <?= getTextAlignClass() ?>">
                                <?= htmlspecialchars(getLocalizedField($team, 'team_name')) ?>
                            </h3>
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-users-medical text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        
                        <?php if (!empty($team['description_ar']) || !empty($team['description_en'])): ?>
                            <div class="mb-4">
                                <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                                    <?= nl2br(htmlspecialchars(substr(getLocalizedField($team, 'description'), 0, 150))) ?>...
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($team['current_location_ar']) || !empty($team['current_location_en'])): ?>
                            <div class="mb-3">
                                <div class="flex items-start gap-2">
                                    <i class="fas fa-map-marker-alt text-gray-500 mt-1 <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                    <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                                        <?= htmlspecialchars(getLocalizedField($team, 'current_location')) ?>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($team['contact_number'])): ?>
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-phone text-gray-500 <?= $currentLang === 'ar' ? 'ml-1' : 'mr-1' ?>"></i>
                                    <a href="tel:<?= htmlspecialchars($team['contact_number']) ?>" class="text-blue-600 hover:underline">
                                        <?= htmlspecialchars($team['contact_number']) ?>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <?php
                            $statusColors = [
                                'available' => 'bg-green-100 text-green-800',
                                'on_mission' => 'bg-blue-100 text-blue-800',
                                'on_break' => 'bg-yellow-100 text-yellow-800',
                                'unavailable' => 'bg-red-100 text-red-800'
                            ];
                            $statusColor = $statusColors[$team['status']] ?? 'bg-gray-100 text-gray-800';
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-medium <?= $statusColor ?>">
                                <?= __('status_' . $team['status']) ?>
                            </span>
                            <?php if (!empty($team['contact_number']) && $team['status'] === 'available'): ?>
                                <a href="tel:<?= htmlspecialchars($team['contact_number']) ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-phone <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                    <span><?= __('call') ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="card shadow-lg bg-secondary">
                <div class="text-center py-12 text-secondary">
                    <i class="fas fa-users-medical text-6xl mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2"><?= __('no_medical_teams') ?></h3>
                    <p><?= __('no_teams_available') ?></p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
