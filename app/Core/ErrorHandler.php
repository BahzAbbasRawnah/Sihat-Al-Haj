<?php

namespace App\Core;

/**
 * Error Handler
 * 
 * Handles application errors and exceptions
 */
class ErrorHandler
{
    private $language;
    private $logFile;
    
    public function __construct()
    {
        $this->language = new Language();
        $this->logFile = __DIR__ . '/../../storage/logs/error.log';
        
        // Ensure log directory exists
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Set error handlers
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
    
    /**
     * Handle PHP errors
     */
    public function handleError($severity, $message, $file, $line)
    {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $error = [
            'type' => 'PHP Error',
            'severity' => $this->getSeverityName($severity),
            'message' => $message,
            'file' => $file,
            'line' => $line,
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];
        
        $this->logError($error);
        
        // Show error page for fatal errors
        if ($severity === E_ERROR || $severity === E_CORE_ERROR || $severity === E_COMPILE_ERROR) {
            $this->showErrorPage(500, $this->language->get('messages.server_error'));
        }
        
        return true;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public function handleException($exception)
    {
        $error = [
            'type' => 'Exception',
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'timestamp' => date('Y-m-d H:i:s'),
            'url' => $_SERVER['REQUEST_URI'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
        ];
        
        $this->logError($error);
        
        // Determine HTTP status code
        $statusCode = 500;
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        }
        
        $this->showErrorPage($statusCode, $this->language->get('messages.server_error'));
    }
    
    /**
     * Handle fatal errors on shutdown
     */
    public function handleShutdown()
    {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $errorData = [
                'type' => 'Fatal Error',
                'severity' => $this->getSeverityName($error['type']),
                'message' => $error['message'],
                'file' => $error['file'],
                'line' => $error['line'],
                'timestamp' => date('Y-m-d H:i:s'),
                'url' => $_SERVER['REQUEST_URI'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ip' => $_SERVER['REMOTE_ADDR'] ?? ''
            ];
            
            $this->logError($errorData);
            $this->showErrorPage(500, $this->language->get('messages.server_error'));
        }
    }
    
    /**
     * Log error to file
     */
    private function logError($error)
    {
        $logEntry = sprintf(
            "[%s] %s: %s in %s:%d\n",
            $error['timestamp'],
            $error['type'],
            $error['message'],
            $error['file'],
            $error['line']
        );
        
        if (isset($error['trace'])) {
            $logEntry .= "Stack trace:\n" . $error['trace'] . "\n";
        }
        
        $logEntry .= "URL: " . $error['url'] . "\n";
        $logEntry .= "IP: " . $error['ip'] . "\n";
        $logEntry .= "User Agent: " . $error['user_agent'] . "\n";
        $logEntry .= str_repeat('-', 80) . "\n";
        
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Show error page
     */
    private function showErrorPage($statusCode, $message)
    {
        if (headers_sent()) {
            return;
        }
        
        http_response_code($statusCode);
        
        // Check if it's an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => $message,
                'error_code' => $statusCode
            ]);
            exit;
        }
        
        // Show HTML error page
        $this->renderErrorPage($statusCode, $message);
        exit;
    }
    
    /**
     * Render HTML error page
     */
    private function renderErrorPage($statusCode, $message)
    {
        $title = $this->getErrorTitle($statusCode);
        $isRTL = $this->language->getCurrentLanguage() === 'ar';
        
        ?>
        <!DOCTYPE html>
        <html lang="<?= $this->language->getCurrentLanguage() ?>" dir="<?= $isRTL ? 'rtl' : 'ltr' ?>">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= htmlspecialchars($title) ?> - <?= $this->language->get('platform.name') ?></title>
            <link href="/assets/css/app.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
        </head>
        <body class="bg-gray-50 dark:bg-gray-900">
            <div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8">
                <div class="max-w-md w-full text-center">
                    <div class="mb-8">
                        <div class="w-24 h-24 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-3xl"></i>
                        </div>
                        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">
                            <?= $statusCode ?>
                        </h1>
                        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-4">
                            <?= htmlspecialchars($title) ?>
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-8">
                            <?= htmlspecialchars($message) ?>
                        </p>
                    </div>
                    
                    <div class="space-y-4">
                        <a href="/" class="btn btn-primary w-full">
                            <i class="fas fa-home <?= $isRTL ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= $this->language->get('Go to Homepage') ?>
                        </a>
                        <button onclick="history.back()" class="btn btn-secondary w-full">
                            <i class="fas fa-arrow-<?= $isRTL ? 'right' : 'left' ?> <?= $isRTL ? 'ml-2' : 'mr-2' ?>"></i>
                            <?= $this->language->get('Go Back') ?>
                        </button>
                    </div>
                    
                    <?php if ($statusCode >= 500): ?>
                        <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <?= $this->language->get('If this problem persists, please contact support.') ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
    
    /**
     * Get error title by status code
     */
    private function getErrorTitle($statusCode)
    {
        $titles = [
            400 => $this->language->get('Bad Request'),
            401 => $this->language->get('Unauthorized'),
            403 => $this->language->get('Forbidden'),
            404 => $this->language->get('Page Not Found'),
            405 => $this->language->get('Method Not Allowed'),
            429 => $this->language->get('Too Many Requests'),
            500 => $this->language->get('Internal Server Error'),
            502 => $this->language->get('Bad Gateway'),
            503 => $this->language->get('Service Unavailable'),
            504 => $this->language->get('Gateway Timeout')
        ];
        
        return $titles[$statusCode] ?? $this->language->get('Error');
    }
    
    /**
     * Get severity name
     */
    private function getSeverityName($severity)
    {
        $severities = [
            E_ERROR => 'Fatal Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict Standards',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated'
        ];
        
        return $severities[$severity] ?? 'Unknown Error';
    }
    
    /**
     * Create custom exception
     */
    public static function createException($message, $statusCode = 500)
    {
        return new class($message, $statusCode) extends \Exception {
            private $statusCode;
            
            public function __construct($message, $statusCode = 500)
            {
                parent::__construct($message);
                $this->statusCode = $statusCode;
            }
            
            public function getStatusCode()
            {
                return $this->statusCode;
            }
        };
    }
}

