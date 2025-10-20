/**
 * Sihat Al-Hajj - Main Application JavaScript (jQuery)
 */

$(document).ready(function() {
    
    // Initialize tooltips and popovers if using Bootstrap
    if (typeof bootstrap !== 'undefined') {
        $('[data-bs-toggle="tooltip"]').tooltip();
        $('[data-bs-toggle="popover"]').popover();
    }
    
    // Form validation helper
    window.validateForm = function(formSelector) {
        let isValid = true;
        $(formSelector + ' [required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('border-red-500');
                isValid = false;
            } else {
                $(this).removeClass('border-red-500');
            }
        });
        return isValid;
    };
    
    // AJAX helper function
    window.ajaxRequest = function(url, method, data, successCallback, errorCallback) {
        $.ajax({
            url: url,
            method: method || 'GET',
            data: data || {},
            dataType: 'json',
            success: function(response) {
                if (successCallback) successCallback(response);
            },
            error: function(xhr, status, error) {
                if (errorCallback) {
                    errorCallback(xhr.responseJSON || {message: error});
                } else {
                    console.error('AJAX Error:', error);
                }
            }
        });
    };
    
    // Show loading spinner
    window.showLoading = function(element) {
        $(element).html('<i class="fas fa-spinner fa-spin"></i> جاري التحميل...');
    };
    
    // Hide loading spinner
    window.hideLoading = function(element, originalText) {
        $(element).html(originalText);
    };
    
    // Show notification
    window.showNotification = function(message, type = 'info') {
        const alertClass = {
            'success': 'bg-green-100 border-green-400 text-green-700',
            'error': 'bg-red-100 border-red-400 text-red-700',
            'warning': 'bg-yellow-100 border-yellow-400 text-yellow-700',
            'info': 'bg-blue-100 border-blue-400 text-blue-700'
        };
        
        const notification = $(`
            <div class="fixed top-4 right-4 z-50 border px-4 py-3 rounded ${alertClass[type]} notification-alert">
                <span class="block sm:inline">${message}</span>
                <button class="float-right ml-2 text-xl leading-none" onclick="$(this).parent().fadeOut()">&times;</button>
            </div>
        `);
        
        $('body').append(notification);
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            notification.fadeOut(() => notification.remove());
        }, 5000);
    };
    
    // Confirm dialog
    window.confirmAction = function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    };
    
    // Format date for display
    window.formatDate = function(dateString, locale = 'ar-SA') {
        const date = new Date(dateString);
        return date.toLocaleDateString(locale, {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };
    
    // Format time for display
    window.formatTime = function(dateString, locale = 'ar-SA') {
        const date = new Date(dateString);
        return date.toLocaleTimeString(locale, {
            hour: '2-digit',
            minute: '2-digit'
        });
    };
    
    // Smooth scroll to element
    window.scrollToElement = function(selector, offset = 0) {
        const element = $(selector);
        if (element.length) {
            $('html, body').animate({
                scrollTop: element.offset().top - offset
            }, 500);
        }
    };
    
    // Copy text to clipboard
    window.copyToClipboard = function(text) {
        navigator.clipboard.writeText(text).then(() => {
            showNotification('تم نسخ النص بنجاح', 'success');
        }).catch(() => {
            showNotification('فشل في نسخ النص', 'error');
        });
    };
    
    // Auto-resize textareas
    $('textarea[data-auto-resize]').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Handle form submissions with loading states
    $('form[data-ajax]').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $submitBtn = $form.find('[type="submit"]');
        const originalText = $submitBtn.html();
        
        showLoading($submitBtn);
        
        ajaxRequest(
            $form.attr('action'),
            $form.attr('method') || 'POST',
            $form.serialize(),
            function(response) {
                hideLoading($submitBtn, originalText);
                if (response.success) {
                    showNotification(response.message || 'تم بنجاح', 'success');
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                } else {
                    showNotification(response.message || 'حدث خطأ', 'error');
                }
            },
            function(error) {
                hideLoading($submitBtn, originalText);
                showNotification(error.message || 'حدث خطأ في الاتصال', 'error');
            }
        );
    });
    
    // Handle delete buttons with confirmation
    $('[data-delete-url]').on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('delete-url');
        const message = $(this).data('confirm-message') || 'هل أنت متأكد من الحذف؟';
        
        confirmAction(message, function() {
            ajaxRequest(url, 'DELETE', {}, function(response) {
                if (response.success) {
                    showNotification(response.message || 'تم الحذف بنجاح', 'success');
                    // Reload page or remove element
                    if (response.reload) {
                        location.reload();
                    }
                } else {
                    showNotification(response.message || 'فشل في الحذف', 'error');
                }
            });
        });
    });
    
});