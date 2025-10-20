<?php 
/**
 * FAQs Page - Fully Multilingual
 */

$currentLang = getCurrentLanguage();
$title = __('faqs');
?>

<div class="main-content min-h-screen relative page-content">
    <div class="relative z-10 py-12">
        <div class="container mx-auto px-4 max-w-4xl">
            <!-- Page Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-primary"><?= __('frequently_asked_questions') ?></h1>
                <p class="text-lg <?= getTextAlignClass() ?> text-secondary">
                    <?= __('faqs_description') ?>
                </p>
            </div>
            
            <!-- FAQs Accordion -->
            <?php if (!empty($faqs)): ?>
                <div class="space-y-4">
                    <?php foreach ($faqs as $index => $faq): ?>
                        <div class="card shadow-lg" style="background-color: var(--bg-secondary);">
                            <button 
                                onclick="toggleFaq(<?= $index ?>)" 
                                class="w-full <?= getTextAlignClass() ?> p-6 flex justify-between items-center hover:bg-opacity-80 transition"
                                aria-expanded="false"
                                id="faq-button-<?= $index ?>"
                            >
                                <h3 class="text-lg font-semibold text-primary pr-4">
                                    <?= getLocalizedField($faq, 'question') ?>
                                </h3>
                                <i class="fas fa-chevron-down text-primary transition-transform duration-300" id="faq-icon-<?= $index ?>"></i>
                            </button>
                            <div 
                                id="faq-content-<?= $index ?>" 
                                class="hidden px-6 pb-6 <?= getTextAlignClass() ?>"
                            >
                                <div class="pt-4 border-t" style="border-color: var(--border-color);">
                                    <p class="text-gray-700 leading-relaxed">
                                        <?= nl2br(htmlspecialchars(getLocalizedField($faq, 'answer'))) ?>
                                    </p>
                                    <?php if (!empty($faq['category_' . $currentLang])): ?>
                                        <div class="mt-4">
                                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                                <?= htmlspecialchars(getLocalizedField($faq, 'category')) ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="card shadow-lg text-center py-12" style="background-color: var(--bg-secondary);">
                    <i class="fas fa-question-circle text-6xl mb-4 text-gray-400"></i>
                    <h3 class="text-xl font-semibold mb-2 text-primary"><?= __('no_faqs_available') ?></h3>
                    <p class="text-secondary"><?= __('faqs_coming_soon') ?></p>
                </div>
            <?php endif; ?>
            
            <!-- Contact Support Section -->
            <div class="mt-12 card shadow-lg p-8 text-center" style="background-color: var(--bg-secondary);">
                <h3 class="text-2xl font-bold mb-4 text-primary"><?= __('still_have_questions') ?></h3>
                <p class="text-gray-700 mb-6"><?= __('contact_support_team') ?></p>
                <a href="<?= url('/contact') ?>" class="btn btn-primary inline-block">
                    <i class="fas fa-envelope <?= $currentLang === 'ar' ? 'ml-2' : 'mr-2' ?>"></i>
                    <?= __('contact_us') ?>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Emergency FAB -->
    <div class="fixed bottom-6 <?= $currentLang === 'ar' ? 'left-6' : 'right-6' ?> z-50">
        <a href="tel:997" class="bg-red-600 hover:bg-red-700 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
            <i class="fas fa-phone text-xl"></i>
        </a>
        <div class="absolute bottom-20 <?= $currentLang === 'ar' ? 'left-0' : 'right-0' ?> bg-red-600 text-white px-3 py-1 rounded-lg text-sm whitespace-nowrap opacity-0 hover:opacity-100 transition-opacity duration-300">
            <?= __('emergency_997') ?>
        </div>
    </div>
</div>

<script>
function toggleFaq(index) {
    const content = document.getElementById('faq-content-' + index);
    const icon = document.getElementById('faq-icon-' + index);
    const button = document.getElementById('faq-button-' + index);
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
        button.setAttribute('aria-expanded', 'true');
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
        button.setAttribute('aria-expanded', 'false');
    }
}
</script>
