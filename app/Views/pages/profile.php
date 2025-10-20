<?php
/**
 * User Profile Page
 */
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'الملف الشخصي' ?> - صحة الحاج</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-green-600 rounded-full flex items-center justify-center text-white ml-2">
                        <i class="fas fa-user"></i>
                    </div>
                    <h1 class="text-xl font-bold text-gray-800">صحة الحاج</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="/sihat-al-haj/dashboard" class="text-gray-600 hover:text-green-600 transition">
                        <i class="fas fa-home ml-2"></i>
                        لوحة التحكم
                    </a>
                    <a href="/sihat-al-haj/logout" class="text-red-600 hover:text-red-700 transition">
                        <i class="fas fa-sign-out-alt ml-2"></i>
                        تسجيل الخروج
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl shadow-lg p-6 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">الملف الشخصي</h1>
                    <p class="text-white text-opacity-90">إدارة معلوماتك الشخصية</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-user-circle text-6xl opacity-20"></i>
                </div>
            </div>
        </div>

        <?php if (isset($_SESSION['flash']['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-check-circle ml-2"></i>
                <?= $_SESSION['flash']['success'] ?>
            </div>
            <?php unset($_SESSION['flash']['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <?= $_SESSION['flash']['error'] ?>
            </div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>

        <!-- Profile Form -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class="fas fa-info-circle"></i>
                    المعلومات الشخصية
                </h2>
            </div>

            <div class="p-8">
                <form method="POST" action="/sihat-al-haj/profile" class="space-y-6">
                    <!-- Names -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                                الاسم الأول (عربي)
                            </label>
                            <input type="text" id="first_name_ar" name="first_name_ar"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   value="<?= htmlspecialchars($user['first_name_ar'] ?? '') ?>">
                        </div>

                        <div>
                            <label for="last_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                                اسم العائلة (عربي)
                            </label>
                            <input type="text" id="last_name_ar" name="last_name_ar"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   value="<?= htmlspecialchars($user['last_name_ar'] ?? '') ?>">
                        </div>

                        <div>
                            <label for="first_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                                الاسم الأول (English)
                            </label>
                            <input type="text" id="first_name_en" name="first_name_en"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   value="<?= htmlspecialchars($user['first_name_en'] ?? '') ?>">
                        </div>

                        <div>
                            <label for="last_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                                اسم العائلة (English)
                            </label>
                            <input type="text" id="last_name_en" name="last_name_en"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   value="<?= htmlspecialchars($user['last_name_en'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input type="email" id="email" name="email" disabled
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed"
                                   value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                            <p class="text-xs text-gray-500 mt-1">لا يمكن تغيير البريد الإلكتروني</p>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                رقم الهاتف
                            </label>
                            <input type="text" id="phone" name="phone"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                            العنوان
                        </label>
                        <textarea id="address" name="address" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                    </div>

                    <!-- Password Change Section -->
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">تغيير كلمة المرور</h3>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    كلمة المرور الحالية
                                </label>
                                <input type="password" id="current_password" name="current_password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                            </div>

                            <div>
                                <label for="new_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    كلمة المرور الجديدة
                                </label>
                                <input type="password" id="new_password" name="new_password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    تأكيد كلمة المرور
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-transparent">
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">اترك الحقول فارغة إذا كنت لا تريد تغيير كلمة المرور</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                        <a href="/sihat-al-haj/dashboard" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>إلغاء</span>
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>حفظ التغييرات</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-12">
        <div class="container mx-auto px-4 py-6 text-center">
            <p class="text-gray-600">صحة الحاج &copy; 2025 - جميع الحقوق محفوظة</p>
        </div>
    </footer>
</body>
</html>
