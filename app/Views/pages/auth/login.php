<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-saudi-green to-green-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                <i class="fas fa-user-shield text-4xl text-white"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">
                تسجيل الدخول
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                مرحباً بك في منصة صحة الحاج
            </p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 border border-gray-100">
            <!-- Success Message -->
            <?php if (isset($_SESSION['flash']['success'])): ?>
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-green-800"><?= $_SESSION['flash']['success'] ?></p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash']['success']); ?>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if (isset($_SESSION['flash']['error'])): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-red-800"><?= $_SESSION['flash']['error'] ?></p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>

            <!-- Info Message -->
            <?php if (isset($_SESSION['flash']['info'])): ?>
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-blue-800"><?= $_SESSION['flash']['info'] ?></p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-blue-500 hover:text-blue-700 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash']['info']); ?>
            <?php endif; ?>

            <!-- Warning Message -->
            <?php if (isset($_SESSION['flash']['warning'])): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm animate-fade-in">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-yellow-800"><?= $_SESSION['flash']['warning'] ?></p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-500 hover:text-yellow-700 transition">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash']['warning']); ?>
            <?php endif; ?>

            <!-- Login Error -->
            <?php if (isset($errors['login'])): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-red-800"><?= $errors['login'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-6" action="<?= url('/login') ?>" method="POST">
                <!-- Identifier Field (Username/Email/Phone/ID/Passport) -->
                <div>
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                        اسم المستخدم / البريد الإلكتروني / رقم الهاتف / رقم الهوية / رقم الجواز
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input 
                            id="identifier" 
                            name="identifier" 
                            type="text" 
                            required 
                            class="appearance-none block w-full pr-10 px-4 py-3 border <?= isset($errors['identifier']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                            placeholder="أدخل معرفك (اسم المستخدم، البريد، الهاتف، الهوية، أو الجواز)"
                            value="<?= $old['identifier'] ?? '' ?>"
                        >
                    </div>
                    <?php if (isset($errors['identifier'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['identifier'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            class="appearance-none block w-full pr-10 px-4 py-3 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                            placeholder="أدخل كلمة المرور"
                        >
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            class="h-4 w-4 text-saudi-green focus:ring-saudi-green border-gray-300 rounded"
                        >
                        <label for="remember" class="mr-2 block text-sm text-gray-700">
                            تذكرني
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="<?= url('/forgot-password') ?>" class="font-medium text-saudi-green hover:text-green-700 transition duration-150">
                            نسيت كلمة المرور؟
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-saudi-green to-green-600 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                    >
                        <span class="absolute right-0 inset-y-0 flex items-center pr-4">
                            <i class="fas fa-sign-in-alt text-lg text-green-200 group-hover:text-white"></i>
                        </span>
                        تسجيل الدخول
                    </button>
                </div>

                <!-- Register Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        ليس لديك حساب؟
                        <a href="<?= url('/register') ?>" class="font-semibold text-saudi-green hover:text-green-700 transition duration-200 hover:underline">
                            إنشاء حساب جديد
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Back to Home -->
        <div class="text-center">
            <a href="<?= url('/') ?>" class="text-sm text-gray-600 hover:text-saudi-green transition duration-200 font-medium">
                <i class="fas fa-arrow-right ml-2"></i>
                العودة إلى الصفحة الرئيسية
            </a>
        </div>
    </div>
</div>