<?php
/**
 * Create User - Modern Design
 */
$currentLang = getCurrentLanguage();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-primary to-secondary rounded-xl shadow-lg p-6 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2"><?= __('add_new_user') ?></h1>
            <p class="text-white text-opacity-90"><?= __('add_user_to_system') ?></p>
        </div>
        <div class="hidden md:block">
            <i class="fas fa-user-plus text-6xl opacity-20"></i>
        </div>
    </div>
</div>

<!-- Form Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
        <h2 class="text-xl font-bold text-white flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            <?= __('user_information') ?>
        </h2>
    </div>
    
    <div class="p-8">
        <?php if (isset($_SESSION['flash']['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-circle ml-2"></i>
                    <span class="font-semibold"><?= $_SESSION['flash']['error'] ?></span>
                </div>
                <?php if (isset($_SESSION['validation_errors']) && !empty($_SESSION['validation_errors'])): ?>
                    <ul class="list-disc list-inside mt-2 mr-6">
                        <?php foreach ($_SESSION['validation_errors'] as $field => $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php unset($_SESSION['flash']['error']); ?>
        <?php endif; ?>
        
        <form method="POST" action="/sihat-al-haj/admin/users/create" class="space-y-6">
            
            <!-- User Type -->
            <div class="bg-blue-50 border-r-4 border-blue-500 p-4 rounded-lg">
                <label for="user_type" class="block text-sm font-semibold text-gray-700 mb-2">
                    <?= __('user_type') ?> <span class="text-red-500">*</span>
                </label>
                <select id="user_type" name="user_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option value=""><?= __('select_user_type') ?></option>
                    <option value="pilgrim" <?= ($_SESSION['old_input']['user_type'] ?? '') === 'pilgrim' ? 'selected' : '' ?>><?= __('pilgrim') ?></option>
                    <option value="guide" <?= ($_SESSION['old_input']['user_type'] ?? '') === 'guide' ? 'selected' : '' ?>><?= __('guide') ?></option>
                    <option value="medical_personnel" <?= ($_SESSION['old_input']['user_type'] ?? '') === 'medical_personnel' ? 'selected' : '' ?>><?= __('medical_personnel') ?></option>
                    <option value="administrator" <?= ($_SESSION['old_input']['user_type'] ?? '') === 'administrator' ? 'selected' : '' ?>><?= __('administrator') ?></option>
                </select>
            </div>

            <!-- Personal Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name Arabic -->
                <div>
                    <label for="first_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('first_name_arabic') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name_ar" name="first_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent <?= isset($_SESSION['validation_errors']['first_name_ar']) ? 'border-red-500' : '' ?>"
                           value="<?= htmlspecialchars($_SESSION['old_input']['first_name_ar'] ?? '') ?>"
                           placeholder="<?= __('example_first_name_ar') ?>">
                    <?php if (isset($_SESSION['validation_errors']['first_name_ar'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $_SESSION['validation_errors']['first_name_ar'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Last Name Arabic -->
                <div>
                    <label for="last_name_ar" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('last_name_arabic') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name_ar" name="last_name_ar" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['last_name_ar'] ?? '') ?>"
                           placeholder="<?= __('example_last_name_ar') ?>">
                </div>

                <!-- First Name English -->
                <div>
                    <label for="first_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('first_name_english') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="first_name_en" name="first_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['first_name_en'] ?? '') ?>"
                           placeholder="<?= __('example_first_name_en') ?>">
                </div>

                <!-- Last Name English -->
                <div>
                    <label for="last_name_en" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('last_name_english') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="last_name_en" name="last_name_en" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['last_name_en'] ?? '') ?>"
                           placeholder="<?= __('example_last_name_en') ?>">
                </div>
            </div>

            <!-- Contact Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('email') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['email'] ?? '') ?>"
                           placeholder="user@example.com">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('phone_number') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['phone'] ?? '') ?>"
                           placeholder="+966 50 123 4567">
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('password') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="••••••••">
                    <p class="text-xs text-gray-500 mt-1"><?= __('password_min_length') ?></p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('confirm_password') ?> <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Additional Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Date of Birth -->
                <div>
                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('date_of_birth') ?>
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                           value="<?= htmlspecialchars($_SESSION['old_input']['date_of_birth'] ?? '') ?>">
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('gender') ?>
                    </label>
                    <select id="gender" name="gender" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value=""><?= __('select_gender') ?></option>
                        <option value="male" <?= ($_SESSION['old_input']['gender'] ?? '') === 'male' ? 'selected' : '' ?>><?= __('male') ?></option>
                        <option value="female" <?= ($_SESSION['old_input']['gender'] ?? '') === 'female' ? 'selected' : '' ?>><?= __('female') ?></option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('status') ?>
                    </label>
                    <select id="status" name="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="active" selected><?= __('active') ?></option>
                        <option value="inactive"><?= __('inactive') ?></option>
                        <option value="suspended"><?= __('suspended') ?></option>
                    </select>
                </div>
            </div>

            <!-- Nationality & Country -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nationality_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('nationality') ?>
                    </label>
                    <select id="nationality_id" name="nationality_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value=""><?= __('select_nationality') ?></option>
                        <?php if (!empty($countries)): ?>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= $country['country_id'] ?>" <?= ($_SESSION['old_input']['nationality_id'] ?? '') == $country['country_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(getLocalizedField($country, 'name')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label for="country_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        <?= __('country_of_residence') ?>
                    </label>
                    <select id="country_id" name="country_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value=""><?= __('select_country') ?></option>
                        <?php if (!empty($countries)): ?>
                            <?php foreach ($countries as $country): ?>
                                <option value="<?= $country['country_id'] ?>" <?= ($_SESSION['old_input']['country_id'] ?? '') == $country['country_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars(getLocalizedField($country, 'name')) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                    <?= __('address') ?>
                </label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                          placeholder="<?= __('full_address') ?>..."><?= htmlspecialchars($_SESSION['old_input']['address'] ?? '') ?></textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200 mt-8">
                <a href="/sihat-al-haj/admin/users" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition flex items-center gap-2">
                    <i class="fas fa-times"></i>
                    <span><?= __('cancel') ?></span>
                </a>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg font-semibold hover:bg-opacity-90 transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    <span><?= __('save_user') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php 
// Clear old input and validation errors after displaying
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}
if (isset($_SESSION['validation_errors'])) {
    unset($_SESSION['validation_errors']);
}
?>
