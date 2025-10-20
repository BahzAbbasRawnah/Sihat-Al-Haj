<!DOCTYPE html>
<html lang="ar" dir="rtl" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - خطأ في الخادم</title>
    
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
                <i class="fas fa-server text-8xl text-red-600"></i>
            </div>
            
            <h1 class="text-6xl font-bold text-primary mb-4">500</h1>
            <h2 class="text-3xl font-semibold mb-4" style="color: var(--text-primary);">
                <?= $title ?? 'خطأ في الخادم' ?>
            </h2>
            <p class="text-xl mb-8" style="color: var(--text-secondary);">
                <?= $message ?? 'عذراً، حدث خطأ في الخادم. يرجى المحاولة لاحقاً' ?>
            </p>
            
            <div class="flex gap-4 justify-center">
                <a href="/sihat-al-haj/" class="btn btn-primary">
                    <i class="fas fa-home ml-2"></i>
                    العودة للرئيسية
                </a>
                <button onclick="location.reload()" class="btn btn-secondary">
                    <i class="fas fa-redo ml-2"></i>
                    إعادة المحاولة
                </button>
            </div>
        </div>
    </div>
</body>
</html>
