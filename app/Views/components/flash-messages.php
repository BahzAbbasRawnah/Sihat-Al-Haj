<?php
$flashMessages = [
    'success' => $this->flash('success'),
    'error' => $this->flash('error'),
    'warning' => $this->flash('warning'),
    'info' => $this->flash('info')
];
?>

<?php foreach ($flashMessages as $type => $message): ?>
    <?php if ($message): ?>
        <div class="flash-message fixed top-20 <?= $isRTL ? 'left-4' : 'right-4' ?> z-50 animate-slide-down">
            <div class="alert alert-<?= $type === 'error' ? 'error' : $type ?> max-w-md shadow-lg">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <?php if ($type === 'success'): ?>
                            <i class="fas fa-check-circle text-accent-500"></i>
                        <?php elseif ($type === 'error'): ?>
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        <?php elseif ($type === 'warning'): ?>
                            <i class="fas fa-exclamation-triangle text-secondary-500"></i>
                        <?php else: ?>
                            <i class="fas fa-info-circle text-primary-500"></i>
                        <?php endif; ?>
                    </div>
                    <div class="<?= $isRTL ? 'mr-3' : 'ml-3' ?> flex-1">
                        <p class="text-sm font-medium">
                            <?= htmlspecialchars($message) ?>
                        </p>
                    </div>
                    <div class="<?= $isRTL ? 'mr-4' : 'ml-4' ?> flex-shrink-0">
                        <button type="button" class="flash-close inline-flex text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition ease-in-out duration-150">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Close flash messages
    document.querySelectorAll('.flash-close').forEach(function(button) {
        button.addEventListener('click', function() {
            const flashMessage = this.closest('.flash-message');
            flashMessage.style.opacity = '0';
            flashMessage.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                flashMessage.remove();
            }, 300);
        });
    });
    
    // Auto-hide flash messages after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.flash-message').forEach(function(message) {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(function() {
                message.remove();
            }, 300);
        });
    }, 5000);
});
</script>

