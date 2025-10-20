<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="صحة الحاج - منصة رقمية شاملة لخدمة ضيوف الرحمن">
    <title><?= isset($title) ? $title . ' - ' : '' ?>صحة الحاج</title>
    
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

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        /* Fade-in animation for alerts */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Smooth transitions */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
    
    <?php if (isset($styles)): ?>
        <?= $styles ?>
    <?php endif; ?>
</head>
<body class="font-sans antialiased">
    <!-- Main Content -->
    <?php if (isset($content)): ?>
        <?= $content ?>
    <?php endif; ?>
    
    <!-- Custom Scripts -->
    <?php if (isset($scripts)): ?>
        <?= $scripts ?>
    <?php endif; ?>
</body>
</html>
