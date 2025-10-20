<?php 
/**
 * Medical Centers Page - Fully Multilingual
 * Displays medical centers with dynamic language support
 */

$currentLang = getCurrentLanguage();
$title = __('medical_centers');
?>

<div class="main-content min-h-screen relative page-content">
    <div class="relative z-10 py-12">
        <div class="container mx-auto px-4">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary"><?= __('medical_centers') ?></h1>
                <p class="text-lg md:text-xl <?= getTextAlignClass() ?> text-secondary">
                    <?= __('medical_centers_description') ?>
                </p>
            </div>
            
            <!-- Medical Centers Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <?php if (!empty($centers)): ?>
                    <?php foreach ($centers as $center): ?>
                        <div class="card shadow-lg hover:shadow-xl transition-all duration-300 bg-secondary">
                            <!-- Icon -->
                            <div class="text-center mb-4">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100">
                                    <i class="<?= htmlspecialchars($center['icon_name'] ?? 'fas fa-hospital') ?> text-3xl text-primary"></i>
                                </div>
                            </div>
                            
                            <!-- Center Name -->
                            <h3 class="text-xl font-bold mb-3 <?= getTextAlignClass() ?> text-primary">
                                <?= htmlspecialchars(getLocalizedField($center, 'name')) ?>
                            </h3>
                            
                            <!-- Address -->
                            <?php if (!empty($center['address_ar']) || !empty($center['address_en'])): ?>
                                <div class="flex items-start mb-3">
                                    <i class="fas fa-map-marker-alt text-primary mt-1 <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                    <p class="text-sm flex-1 <?= getTextAlignClass() ?> text-secondary">
                                        <?= htmlspecialchars(getLocalizedField($center, 'address')) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Phone Number -->
                            <?php if (!empty($center['phone_number'])): ?>
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-phone text-primary <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                    <a href="tel:<?= htmlspecialchars($center['phone_number']) ?>" 
                                       class="text-sm hover:text-primary transition-colors text-secondary">
                                        <?= htmlspecialchars($center['phone_number']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Operating Hours -->
                            <?php if (!empty($center['operating_hours_ar']) || !empty($center['operating_hours_en'])): ?>
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-clock text-primary <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                    <p class="text-sm text-secondary">
                                        <?= htmlspecialchars(getLocalizedField($center, 'operating_hours')) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Services Offered -->
                            <?php if (!empty($center['services_offered_ar']) || !empty($center['services_offered_en'])): ?>
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-xs font-semibold mb-2 text-primary">
                                        <?= __('services_offered') ?>:
                                    </p>
                                    <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                                        <?= htmlspecialchars(getLocalizedField($center, 'services_offered')) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Status Badge -->
                            <div class="mt-4">
                                <?php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'inactive' => 'bg-yellow-100 text-yellow-800',
                                    'full' => 'bg-red-100 text-red-800'
                                ];
                                $statusColor = $statusColors[$center['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold <?= $statusColor ?>">
                                    <?= __('status_' . $center['status']) ?>
                                </span>
                            </div>
                            
                            <!-- Map Link -->
                            <?php if (!empty($center['latitude']) && !empty($center['longitude'])): ?>
                                <div class="mt-4">
                                    <a href="https://www.google.com/maps?q=<?= $center['latitude'] ?>,<?= $center['longitude'] ?>" 
                                       target="_blank"
                                       class="btn btn-sm btn-primary w-full">
                                        <i class="fas fa-map-marked-alt <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                        <?= __('view_on_map') ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- No Centers Message -->
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-hospital text-6xl text-gray-300 mb-4"></i>
                        <p class="text-lg text-secondary">
                            <?= __('no_medical_centers_available') ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Back to Home Button -->
            <div class="text-center mt-12">
                <a href="<?= url('/') ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-<?= $currentLang === 'ar' ? 'right' : 'left' ?> <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('back_to_home') ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Emergency FAB -->
    <div class="fixed bottom-6 <?= $currentLang === 'ar' ? 'left-6' : 'right-6' ?> z-50">
        <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
            <i class="fas fa-phone text-xl"></i>
        </a>
    </div>
</div>

