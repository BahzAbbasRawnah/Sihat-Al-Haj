<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - غير مصرح</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Main App Styles -->
    <link rel="stylesheet" href="/sihat-al-haj/public/assets/css/style.css">
</head>
<body class="font-sans bg-bg-primary text-text-primary">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="text-center">
            <div class="mb-8">
                <i class="fas fa-lock text-8xl text-red-500"></i>
            </div>
            
            <h1 class="text-6xl font-bold text-primary mb-4">403</h1>
            <h2 class="text-3xl font-semibold mb-4" style="color: var(--text-primary);">
                <?= $title ?? 'غير مصرح' ?>
            </h2>
            <p class="text-xl mb-8" style="color: var(--text-secondary);">
                <?= $message ?? 'عذراً، ليس لديك صلاحية للوصول إلى هذه الصفحة' ?>
            </p>
            
            <div class="flex gap-4 justify-center">
                <a href="/sihat-al-haj/" class="btn btn-primary">
                    <i class="fas fa-home ml-2"></i>
                    العودة للرئيسية
                </a>
                <button onclick="history.back()" class="btn btn-secondary">
                    <i class="fas fa-arrow-right ml-2"></i>
                    العودة للخلف
                </button>
            </div>
        </div>
    </div>
</body>
</html>
