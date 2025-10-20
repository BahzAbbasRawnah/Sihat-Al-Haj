<?php 
/**
 * Contact Page - Fully Multilingual
 */

$currentLang = getCurrentLanguage();
$title = __('contact_us');
?>
<div class="main-content min-h-screen relative page-content">
    <!-- Content -->
    <div class="relative z-10 py-12">
        <div class="text-center px-4 max-w-6xl mx-auto">
                <div class="mb-8">
                    <img src="<?= asset('images/sihat-al-haj.svg') ?>" alt="Sihat Al-Hajj Logo" class="h-32 w-32 mx-auto mb-6" onerror="this.style.display='none'">
                    <h1 class="text-5xl md:text-7xl font-bold mb-4 text-primary"><?= __('contact_us') ?></h1>
                    <h2 class="text-2xl md:text-4xl mb-6 text-secondary"><?= __('we_are_here_to_serve') ?></h2>
                </div>
                
                <div class="max-w-4xl mx-auto mb-8">
                    <p class="text-lg md:text-xl leading-relaxed mb-6 <?= getTextAlignClass() ?> text-primary">
                        <?= __('contact_description') ?>
                    </p>
                </div>
                
                <!-- Contact Methods -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-phone text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary"><?= __('phone') ?></h3>
                        <p class="text-sm text-secondary">+966 12 345 6789</p>
                    </div>
                    
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary"><?= __('email') ?></h3>
                        <p class="text-sm text-secondary">info@sihat-al-haj.sa</p>
                    </div>
                    
                    <div class="card shadow-lg bg-secondary">
                        <div class="text-4xl mb-4">
                            <i class="fas fa-map-marker-alt text-primary"></i>
                        </div>
                        <h3 class="card-title <?= getTextAlignClass() ?> text-primary"><?= __('address') ?></h3>
                        <p class="text-sm <?= getTextAlignClass() ?> text-secondary"><?= __('makkah_saudi_arabia') ?></p>
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
        
        <!-- Floating Action Button for Emergency -->
        <div class="fixed bottom-6 <?= $currentLang === 'ar' ? 'left-6' : 'right-6' ?> z-50">
            <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
                <i class="fas fa-phone text-xl"></i>
            </a>
            <div class="absolute bottom-20 <?= $currentLang === 'ar' ? 'left-0' : 'right-0' ?> bg-red-600 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 hover:opacity-100 transition-opacity duration-300">
                <span><?= __('emergency_997') ?></span>
            </div>
        </div>
    </div>