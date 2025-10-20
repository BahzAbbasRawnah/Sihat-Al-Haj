<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-gray-50">
    <div class="max-w-2xl w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-gradient-to-br from-saudi-green to-green-600 rounded-full flex items-center justify-center mb-4 shadow-lg">
                <i class="fas fa-user-plus text-4xl text-white"></i>
            </div>
            <h2 class="text-3xl font-extrabold text-gray-900">
                إنشاء حساب جديد
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                انضم إلى منصة صحة الحاج
            </p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 backdrop-blur-sm border border-gray-100">
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
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-500 hover:text-green-700">
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
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['flash']['error']); ?>
            <?php endif; ?>

            <!-- General Error -->
            <?php if (isset($errors['general'])): ?>
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-medium text-red-800"><?= $errors['general'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Multiple Errors Summary -->
            <?php if (isset($errors) && is_array($errors) && count($errors) > 1): ?>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
                        </div>
                        <div class="mr-3 flex-1">
                            <p class="text-sm font-semibold text-yellow-800 mb-2">يرجى تصحيح الأخطاء التالية:</p>
                            <ul class="list-disc list-inside text-sm text-yellow-700 space-y-1">
                                <?php foreach ($errors as $field => $error): ?>
                                    <?php if ($field !== 'general'): ?>
                                        <li><?= $error ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form class="space-y-8" action="<?= url('/register') ?>" method="POST">
                <!-- Hidden User Type - Default to Pilgrim -->
                <input type="hidden" name="user_type" value="pilgrim">

                <!-- Account Information -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b-2 border-saudi-green">
                        <div class="bg-green-50 p-2 rounded-lg">
                            <i class="fas fa-user-lock text-saudi-green text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">معلومات الحساب</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Username -->
                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم المستخدم <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="username" 
                                name="username" 
                                type="text" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['username']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="اسم المستخدم"
                                value="<?= $old['username'] ?? '' ?>"
                            >
                            <?php if (isset($errors['username'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['username'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                البريد الإلكتروني
                            </label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['email']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="example@email.com"
                                value="<?= $old['email'] ?? '' ?>"
                            >
                            <?php if (isset($errors['email'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['email'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['password']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="كلمة المرور"
                            >
                            <?php if (isset($errors['password'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['password'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                تأكيد كلمة المرور <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['password_confirmation']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="تأكيد كلمة المرور"
                            >
                            <?php if (isset($errors['password_confirmation'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['password_confirmation'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b-2 border-saudi-green">
                        <div class="bg-green-50 p-2 rounded-lg">
                            <i class="fas fa-id-card text-saudi-green text-xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">المعلومات الشخصية</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name Arabic -->
                        <div>
                            <label for="first_name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الأول (عربي) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="first_name_ar" 
                                name="first_name_ar" 
                                type="text" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['first_name_ar']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="الاسم الأول"
                                value="<?= $old['first_name_ar'] ?? '' ?>"
                            >
                            <?php if (isset($errors['first_name_ar'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['first_name_ar'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Last Name Arabic -->
                        <div>
                            <label for="last_name_ar" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العائلة (عربي) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="last_name_ar" 
                                name="last_name_ar" 
                                type="text" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['last_name_ar']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="اسم العائلة"
                                value="<?= $old['last_name_ar'] ?? '' ?>"
                            >
                            <?php if (isset($errors['last_name_ar'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['last_name_ar'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- First Name English -->
                        <div>
                            <label for="first_name_en" class="block text-sm font-medium text-gray-700 mb-2">
                                الاسم الأول (إنجليزي) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="first_name_en" 
                                name="first_name_en" 
                                type="text" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['first_name_en']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="First Name"
                                value="<?= $old['first_name_en'] ?? '' ?>"
                            >
                            <?php if (isset($errors['first_name_en'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['first_name_en'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Last Name English -->
                        <div>
                            <label for="last_name_en" class="block text-sm font-medium text-gray-700 mb-2">
                                اسم العائلة (إنجليزي) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                id="last_name_en" 
                                name="last_name_en" 
                                type="text" 
                                required 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['last_name_en']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="Last Name"
                                value="<?= $old['last_name_en'] ?? '' ?>"
                            >
                            <?php if (isset($errors['last_name_en'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['last_name_en'] ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهاتف
                            </label>
                            <input 
                                id="phone_number" 
                                name="phone_number" 
                                type="tel" 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['phone_number']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="+966xxxxxxxxx"
                                value="<?= $old['phone_number'] ?? '' ?>"
                            >
                            <?php if (isset($errors['phone_number'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['phone_number'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- ID Number -->
                        <div>
                            <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">
                                رقم الهوية
                            </label>
                            <input 
                                id="id_number" 
                                name="id_number" 
                                type="text" 
                                class="appearance-none block w-full px-4 py-3 border <?= isset($errors['id_number']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400"
                                placeholder="رقم الهوية"
                                value="<?= $old['id_number'] ?? '' ?>"
                            >
                            <?php if (isset($errors['id_number'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= $errors['id_number'] ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                الجنس
                            </label>
                            <select 
                                id="gender" 
                                name="gender" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400 bg-white"
                            >
                                <option value="">اختر الجنس</option>
                                <option value="male" <?= ($old['gender'] ?? '') === 'male' ? 'selected' : '' ?>>ذكر</option>
                                <option value="female" <?= ($old['gender'] ?? '') === 'female' ? 'selected' : '' ?>>أنثى</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Date of Birth -->
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                تاريخ الميلاد
                            </label>
                            <input 
                                id="date_of_birth" 
                                name="date_of_birth" 
                                type="date" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400 bg-white"
                                value="<?= $old['date_of_birth'] ?? '' ?>"
                            >
                        </div>

                        <!-- Nationality -->
                        <div>
                            <label for="nationality_id" class="block text-sm font-medium text-gray-700 mb-2">
                                الجنسية
                            </label>
                            <input 
                                id="nationality_id" 
                                name="nationality_id" 
                                type="number" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-saudi-green focus:border-transparent transition-all duration-200 hover:border-gray-400 bg-white"
                                placeholder="رقم الجنسية"
                                value="<?= $old['nationality_id'] ?? '' ?>"
                            >
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6">
                    <button 
                        type="submit" 
                        class="w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-saudi-green to-green-600 hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-300 transform hover:scale-[1.02] shadow-lg hover:shadow-xl"
                    >
                        <i class="fas fa-user-plus ml-2 text-lg"></i>
                        إنشاء الحساب
                    </button>
                </div>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-sm text-gray-600">
                        لديك حساب بالفعل؟
                        <a href="<?= url('/login') ?>" class="font-semibold text-saudi-green hover:text-green-700 transition duration-200 hover:underline">
                            تسجيل الدخول
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