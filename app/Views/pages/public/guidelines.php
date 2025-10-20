<?php 
/**
 * Guidelines Page - Fully Multilingual
 */

$currentLang = getCurrentLanguage();
$title = __('health_guidelines');
?>
<div class="main-content min-h-screen relative page-content">
    <!-- Guidelines Content -->
    <div class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-primary mb-4"><?= __('rituals_guide') ?></h2>
            <p class="max-w-3xl mx-auto <?= getTextAlignClass() ?> text-primary">
                <?= __('rituals_guide_description') ?>
            </p>
        </div>

        <!-- Guidelines Timeline -->
        <div class="relative mb-24">
            <!-- Vertical Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary"></div>

            <!-- Timeline Items -->
            <div class="grid grid-cols-1 gap-12">
                <?php if (!empty($timeline)): ?>
                    <?php foreach ($timeline as $item): ?>
                        <div class="relative">
                            <div class="flex items-center justify-center">
                                <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center z-10 mb-4">
                                    <i class="<?= htmlspecialchars($item['icon_class'] ?? 'fa-solid fa-star') ?> text-2xl"></i>
                                </div>
                            </div>
                            <div class="rounded-lg p-6 shadow-md mx-4 md:mx-12 bg-secondary">
                                <h3 class="text-2xl font-bold mb-3 <?= getTextAlignClass() ?> text-primary">
                                    <?= htmlspecialchars(getLocalizedField($item, 'ritual_name')) ?>
                                </h3>
                                <?php if (!empty($item['description_ar']) || !empty($item['description_en'])): ?>
                                    <p class="mb-4 <?= getTextAlignClass() ?> text-primary">
                                        <?= nl2br(htmlspecialchars(getLocalizedField($item, 'description'))) ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($item['instructions_ar']) || !empty($item['instructions_en'])): ?>
                                    <div class="space-y-3 <?= getTextAlignClass() ?> text-primary">
                                        <?php 
                                        $instructions = explode('\n', getLocalizedField($item, 'instructions'));
                                        foreach ($instructions as $instruction):
                                            if (trim($instruction)):
                                        ?>
                                            <div class="flex items-start gap-2">
                                                <i class="fa-solid fa-circle-check text-primary mt-1 <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                                                <span><?= htmlspecialchars(trim($instruction)) ?></span>
                                            </div>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 text-secondary">
                        <i class="fas fa-info-circle text-4xl mb-4"></i>
                        <p><?= __('no_guidelines_available') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Additional Resources -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-primary mb-6 text-center"><?= __('additional_resources') ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 rounded-lg shadow-md text-center bg-secondary">
                    <div class="text-primary text-5xl mb-4">
                        <i class="fa-solid fa-book"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-primary"><?= __('books_references') ?></h3>
                    <p class="<?= getTextAlignClass() ?> text-primary">
                        <?= __('books_references_description') ?>
                    </p>
                </div>
                <div class="p-6 rounded-lg shadow-md text-center bg-secondary">
                    <div class="text-primary text-5xl mb-4">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-primary"><?= __('useful_apps') ?></h3>
                    <p class="<?= getTextAlignClass() ?> text-primary">
                        <?= __('useful_apps_description') ?>
                    </p>
                </div>
                <div class="p-6 rounded-lg shadow-md text-center bg-secondary">
                    <div class="text-primary text-5xl mb-4">
                        <i class="fa-solid fa-video"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-primary"><?= __('explanatory_videos') ?></h3>
                    <p class="<?= getTextAlignClass() ?> text-primary">
                        <?= __('explanatory_videos_description') ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Health Guidelines -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-primary mb-6 text-center"><?= __('health_guidelines') ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="p-6 rounded-lg shadow-md bg-secondary">
                    <h3 class="text-xl font-bold mb-4 <?= getTextAlignClass() ?> text-primary"><?= __('first_aid') ?></h3>
                    <ul class="space-y-3 <?= getTextAlignClass() ?> text-primary">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-sun text-yellow-500 mt-1 <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <div>
                                <span class="font-bold"><?= __('sunstroke') ?></span>
                                <p><?= __('sunstroke_treatment') ?></p>
                            </div>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-droplet text-blue-500 mt-1 <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <div>
                                <span class="font-bold"><?= __('dehydration') ?></span>
                                <p><?= __('dehydration_treatment') ?></p>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="p-6 rounded-lg shadow-md bg-secondary">
                    <h3 class="text-xl font-bold mb-4 <?= getTextAlignClass() ?> text-primary"><?= __('general_health_tips') ?></h3>
                    <ul class="space-y-3 <?= getTextAlignClass() ?> text-primary">
                        <li class="flex items-start gap-2">
                            <i class="fa-solid fa-shield-virus text-green-500 mt-1 <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                            <div>
                                <span class="font-bold"><?= __('personal_hygiene') ?></span>
                                <p><?= __('personal_hygiene_tips') ?></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button for Emergency -->
    <div class="fixed bottom-6 <?= $currentLang === 'ar' ? 'left-6' : 'right-6' ?> z-50">
        <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
            <i class="fas fa-phone text-xl"></i>
        </a>
        <div class="absolute bottom-20 <?= $currentLang === 'ar' ? 'left-0' : 'right-0' ?> bg-red-600 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 hover:opacity-100 transition-opacity duration-300">
            <span><?= __('emergency_997') ?></span>
        </div>
    </div>
</div>
