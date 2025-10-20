<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SystemContent;
use App\Models\Service;
use App\Models\ServiceProvider;
use App\Models\Country;
use App\Models\FAQ;

/**
 * Home Controller
 * 
 * Handles home page and general public pages
 */
class HomeController extends Controller
{
    /**
     * Display home page
     */
    public function index()
    {
        $systemContent = new SystemContent();
        
        $data = [
            'pageTitle' => 'صحة الحاج - الرئيسية',
            'hero' => $systemContent->getHeroContent(),
            'statistics' => $systemContent->getStatistics(),
            'benefits' => $systemContent->getBenefits(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/home', $data);
    }
    
    /**
     * Switch language
     */
    public function switchLanguage($lang)
    {
        if (in_array($lang, ['ar', 'en'])) {
            $_SESSION['language'] = $lang;
            $this->language->setLanguage($lang);
        }
        
        // Redirect back to previous page or home
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }
    
    /**
     * Display about page
     */
    public function about()
    {
        $systemContent = new SystemContent();
        
        $data = [
            'pageTitle' => 'عن صحة الحاج',
            'layout' => 'app',
            'vision_values' => $systemContent->getVisionValues(),
            'statistics' => $systemContent->getStatistics(),
            'benefits' => $systemContent->getBenefits()
        ];
        
        return $this->render('pages/public/about', $data);
    }
    
    /**
     * Display services page
     */
    public function services()
    {
        $service = new Service();
        $serviceProvider = new ServiceProvider();
        
        $data = [
            'pageTitle' => 'الخدمات - صحة الحاج',
            'layout' => 'app',
            'services' => $service->getAllActive(),
            'service_categories' => $service->getServiceCategories(),
            'providers' => $serviceProvider->getFeatured(6)
        ];
        
        return $this->render('pages/services', $data);
    }
    
    /**
     * Display guidelines page
     */
    public function guidelines()
    {
        $data = [
            'pageTitle' => 'إرشادات الحج - صحة الحاج',
            'layout' => 'app'
        ];
        
        return $this->render('pages/guidelines', $data);
    }
    
    /**
     * Display contact page
     */
    public function contact()
    {
        $systemContent = new SystemContent();
        $faq = new FAQ();
        
        $data = [
            'pageTitle' => 'تواصل معنا - صحة الحاج',
            'layout' => 'app',
            'contact_info' => $systemContent->getContactInfo(),
            'faqs' => $faq->getAllActive()
        ];
        
        return $this->render('pages/contact', $data);
    }
    
    /**
     * Display countries page
     */
    public function countries()
    {
        $country = new Country();
        
        $data = [
            'pageTitle' => 'الدول - صحة الحاج',
            'layout' => 'app',
            'countries' => $country->getAll(),
            'popular_countries' => $country->getPopular(10)
        ];
        
        return $this->render('pages/countries', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function submitContact()
    {
        // Handle contact form submission
        $this->redirect('/contact');
    }
}

