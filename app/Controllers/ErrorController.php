<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Error Controller
 * 
 * Handles error pages and error responses
 */
class ErrorController extends Controller
{
    /**
     * 404 Not Found page
     */
    public function notFoundPage()
    {
        http_response_code(404);
        return $this->render('errors/404', [
            'title' => 'الصفحة غير موجودة',
            'message' => 'عذراً، الصفحة التي تبحث عنها غير موجودة'
        ]);
    }
    
    /**
     * 500 Server Error page
     */
    public function serverError()
    {
        http_response_code(500);
        return $this->render('errors/500', [
            'title' => 'خطأ في الخادم',
            'message' => 'عذراً، حدث خطأ في الخادم. يرجى المحاولة لاحقاً'
        ]);
    }
    
    /**
     * 403 Unauthorized page
     */
    public function unauthorized()
    {
        http_response_code(403);
        return $this->render('errors/403', [
            'title' => 'غير مصرح',
            'message' => 'عذراً، ليس لديك صلاحية للوصول إلى هذه الصفحة'
        ]);
    }
}
