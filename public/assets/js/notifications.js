/**
 * Notification System
 * 
 * Handles client-side notifications and user feedback
 */
class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }
    
    init() {
        // Create notification container
        this.container = document.createElement('div');
        this.container.id = 'notification-container';
        this.container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(this.container);
        
        // Add CSS styles
        this.addStyles();
    }
    
    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .notification {
                max-width: 400px;
                min-width: 300px;
                padding: 16px;
                border-radius: 8px;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                transform: translateX(100%);
                opacity: 0;
                transition: all 0.3s ease-in-out;
                border-left: 4px solid;
                backdrop-filter: blur(10px);
            }
            
            .notification.show {
                transform: translateX(0);
                opacity: 1;
            }
            
            .notification.success {
                background-color: rgba(16, 185, 129, 0.1);
                border-left-color: #10b981;
                color: #065f46;
            }
            
            .notification.error {
                background-color: rgba(239, 68, 68, 0.1);
                border-left-color: #ef4444;
                color: #991b1b;
            }
            
            .notification.warning {
                background-color: rgba(245, 158, 11, 0.1);
                border-left-color: #f59e0b;
                color: #92400e;
            }
            
            .notification.info {
                background-color: rgba(59, 130, 246, 0.1);
                border-left-color: #3b82f6;
                color: #1e40af;
            }
            
            .dark .notification.success {
                background-color: rgba(16, 185, 129, 0.2);
                color: #6ee7b7;
            }
            
            .dark .notification.error {
                background-color: rgba(239, 68, 68, 0.2);
                color: #fca5a5;
            }
            
            .dark .notification.warning {
                background-color: rgba(245, 158, 11, 0.2);
                color: #fcd34d;
            }
            
            .dark .notification.info {
                background-color: rgba(59, 130, 246, 0.2);
                color: #93c5fd;
            }
            
            .notification-progress {
                position: absolute;
                bottom: 0;
                left: 0;
                height: 3px;
                background-color: currentColor;
                opacity: 0.3;
                transition: width linear;
            }
            
            @media (max-width: 640px) {
                #notification-container {
                    top: 1rem;
                    right: 1rem;
                    left: 1rem;
                }
                
                .notification {
                    max-width: none;
                    min-width: auto;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    show(message, type = 'info', options = {}) {
        const defaults = {
            title: null,
            duration: 5000,
            closable: true,
            persistent: false,
            action: null,
            actionText: null
        };
        
        const config = { ...defaults, ...options };
        
        // Create notification element
        const notification = this.createElement(message, type, config);
        
        // Add to container
        this.container.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Auto-hide if not persistent
        if (!config.persistent && config.duration > 0) {
            this.autoHide(notification, config.duration);
        }
        
        return notification;
    }
    
    createElement(message, type, config) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };
        
        let html = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="${icons[type] || icons.info} text-lg"></i>
                </div>
                <div class="ml-3 flex-1">
        `;
        
        if (config.title) {
            html += `<h4 class="font-semibold mb-1">${this.escapeHtml(config.title)}</h4>`;
        }
        
        html += `<p class="text-sm">${this.escapeHtml(message)}</p>`;
        
        if (config.action && config.actionText) {
            html += `
                <div class="mt-2">
                    <button class="notification-action text-sm font-medium underline hover:no-underline">
                        ${this.escapeHtml(config.actionText)}
                    </button>
                </div>
            `;
        }
        
        html += `</div>`;
        
        if (config.closable) {
            html += `
                <div class="ml-4 flex-shrink-0">
                    <button class="notification-close text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }
        
        html += `</div>`;
        
        // Add progress bar for auto-hide
        if (!config.persistent && config.duration > 0) {
            html += `<div class="notification-progress" style="width: 100%;"></div>`;
        }
        
        notification.innerHTML = html;
        
        // Add event listeners
        this.addEventListeners(notification, config);
        
        return notification;
    }
    
    addEventListeners(notification, config) {
        // Close button
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.hide(notification);
            });
        }
        
        // Action button
        const actionBtn = notification.querySelector('.notification-action');
        if (actionBtn && config.action) {
            actionBtn.addEventListener('click', (e) => {
                e.preventDefault();
                config.action();
                if (!config.persistent) {
                    this.hide(notification);
                }
            });
        }
        
        // Click to dismiss (except for persistent notifications)
        if (!config.persistent) {
            notification.addEventListener('click', (e) => {
                if (!e.target.closest('.notification-action') && !e.target.closest('.notification-close')) {
                    this.hide(notification);
                }
            });
        }
    }
    
    autoHide(notification, duration) {
        const progressBar = notification.querySelector('.notification-progress');
        
        if (progressBar) {
            // Animate progress bar
            progressBar.style.transition = `width ${duration}ms linear`;
            progressBar.style.width = '0%';
        }
        
        setTimeout(() => {
            this.hide(notification);
        }, duration);
    }
    
    hide(notification) {
        notification.classList.remove('show');
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }
    
    success(message, options = {}) {
        return this.show(message, 'success', options);
    }
    
    error(message, options = {}) {
        return this.show(message, 'error', options);
    }
    
    warning(message, options = {}) {
        return this.show(message, 'warning', options);
    }
    
    info(message, options = {}) {
        return this.show(message, 'info', options);
    }
    
    clear() {
        const notifications = this.container.querySelectorAll('.notification');
        notifications.forEach(notification => {
            this.hide(notification);
        });
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Loading overlay system
class LoadingSystem {
    constructor() {
        this.overlay = null;
        this.init();
    }
    
    init() {
        this.overlay = document.createElement('div');
        this.overlay.id = 'loading-overlay';
        this.overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
        this.overlay.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl">
                <div class="flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary-600"></div>
                    <span class="text-gray-900 dark:text-white font-medium" id="loading-text">Loading...</span>
                </div>
            </div>
        `;
        document.body.appendChild(this.overlay);
    }
    
    show(text = 'Loading...') {
        const textElement = this.overlay.querySelector('#loading-text');
        if (textElement) {
            textElement.textContent = text;
        }
        this.overlay.classList.remove('hidden');
    }
    
    hide() {
        this.overlay.classList.add('hidden');
    }
}

// Confirmation dialog system
class ConfirmationSystem {
    constructor() {
        this.modal = null;
        this.init();
    }
    
    init() {
        this.modal = document.createElement('div');
        this.modal.id = 'confirmation-modal';
        this.modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
        this.modal.innerHTML = `
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl max-w-md w-full mx-4">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="confirmation-title">Confirm Action</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-400 mb-6" id="confirmation-message">Are you sure you want to proceed?</p>
                <div class="flex justify-end space-x-3">
                    <button id="confirmation-cancel" class="btn btn-secondary">Cancel</button>
                    <button id="confirmation-confirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        `;
        document.body.appendChild(this.modal);
        
        // Add event listeners
        this.modal.querySelector('#confirmation-cancel').addEventListener('click', () => {
            this.hide();
        });
        
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.hide();
            }
        });
    }
    
    show(message, title = 'Confirm Action', options = {}) {
        return new Promise((resolve) => {
            const titleElement = this.modal.querySelector('#confirmation-title');
            const messageElement = this.modal.querySelector('#confirmation-message');
            const confirmBtn = this.modal.querySelector('#confirmation-confirm');
            
            titleElement.textContent = title;
            messageElement.textContent = message;
            
            if (options.confirmText) {
                confirmBtn.textContent = options.confirmText;
            }
            
            if (options.danger) {
                confirmBtn.className = 'btn btn-danger';
            } else {
                confirmBtn.className = 'btn btn-primary';
            }
            
            // Remove existing event listeners
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add new event listener
            newConfirmBtn.addEventListener('click', () => {
                this.hide();
                resolve(true);
            });
            
            this.modal.classList.remove('hidden');
        });
    }
    
    hide() {
        this.modal.classList.add('hidden');
    }
}

// Initialize systems
const notifications = new NotificationSystem();
const loading = new LoadingSystem();
const confirmation = new ConfirmationSystem();

// Global utility functions
window.SihatApp = window.SihatApp || {};
window.SihatApp.utils = {
    showNotification: (message, type, options) => notifications.show(message, type, options),
    showLoading: (text) => loading.show(text),
    hideLoading: () => loading.hide(),
    confirm: (message, title, options) => confirmation.show(message, title, options),
    
    // Form utilities
    showLoading: (button) => {
        if (button) {
            button.disabled = true;
            const originalText = button.textContent;
            button.dataset.originalText = originalText;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
        }
    },
    
    hideLoading: (button) => {
        if (button && button.dataset.originalText) {
            button.disabled = false;
            button.textContent = button.dataset.originalText;
            delete button.dataset.originalText;
        }
    },
    
    // Number formatting
    formatNumber: (number) => {
        return new Intl.NumberFormat().format(number);
    },
    
    // Date formatting
    formatDate: (date, options = {}) => {
        const defaults = {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        };
        return new Intl.DateTimeFormat('en-US', { ...defaults, ...options }).format(new Date(date));
    }
};

// API utilities
window.SihatApp.api = {
    get: async (url) => {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        return response.json();
    },
    
    post: async (url, data) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        return response.json();
    },
    
    put: async (url, data) => {
        const response = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(data)
        });
        return response.json();
    },
    
    delete: async (url) => {
        const response = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        return response.json();
    }
};

// Location utilities
window.SihatApp.location = {
    getCurrentPosition: () => {
        return new Promise((resolve, reject) => {
            if (!navigator.geolocation) {
                reject(new Error('Geolocation is not supported'));
                return;
            }
            
            navigator.geolocation.getCurrentPosition(resolve, reject, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minutes
            });
        });
    },
    
    updateLocation: async () => {
        try {
            const position = await SihatApp.location.getCurrentPosition();
            const data = {
                latitude: position.coords.latitude,
                longitude: position.coords.longitude,
                accuracy: position.coords.accuracy
            };
            
            return await SihatApp.api.post('/api/location/update', data);
        } catch (error) {
            throw error;
        }
    }
};

