<?php

namespace App\Core;

/**
 * Router Class
 * 
 * Handles URL routing and dispatches requests to appropriate controllers
 */
class Router
{
    private $routes = [];
    private $currentRoute = null;
    
    /**
     * Add a GET route
     */
    public function get($pattern, $callback)
    {
        $this->addRoute('GET', $pattern, $callback);
    }
    
    /**
     * Add a POST route
     */
    public function post($pattern, $callback)
    {
        $this->addRoute('POST', $pattern, $callback);
    }
    
    /**
     * Add a PUT route
     */
    public function put($pattern, $callback)
    {
        $this->addRoute('PUT', $pattern, $callback);
    }
    
    /**
     * Add a DELETE route
     */
    public function delete($pattern, $callback)
    {
        $this->addRoute('DELETE', $pattern, $callback);
    }
    
    /**
     * Add a route with any HTTP method
     */
    public function any($pattern, $callback)
    {
        $methods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        foreach ($methods as $method) {
            $this->addRoute($method, $pattern, $callback);
        }
    }
    
    /**
     * Add a route to the routes array
     */
    private function addRoute($method, $pattern, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'callback' => $callback
        ];
    }
    
    /**
     * Handle the current request
     */
    public function handleRequest()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->getRequestUri();
        
        // Debug output
        if (APP_DEBUG) {
            error_log("Router Debug: Method={$requestMethod}, URI={$requestUri}");
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchRoute($route['pattern'], $requestUri)) {
                $this->currentRoute = $route;
                if (APP_DEBUG) {
                    error_log("Router Debug: Matched route {$route['pattern']} -> {$route['callback']}");
                }
                return $this->executeRoute($route['callback'], $this->getRouteParams($route['pattern'], $requestUri));
            }
        }
        
        // No route found, show 404
        if (APP_DEBUG) {
            error_log("Router Debug: No route found for {$requestMethod} {$requestUri}");
        }
        $this->show404();
    }
    
    /**
     * Get clean request URI
     */
    private function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove base path
        $basePath = '/sihat-al-haj';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }
        
        // Ensure URI starts with /
        if (empty($uri) || $uri[0] !== '/') {
            $uri = '/' . $uri;
        }
        
        // Remove trailing slash except for root
        if ($uri !== '/' && substr($uri, -1) === '/') {
            $uri = substr($uri, 0, -1);
        }
        
        return $uri;
    }
    
    /**
     * Check if route pattern matches the request URI
     */
    private function matchRoute($pattern, $uri)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        return preg_match($pattern, $uri);
    }
    
    /**
     * Get route parameters from URI
     */
    private function getRouteParams($pattern, $uri)
    {
        $params = [];
        
        // Extract parameter names from pattern
        preg_match_all('/\{([^}]+)\}/', $pattern, $paramNames);
        
        // Convert pattern to regex and extract values
        $regexPattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $pattern);
        $regexPattern = '#^' . $regexPattern . '$#';
        
        if (preg_match($regexPattern, $uri, $matches)) {
            array_shift($matches); // Remove full match
            
            for ($i = 0; $i < count($paramNames[1]); $i++) {
                if (isset($matches[$i])) {
                    $params[$paramNames[1][$i]] = $matches[$i];
                }
            }
        }
        
        return $params;
    }
    
    /**
     * Execute the route callback
     */
    private function executeRoute($callback, $params = [])
    {
        if (is_string($callback)) {
            // Controller@method format
            if (strpos($callback, '@') !== false) {
                list($controllerName, $methodName) = explode('@', $callback);
                
                $controllerClass = "\\App\\Controllers\\{$controllerName}";
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    
                    if (method_exists($controller, $methodName)) {
                        return call_user_func_array([$controller, $methodName], $params);
                    } else {
                        throw new \Exception("Method {$methodName} not found in {$controllerClass}");
                    }
                } else {
                    throw new \Exception("Controller {$controllerClass} not found");
                }
            }
        } elseif (is_callable($callback)) {
            // Anonymous function
            return call_user_func_array($callback, $params);
        }
        
        throw new \Exception("Invalid route callback");
    }
    
    /**
     * Show 404 error page
     */
    private function show404()
    {
        http_response_code(404);
        
        // Try to load 404 view
        $view = new View();
        if (file_exists(VIEWS_PATH . '/errors/404.php')) {
            $view->render('errors/404');
        } else {
            echo '<h1>404 - Page Not Found</h1>';
            echo '<p>The requested page could not be found.</p>';
        }
    }
    
    /**
     * Redirect to a URL
     */
    public static function redirect($url, $statusCode = 302)
    {
        error_log("Router::redirect called");
        error_log("Redirect URL: " . $url);
        error_log("Status Code: " . $statusCode);
        error_log("Headers sent: " . (headers_sent() ? 'YES' : 'NO'));
        
        // Clean all output buffers before redirect
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        if (headers_sent($file, $line)) {
            error_log("ERROR: Headers already sent in {$file} on line {$line}");
            echo "<script>window.location.href = '{$url}';</script>";
            echo "<noscript><meta http-equiv='refresh' content='0;url={$url}'></noscript>";
            exit;
        }
        
        header("Location: {$url}", true, $statusCode);
        error_log("Header sent successfully");
        exit;
    }
    
    /**
     * Generate URL for a named route
     */
    public function url($name, $params = [])
    {
        // This would be implemented if we had named routes
        // For now, just return the URL as-is
        return $name;
    }
}

