<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="صحة الحاج - منصة رقمية شاملة لخدمة ضيوف الرحمن">
    <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>صحة الحاج</title>
    
    <!-- Tailwind CSS CDN with Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'saudi-green': '#006C35',
                        'saudi-gold': '#FFD700',
                        'medical-emergency': '#ef4444',
                        'medical-warning': '#f59e0b',
                        'medical-success': '#10b981',
                        'medical-info': '#3b82f6'
                    }
                }
            }
        }
    </script>

    <!-- Theme and Language Scripts (placing at top to ensure it loads first) -->
    <script>
        // Apply saved theme preference
        function applyTheme() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        }
        
        // Apply saved language preference
        function applyLanguage() {
            const savedLang = localStorage.getItem('language') || 'ar';
            const dir = savedLang === 'ar' ? 'rtl' : 'ltr';
            document.documentElement.setAttribute('lang', savedLang);
            document.documentElement.setAttribute('dir', dir);
        }
        
        // Apply saved preferences immediately
        applyTheme();
        applyLanguage();
    </script>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Main App Styles -->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    
    <!-- Custom Page Styles -->
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>
</head>
<body class="font-sans bg-bg-primary text-text-primary">


    <!-- Include Navigation -->
    <?php include VIEWS_PATH . '/components/navigation.php'; ?>
    
    <!-- Main Content -->
    <main>
        <?php if (isset($content)): ?>
            <?= $content ?>
        <?php endif; ?>
    </main>

    <!-- Main App Script -->
    <script src="<?= asset('assets/js/app.js') ?>"></script>
    
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
    
    <!-- Page-specific scripts -->
    <?php if (isset($pageScripts)): ?>
        <?= $pageScripts ?>
    <?php endif; ?>
</body>
</html>