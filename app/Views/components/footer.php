<footer class="bg-gray-900 text-white">
    <!-- Main Footer -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand Section -->
            <div class="lg:col-span-2">
                <div class="flex items-center space-x-3 rtl:space-x-reverse mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-saudi-green to-accent-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-heartbeat text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold"><?= $lang->get('platform.name') ?></h3>
                        <p class="text-gray-400 text-sm"><?= $lang->get('platform.tagline') ?></p>
                    </div>
                </div>
                <p class="text-gray-300 mb-6 max-w-md">
                    <?= $lang->get('platform.description') ?>
                </p>
                
                <!-- Saudi Vision 2030 Badge -->
                <div class="flex items-center space-x-3 rtl:space-x-reverse mb-6">
                    <div class="w-16 h-16 bg-saudi-green rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xs text-center leading-tight">2030<br>رؤية</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white"><?= $lang->get('platform.saudi_vision_2030') ?></p>
                        <p class="text-xs text-gray-400"><?= $lang->get('platform.digital_transformation') ?></p>
                    </div>
                </div>
                
                <!-- Social Links -->
                <div class="flex space-x-4 rtl:space-x-reverse">
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-twitter text-blue-400"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-facebook text-blue-600"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-instagram text-pink-500"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-gray-700 rounded-lg flex items-center justify-center transition-colors">
                        <i class="fab fa-linkedin text-blue-500"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4"><?= $lang->get('Quick Links') ?></h4>
                <ul class="space-y-2">
                    <li>
                        <a href="/" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-home <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('nav.home') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/about" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-info-circle <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('nav.about') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/services" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-concierge-bell <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('nav.services') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-envelope <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('nav.contact') ?>
                        </a>
                    </li>
                    <?php if ($isLoggedIn): ?>
                    <li>
                        <a href="/dashboard" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-tachometer-alt <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('nav.dashboard') ?>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            
            <!-- Support -->
            <div>
                <h4 class="text-lg font-semibold mb-4"><?= $lang->get('Support') ?></h4>
                <ul class="space-y-2">
                    <li>
                        <a href="/privacy" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-shield-alt <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('Privacy Policy') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/terms" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-file-contract <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('Terms of Service') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/help" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-question-circle <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('Help Center') ?>
                        </a>
                    </li>
                    <li>
                        <a href="/contact" class="text-gray-300 hover:text-white transition-colors flex items-center">
                            <i class="fas fa-headset <?= $isRTL ? 'ml-2' : 'mr-2' ?> text-sm"></i>
                            <?= $lang->get('Contact Support') ?>
                        </a>
                    </li>
                </ul>
                
                <!-- Emergency Contact -->
                <div class="mt-6 p-4 bg-red-900/20 border border-red-800 rounded-lg">
                    <h5 class="text-red-400 font-semibold mb-2 flex items-center">
                        <i class="fas fa-exclamation-triangle <?= $isRTL ? 'ml-2' : 'mr-2' ?>"></i>
                        <?= $lang->get('Emergency') ?>
                    </h5>
                    <p class="text-red-300 text-sm mb-2"><?= $lang->get('For medical emergencies, call:') ?></p>
                    <a href="tel:997" class="text-red-400 font-bold text-lg hover:text-red-300 transition-colors">
                        <i class="fas fa-phone <?= $isRTL ? 'ml-2' : 'mr-2' ?>"></i>
                        997
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bottom Footer -->
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4 rtl:space-x-reverse text-sm text-gray-400">
                    <span>&copy; <?= date('Y') ?> <?= $lang->get('platform.name') ?>. <?= $lang->get('All rights reserved.') ?></span>
                    <span class="hidden md:inline">|</span>
                    <span class="hidden md:inline"><?= $lang->get('Made with') ?> <i class="fas fa-heart text-red-500"></i> <?= $lang->get('in Saudi Arabia') ?></span>
                </div>
                
                <div class="flex items-center space-x-6 rtl:space-x-reverse text-sm">
                    <!-- Language Switcher -->
                    <a href="<?= $lang->getSwitchUrl() ?>" class="flex items-center space-x-2 rtl:space-x-reverse text-gray-400 hover:text-white transition-colors">
                        <i class="fas fa-globe"></i>
                        <span><?= $currentLang === 'ar' ? 'English' : 'العربية' ?></span>
                    </a>
                    
                    <!-- Version -->
                    <span class="text-gray-500 text-xs">v1.0.0</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="back-to-top" class="fixed bottom-32 <?= $isRTL ? 'left-4' : 'right-4' ?> bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 opacity-0 invisible z-40">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const backToTopBtn = document.getElementById('back-to-top');
    
    // Show/hide back to top button
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
            backToTopBtn.classList.remove('opacity-100', 'visible');
        }
    });
    
    // Smooth scroll to top
    backToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

