<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/sihat-al-haj/config/database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/sihat-al-haj/app/Core/Session.php';

$session = new \App\Core\Session();
$user = $session->get('user');
$userName = $user['name'] ?? 'فريق طبي';
$firstLetter = mb_substr($userName, 0, 1, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="صحة الحاج - لوحة تحكم الفريق الطبي">
    <title>صحة الحاج - الفريق الطبي</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: 'var(--primary)',
                        secondary: 'var(--secondary)',
                        'text-primary': 'var(--text-primary)',
                        'text-secondary': 'var(--text-secondary)',
                        'bg-primary': 'var(--bg-primary)',
                        'bg-secondary': 'var(--bg-secondary)',
                        'nav-bg': 'var(--nav-bg)',
                        'nav-text': 'var(--nav-text)',
                        'sidebar-bg': 'var(--sidebar-bg)',
                        'sidebar-text': 'var(--sidebar-text)',
                        'sidebar-active': 'var(--sidebar-active)'
                    }
                }
            }
        }
    </script>
    
    <style>
        :root {
            --primary: #10b981;
            --secondary: #059669;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --bg-primary: #ffffff;
            --bg-secondary: #f9fafb;
            --nav-bg: #065f46;
            --nav-text: #ffffff;
            --sidebar-bg: #047857;
            --sidebar-text: #ffffff;
            --sidebar-active: #10b981;
        }
        
        [data-theme="dark"] {
            --primary: #10b981;
            --secondary: #059669;
            --text-primary: #f9fafb;
            --text-secondary: #d1d5db;
            --bg-primary: #1f2937;
            --bg-secondary: #111827;
            --nav-bg: #047857;
            --nav-text: #ffffff;
            --sidebar-bg: #065f46;
            --sidebar-text: #ffffff;
            --sidebar-active: #10b981;
        }
        
        .dashboard-card {
            background: var(--bg-primary);
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        
        [data-theme="dark"] .dashboard-card {
            border-color: #374151;
        }
    </style>
    
    <!-- Theme Script -->
    <script>
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        
        function applyLanguage() {
            const savedLang = localStorage.getItem('language') || 'ar';
            const dir = savedLang === 'ar' ? 'rtl' : 'ltr';
            document.documentElement.setAttribute('lang', savedLang);
            document.documentElement.setAttribute('dir', dir);
        }
        
        applyTheme();
        applyLanguage();
    </script>
</head>
<body class="font-sans bg-bg-secondary text-text-primary min-h-screen flex flex-col">
    <!-- Top Navigation Bar -->
    <header class="bg-nav-bg text-nav-text shadow-md z-50">
        <div class="container mx-auto">
            <div class="flex items-center justify-between py-3 px-4">
                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-button" class="lg:hidden text-nav-text focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- Logo and Title -->
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-primary rounded-full flex items-center justify-center text-white ml-2">
                        <i class="fas fa-heartbeat"></i>
                    </div>
                    <h1 class="text-xl font-bold">صحة الحاج | الفريق الطبي</h1>
                </div>
                
                <!-- Right Navigation Items -->
                <div class="flex items-center space-x-4 space-x-reverse">
                    <!-- Theme Toggle -->
                    <button onclick="toggleTheme()" class="flex items-center justify-center h-10 w-10 rounded-full hover:bg-opacity-20 hover:bg-white transition-colors" id="theme-toggle">
                        <i class="fa-solid fa-moon text-xl dark-icon hidden"></i>
                        <i class="fa-solid fa-sun text-xl light-icon"></i>
                    </button>
                    
                    <!-- Language Switcher -->
                    <button onclick="toggleLanguage()" class="flex items-center justify-center h-10 w-10 rounded-full hover:bg-opacity-20 hover:bg-white transition-colors" id="language-switcher">
                        <i class="fa-solid fa-globe text-xl"></i>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 space-x-reverse focus:outline-none user-menu-button">
                            <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">
                                <?php echo $firstLetter; ?>
                            </div>
                            <?php echo htmlspecialchars($userName); ?>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute left-0 mt-2 w-48 bg-bg-primary rounded-lg shadow-lg py-2 z-50 hidden user-dropdown-menu">
                            <a href="/sihat-al-haj/medical-team/profile" class="block px-4 py-2 text-text-primary hover:bg-bg-secondary transition-colors">
                                <i class="fas fa-user-circle ml-2"></i>
                                <span data-ar="الملف الشخصي" data-en="Profile">الملف الشخصي</span>
                            </a>
                            <a href="#" class="block px-4 py-2 text-text-primary hover:bg-bg-secondary transition-colors">
                                <i class="fas fa-cog ml-2"></i>
                                <span data-ar="الإعدادات" data-en="Settings">الإعدادات</span>
                            </a>
                            <div class="border-t border-gray-200 my-1"></div>
                            <a href="/sihat-al-haj/logout" class="block px-4 py-2 text-red-600 hover:bg-bg-secondary transition-colors">
                                <i class="fas fa-sign-out-alt ml-2"></i>
                                <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-nav-bg text-nav-text h-full shadow-lg transition-all duration-300 lg:translate-x-0 fixed lg:static z-40 right-0 transform translate-x-full">
            <div class="h-full flex flex-col">
                <!-- User Info -->
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center space-x-3 space-x-reverse">
                        <div class="h-12 w-12 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xl">
                            <?php echo $firstLetter; ?>
                        </div>
                        <div>
                            <h3 class="font-bold text-white"><?php echo htmlspecialchars($userName); ?></h3>
                            <p class="text-xs text-gray-300" data-ar="فريق طبي" data-en="Medical Team">فريق طبي</p>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Menu -->
                <nav class="flex-1 py-4 overflow-y-auto scrollbar-thin">
                    <ul class="space-y-1 px-2">
                        <!-- Dashboard -->
                        <li>
                            <a href="/sihat-al-haj/medical-team/medical-dashboard" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-tachometer-alt w-5 text-center"></i>
                                <span data-ar="لوحة المعلومات" data-en="Dashboard">لوحة المعلومات</span>
                            </a>
                        </li>
                        
                        <!-- Medical Team Section -->
                        <li class="pt-4">
                            <p class="px-4 text-xs text-gray-400 font-semibold mb-2" data-ar="الفريق الطبي" data-en="Medical Team">الفريق الطبي</p>
                            
                            <a href="/sihat-al-haj/medical-team/requests" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-ambulance w-5 text-center"></i>
                                <span data-ar="طلبات المساعدة" data-en="Assistance Requests">طلبات المساعدة</span>
                            </a>
                            
                            <a href="/sihat-al-haj/medical-team/patients" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-users w-5 text-center"></i>
                                <span data-ar="المرضى" data-en="Patients">المرضى</span>
                            </a>
                            
                            <a href="/sihat-al-haj/medical-team/notifications" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-bell w-5 text-center"></i>
                                <span data-ar="الإشعارات" data-en="Notifications">الإشعارات</span>
                            </a>
                        </li>
                        
                        <!-- Account Section -->
                        <li class="pt-4">
                            <p class="px-4 text-xs text-gray-400 font-semibold mb-2" data-ar="الحساب" data-en="Account">الحساب</p>
                            
                            <a href="/sihat-al-haj/medical-team/profile" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-user w-5 text-center"></i>
                                <span data-ar="الملف الشخصي" data-en="Profile">الملف الشخصي</span>
                            </a>
                            
                            <a href="/sihat-al-haj/logout" class="flex items-center space-x-3 space-x-reverse px-4 py-3 rounded-lg text-nav-text hover:bg-primary hover:bg-opacity-20 transition-colors">
                                <i class="fas fa-sign-out-alt w-5 text-center"></i>
                                <span data-ar="تسجيل الخروج" data-en="Logout">تسجيل الخروج</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Footer -->
                <div class="p-4 border-t border-gray-700 text-center">
                    <p class="text-xs text-gray-400" data-ar="صحة الحاج &copy; 2025" data-en="Sihat Al-Hajj &copy; 2025">صحة الحاج &copy; 2025</p>
                </div>
            </div>
        </aside>
        
        <!-- Overlay for mobile sidebar -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
        
        <!-- Main Content Area -->
        <main class="flex-1 p-6 overflow-auto">
            <!-- Content will be injected here -->
            <?php if (isset($content)) echo $content; ?>
        </main>
    </div>
    
    <script>
        // Theme toggle function
        function toggleTheme() {
            const currentTheme = localStorage.getItem('theme') || 'light';
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            localStorage.setItem('theme', newTheme);
            document.documentElement.setAttribute('data-theme', newTheme);
            
            // Update icons
            const darkIcon = document.querySelector('.dark-icon');
            const lightIcon = document.querySelector('.light-icon');
            
            if (newTheme === 'dark') {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            } else {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            }
        }
        
        // Language toggle function
        function toggleLanguage() {
            const currentLang = localStorage.getItem('language') || 'ar';
            const newLang = currentLang === 'ar' ? 'en' : 'ar';
            const newDir = newLang === 'ar' ? 'rtl' : 'ltr';
            
            localStorage.setItem('language', newLang);
            document.documentElement.setAttribute('lang', newLang);
            document.documentElement.setAttribute('dir', newDir);
            
            // Update text content based on data attributes
            document.querySelectorAll('[data-ar][data-en]').forEach(el => {
                el.textContent = newLang === 'ar' ? el.getAttribute('data-ar') : el.getAttribute('data-en');
            });
        }
        
        // Initialize theme and language on load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial theme icon
            const currentTheme = localStorage.getItem('theme') || 'light';
            const darkIcon = document.querySelector('.dark-icon');
            const lightIcon = document.querySelector('.light-icon');
            
            if (currentTheme === 'dark') {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
            
            // Update all text based on current language
            const currentLang = localStorage.getItem('language') || 'ar';
            document.querySelectorAll('[data-ar][data-en]').forEach(el => {
                el.textContent = currentLang === 'ar' ? el.getAttribute('data-ar') : el.getAttribute('data-en');
            });
            
            // User dropdown menu behavior
            const userMenuButton = document.querySelector('.user-menu-button');
            const userDropdownMenu = document.querySelector('.user-dropdown-menu');
            
            if (userMenuButton && userDropdownMenu) {
                let isMenuOpen = false;
                
                userMenuButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    isMenuOpen = !isMenuOpen;
                    
                    if (isMenuOpen) {
                        userDropdownMenu.classList.remove('hidden');
                    } else {
                        userDropdownMenu.classList.add('hidden');
                    }
                });
                
                // Close when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                        userDropdownMenu.classList.add('hidden');
                        isMenuOpen = false;
                    }
                });
            }
            
            // Toggle sidebar for mobile
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            
            function toggleSidebar() {
                sidebar.classList.toggle('translate-x-full');
                sidebar.classList.toggle('translate-x-0');
                sidebarOverlay.classList.toggle('hidden');
            }
            
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', toggleSidebar);
            }
            
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleSidebar);
            }
            
            // Highlight current page in sidebar
            const currentPath = window.location.pathname;
            document.querySelectorAll('#sidebar a').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('bg-primary', 'bg-opacity-20');
                }
            });
        });
    </script>
</body>
</html>