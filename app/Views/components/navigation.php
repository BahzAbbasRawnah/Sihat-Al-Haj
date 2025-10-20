<!-- Unified Navigation Bar -->
<nav class="fixed top-0 w-full z-50 bg-bg-primary shadow-lg">
    <!-- Top Section: Logo and Action Icons -->
    <div class="flex justify-between items-center px-6 py-3 border-b border-gray-200">
        <!-- Logo Section -->
        <div class="flex items-center gap-4">
            <img src="/sihat-al-haj/public/assets/images/sihat-al-haj.svg" alt="صحة الحاج" class="h-16 w-16" onerror="this.style.display='none'">
            <h1 class="text-white text-2xl font-bold">صحة الحاج</h1>
        </div>
        
        <!-- Action Icons Section -->
        <div class="flex items-center gap-2">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" class="flex flex-col items-center justify-center px-4 py-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors group" id="theme-toggle">
                <i class="fa-solid fa-moon text-2xl text-white group-hover:text-black dark-icon hidden transition-colors"></i>
                <i class="fa-solid fa-sun text-2xl text-white group-hover:text-black light-icon transition-colors"></i>
                <span class="text-xs text-white group-hover:text-black mt-1 theme-text transition-colors" data-ar="وضع الإضاءة" data-en="Light Mode">وضع الإضاءة</span>
            </button>
            
            <!-- Language Switcher -->
            <button onclick="toggleLanguage()" class="flex flex-col items-center justify-center px-4 py-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors group" id="language-switcher">
                <i class="fa-solid fa-globe text-2xl text-white group-hover:text-black transition-colors"></i>
                <span class="text-xs text-white group-hover:text-black mt-1 language-text transition-colors" data-current="ar" data-ar="العربية" data-en="English">العربية</span>
            </button>
            
            <?php if ($isLoggedIn): ?>
                <!-- User Menu -->
                <div class="relative">
                    <button id="user-menu-btn" class="flex flex-col items-center justify-center px-4 py-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors group">
                        <i class="fa-regular fa-user text-2xl text-white group-hover:text-black transition-colors"></i>
                        <span class="text-xs text-white group-hover:text-black mt-1 whitespace-nowrap transition-colors"><?= $user['first_name_ar'] ?? 'الملف الشخصي' ?></span>
                    </button>
                    
                    <!-- User Dropdown -->
                    <div id="user-dropdown" class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden">
                        <div class="p-2">
                            <a href="<?= url('/pilgrim/dashboard') ?>" class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-tachometer-alt ml-3"></i>
                                لوحة التحكم
                            </a>
                            <a href="<?= url('/logout') ?>" class="flex items-center px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg">
                                <i class="fas fa-sign-out-alt ml-3"></i>
                                تسجيل خروج
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <a href="<?= url('/login') ?>" class="flex flex-col items-center justify-center px-4 py-2 hover:bg-white hover:bg-opacity-20 rounded-lg transition-colors group">
                    <i class="fa-regular fa-user text-2xl text-white group-hover:text-black transition-colors"></i>
                    <span class="text-xs text-white group-hover:text-black mt-1 whitespace-nowrap login-text transition-colors" data-ar="تسجيل دخول" data-en="Login">تسجيل دخول</span>
                </a>
            <?php endif; ?>
            
            <!-- Saudi Vision Logo -->
            <img src="/sihat-al-haj/public/assets/images/Saudi_Vision_2030_logo.svg.png" alt="saudi vision 2030" class="h-16 w-24 mr-2" onerror="this.style.display='none'">
        </div>
    </div>
    
    <!-- Bottom Section: Navigation Links -->
    <div class="bg-nav-transparent">
        <ul class="flex justify-center items-center gap-8 px-6 py-3">
            <li><a href="<?= url('/about') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="عن صحة الحاج" data-en="About Sihat Al-Hajj">عن صحة الحاج</a></li>
            <li><a href="<?= url('/guidelines') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="إرشادات الحج" data-en="Hajj Guidelines">إرشادات الحج</a></li>
            <li><a href="<?= url('/medical-centers') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="المراكز الطبية" data-en="Medical Centers">المراكز الطبية</a></li>
            <li><a href="<?= url('/medical-teams') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="الفرق الطبية" data-en="Medical Teams">الفرق الطبية</a></li>
            <li><a href="<?= url('/services') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="الخدمات" data-en="Services">الخدمات</a></li>
            <li><a href="<?= url('/faqs') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="الأسئلة الشائعة" data-en="FAQs">الأسئلة الشائعة</a></li>
            <li><a href="<?= url('/contact') ?>" class="text-white hover:text-black transition-colors font-semibold" data-ar="تواصل معنا" data-en="Contact Us">تواصل معنا</a></li>
        </ul>
    </div>
</nav>

<!-- Spacer to prevent content from going under fixed navbar -->
<div class="h-32"></div>

<script>
    // Theme toggle function
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        
        // Save theme preference
        localStorage.setItem('theme', newTheme);
        html.setAttribute('data-theme', newTheme);
        
        // Update UI elements
        updateThemeDisplay(newTheme);
    }
    
    // Update theme display
    function updateThemeDisplay(theme) {
        const darkIcon = document.querySelector('.dark-icon');
        const lightIcon = document.querySelector('.light-icon');
        const themeText = document.querySelector('.theme-text');
        
        if (theme === 'dark') {
            darkIcon.classList.remove('hidden');
            lightIcon.classList.add('hidden');
            themeText.setAttribute('data-ar', 'الوضع المظلم');
            themeText.setAttribute('data-en', 'Dark Mode');
        } else {
            darkIcon.classList.add('hidden');
            lightIcon.classList.remove('hidden');
            themeText.setAttribute('data-ar', 'وضع الإضاءة');
            themeText.setAttribute('data-en', 'Light Mode');
        }
        
        // Apply current language to the theme text
        applyLanguageToElement(themeText);
    }
    
    // Language toggle function
    function toggleLanguage() {
        const html = document.documentElement;
        const langSwitcher = document.getElementById('language-switcher');
        const currentLang = langSwitcher.querySelector('.language-text').getAttribute('data-current');
        const newLang = currentLang === 'ar' ? 'en' : 'ar';
        const newDir = newLang === 'ar' ? 'rtl' : 'ltr';
        
        // Save language preference
        localStorage.setItem('language', newLang);
        html.setAttribute('lang', newLang);
        html.setAttribute('dir', newDir);
        
        // Update UI elements with new language
        langSwitcher.querySelector('.language-text').setAttribute('data-current', newLang);
        
        // Apply translation to all elements with data-ar and data-en attributes
        applyTranslation();
    }
    
    // Apply translation to all elements with data-ar and data-en attributes
    function applyTranslation() {
        const currentLang = document.documentElement.getAttribute('lang') || 'ar';
        const elementsWithTranslation = document.querySelectorAll('[data-ar][data-en]');
        
        elementsWithTranslation.forEach(element => {
            applyLanguageToElement(element);
        });
    }
    
    // Apply language to specific element
    function applyLanguageToElement(element) {
        const currentLang = document.documentElement.getAttribute('lang') || 'ar';
        if (element.hasAttribute(`data-${currentLang}`)) {
            const translation = element.getAttribute(`data-${currentLang}`);
            element.innerText = translation;
        }
    }
    
    // Apply theme on load (in case the theme was changed)
    document.addEventListener('DOMContentLoaded', function() {
        const currentTheme = localStorage.getItem('theme') || 'light';
        updateThemeDisplay(currentTheme);
        
        // Also apply language
        applyTranslation();
        
        // User dropdown toggle
        const userMenuBtn = document.getElementById('user-menu-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
            
            // Close dropdowns when clicking outside
            document.addEventListener('click', function() {
                userDropdown.classList.add('hidden');
            });
        }
    });
</script>