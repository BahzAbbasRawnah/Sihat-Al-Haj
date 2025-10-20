<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\HajjTimeline;
use App\Models\MedicalCenter;
use App\Models\MedicalTeam;
use App\Models\Service;
use App\Models\HealthGuideline;
use App\Models\FAQ;

/**
 * Public Controller
 * 
 * Handles public-facing pages with full multilingual support
 */
class PublicController extends Controller
{
    /**
     * About page
     */
    public function about()
    {
        $data = [
            'title' => __('about_us'),
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/about', $data);
    }
    
    /**
     * Contact page
     */
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->submitContact();
        }
        
        $data = [
            'title' => __('contact_us'),
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/contact', $data);
    }
    
    /**
     * Submit contact form
     */
    public function submitContact()
    {
        // Handle contact form submission
        $data = [
            'success' => __('message_sent_successfully'),
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/contact', $data);
    }
    
    /**
     * Health guidelines page
     */
    public function guidelines()
    {
        $guidelineModel = new HealthGuideline();
        $guidelines = $guidelineModel->all();
        
        $timelineModel = new HajjTimeline();
        $timeline = $timelineModel->getAll();
        
        $data = [
            'title' => __('health_guidelines'),
            'guidelines' => $guidelines,
            'timeline' => $timeline,
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/guidelines', $data);
    }
    
    /**
     * Services page
     */
    public function services()
    {
        $serviceModel = new Service();
        $services = $serviceModel->getAllActive();
        
        $data = [
            'title' => __('services'),
            'services' => $services,
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/services', $data);
    }
    
    /**
     * Medical centers page
     */
    public function medicalCenters()
    {
        $centerModel = new MedicalCenter();
        $centers = $centerModel->getActive();
        
        $data = [
            'title' => __('medical_centers'),
            'centers' => $centers,
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/medical-centers', $data);
    }
    
    /**
     * Medical teams page
     */
    public function medicalTeams()
    {
        $teamModel = new MedicalTeam();
        $teams = $teamModel->getAll();
        
        $data = [
            'title' => __('medical_teams'),
            'teams' => $teams,
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/medical-teams', $data);
    }
    
    /**
     * FAQs page
     */
    public function faqs()
    {
        $faqModel = new FAQ();
        $faqs = $faqModel->getAllActive();
        $categories = $faqModel->getCategories();
        
        $data = [
            'title' => __('faqs'),
            'faqs' => $faqs,
            'categories' => $categories,
            'currentLang' => getCurrentLanguage(),
            'layout' => 'app'
        ];
        
        return $this->render('pages/public/faqs', $data);
    }
}