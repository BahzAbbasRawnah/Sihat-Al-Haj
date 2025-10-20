<?php
$currentLang = getCurrentLanguage();
?>

<div class="main-content  min-h-screen relative" >
    <!-- Hero Content -->
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="text-center">
            <div class="mb-8">
                <?php if (!empty($hero) && isset($hero[0])): ?>
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 text-primary"><?= getLocalizedField($hero[0], 'title') ?: __('hero_title') ?></h1>
                    <h2 class="text-2xl md:text-4xl mb-6 text-gray-600"><?= getLocalizedField($hero[0], 'value_text') ?: __('hero_subtitle') ?></h2>
                <?php else: ?>
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 text-primary"><?= __('hero_title') ?></h1>
                    <h2 class="text-2xl md:text-4xl mb-6 text-gray-600"><?= __('hero_subtitle') ?></h2>
                <?php endif; ?>
            </div>
            
            <div class="max-w-2xl mx-auto mb-8">
                <?php if (!empty($hero) && isset($hero[0])): ?>
                    <p class="text-lg md:text-xl leading-relaxed text-gray-700"><?= getLocalizedField($hero[0], 'description') ?: __('hero_description') ?></p>
                <?php else: ?>
                    <p class="text-lg md:text-xl leading-relaxed text-gray-700"><?= __('hero_description') ?></p>
                <?php endif; ?>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?= url('/login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    <?= __('login') ?>
                </a>
                <a href="<?= url('/register') ?>" class="btn btn-secondary" style="color: white; border-color: white;">
                    <i class="fas fa-user-plus"></i>
                    <?= __('register') ?>
                </a>
            </div>
            
            <!-- Quick Access Features -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                <?php if (!empty($benefits)): ?>
                    <?php foreach (array_slice($benefits, 0, 3) as $benefit): ?>
                        <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                            <div class="text-4xl mb-4">
                                <i class="<?= $benefit['icon_name'] ?? 'fas fa-star' ?> text-primary"></i>
                            </div>
                            <h3 class="card-title text-primary"><?= getLocalizedField($benefit, 'title') ?></h3>
                            <p class="text-sm text-gray-600"><?= getLocalizedField($benefit, 'description') ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-shield-alt text-primary"></i>
                        </div>
                        <h3 class="card-title" style="color: var(--text-primary);"><?= __('feature_secure') ?></h3>
                        <p class="text-sm" style="color: var(--text-secondary);"><?= __('feature_secure_desc') ?></p>
                    </div>
                    
                    <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h3 class="card-title" style="color: var(--text-primary);"><?= __('feature_tracking') ?></h3>
                        <p class="text-sm" style="color: var(--text-secondary);"><?= __('feature_tracking_desc') ?></p>
                    </div>
                    
                    <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-heartbeat text-primary"></i>
                        </div>
                        <h3 class="card-title" style="color: var(--text-primary);"><?= __('feature_healthcare') ?></h3>
                        <p class="text-sm" style="color: var(--text-secondary);"><?= __('feature_healthcare_desc') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button for Emergency -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
            <i class="fas fa-phone text-xl"></i>
        </a>
        <div class="absolute bottom-20 right-0 bg-red-600 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 hover:opacity-100 transition-opacity duration-300">
            <?= __('emergency') ?>: 997
        </div>
    </div>
</div>