<?php 
/**
 * About Page - Fully Multilingual
 */

$currentLang = getCurrentLanguage();
$title = __('about_us');
?>

<div class="main-content min-h-screen relative">
    <div class="relative z-10 py-12">
        <div class="text-center px-4 max-w-6xl mx-auto">
            <div class="mb-8">
                <img src="<?= asset('images/sihat-al-haj.svg') ?>" alt="Sihat Al-Hajj Logo" class="h-32 w-32 mx-auto mb-6" onerror="this.style.display='none'">
                <h1 class="text-5xl md:text-7xl font-bold mb-4 text-primary"><?= __('about_sihat_al_haj') ?></h1>
                <h2 class="text-2xl md:text-4xl mb-6 text-secondary"><?= __('smart_platform_for_pilgrims') ?></h2>
            </div>
            
            <div class="max-w-4xl mx-auto mb-8">
                <p class="text-lg md:text-xl leading-relaxed mb-6 <?= getTextAlignClass() ?> text-primary">
                    <?= __('about_description') ?>
                </p>
                
                <!-- Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-heartbeat text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary">
                            <?= __('healthcare') ?>
                        </h3>
                        <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                            <?= __('comprehensive_medical_services') ?>
                        </p>
                    </div>
                    
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary">
                            <?= __('advanced_tracking') ?>
                        </h3>
                        <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                            <?= __('location_tracking_guidance') ?>
                        </p>
                    </div>
                    
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-shield-alt text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary">
                            <?= __('safe_reliable') ?>
                        </h3>
                        <p class="text-sm <?= getTextAlignClass() ?> text-secondary">
                            <?= __('certified_campaigns') ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mt-8">
                <a href="<?= url('/login') ?>" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <span><?= __('login') ?></span>
                </a>
                <a href="<?= url('/register') ?>" class="btn btn-secondary">
                    <i class="fas fa-user-plus <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <span><?= __('register') ?></span>
                </a>
            </div>
        </div>
    </div>
</div>
