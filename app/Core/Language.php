<?php

namespace App\Core;

/**
 * Language Class
 * 
 * Handles multilingual support for Arabic and English
 */
class Language
{
    private $currentLanguage = 'ar';
    private $translations = [];
    private $fallbackLanguage = 'ar';
    
    public function __construct()
    {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Set language from session if available
        if (isset($_SESSION['language'])) {
            $this->currentLanguage = $_SESSION['language'];
        }
        
        $this->loadTranslations();
    }
    
    /**
     * Set current language
     */
    public function setLanguage($language)
    {
        if (in_array($language, ['ar', 'en'])) {
            $this->currentLanguage = $language;
            $_SESSION['language'] = $language;
        }
    }
    
    /**
     * Get current language
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }
    
    /**
     * Get translation for a key
     */
    public function get($key, $params = [])
    {
        $translation = $this->getTranslation($key);
        
        // Replace parameters in translation
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(":{$param}", $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Get translation with fallback
     */
    private function getTranslation($key)
    {
        // For simple keys (no dots), check directly in translations array
        if (strpos($key, '.') === false) {
            // Check current language
            if (isset($this->translations[$this->currentLanguage][$key])) {
                return $this->translations[$this->currentLanguage][$key];
            }
            
            // Check fallback language
            if (isset($this->translations[$this->fallbackLanguage][$key])) {
                return $this->translations[$this->fallbackLanguage][$key];
            }
            
            return $key; // Return key if no translation found
        }
        
        // For nested keys (with dots), use the original logic
        $keys = explode('.', $key);
        $translation = $this->translations[$this->currentLanguage] ?? [];
        
        foreach ($keys as $k) {
            if (isset($translation[$k])) {
                $translation = $translation[$k];
            } else {
                // Try fallback language
                $fallbackTranslation = $this->translations[$this->fallbackLanguage] ?? [];
                foreach ($keys as $fk) {
                    if (isset($fallbackTranslation[$fk])) {
                        $fallbackTranslation = $fallbackTranslation[$fk];
                    } else {
                        return $key; // Return key if no translation found
                    }
                }
                return $fallbackTranslation;
            }
        }
        
        return is_string($translation) ? $translation : $key;
    }
    
    /**
     * Load translations from files
     */
    private function loadTranslations()
    {
        // Use defined constant or calculate path
        $langPath = defined('APP_ROOT') ? APP_ROOT . '/resources/lang' : __DIR__ . '/../../resources/lang';
        
        // Load Arabic translations
        $arFile = $langPath . '/ar.php';
        if (file_exists($arFile)) {
            $this->translations['ar'] = include $arFile;
        }
        
        // Load English translations
        $enFile = $langPath . '/en.php';
        if (file_exists($enFile)) {
            $this->translations['en'] = include $enFile;
        }
    }
    
    /**
     * Check if current language is RTL
     */
    public function isRTL()
    {
        return $this->currentLanguage === 'ar';
    }
    
    /**
     * Get opposite language
     */
    public function getOppositeLanguage()
    {
        return $this->currentLanguage === 'ar' ? 'en' : 'ar';
    }
    
    /**
     * Get language direction
     */
    public function getDirection()
    {
        return $this->isRTL() ? 'rtl' : 'ltr';
    }
    
    /**
     * Get language name
     */
    public function getLanguageName($lang = null)
    {
        $lang = $lang ?? $this->currentLanguage;
        
        $names = [
            'ar' => 'العربية',
            'en' => 'English'
        ];
        
        return $names[$lang] ?? $lang;
    }
    
    /**
     * Get all available languages
     */
    public function getAvailableLanguages()
    {
        return [
            'ar' => 'العربية',
            'en' => 'English'
        ];
    }
    
    /**
     * Format number based on language
     */
    public function formatNumber($number)
    {
        if ($this->currentLanguage === 'ar') {
            // Convert to Arabic-Indic numerals
            $arabicNumerals = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            $englishNumerals = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            
            return str_replace($englishNumerals, $arabicNumerals, $number);
        }
        
        return $number;
    }
    
    /**
     * Format date based on language
     */
    public function formatDate($date, $format = null)
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }
        
        if ($this->currentLanguage === 'ar') {
            $format = $format ?? 'Y/m/d H:i';
            $formatted = $date->format($format);
            
            // Convert to Arabic numerals
            return $this->formatNumber($formatted);
        } else {
            $format = $format ?? 'Y-m-d H:i';
            return $date->format($format);
        }
    }
    
    /**
     * Get localized field value
     */
    public function getLocalizedField($data, $fieldName)
    {
        $localizedField = $fieldName . '_' . $this->currentLanguage;
        
        if (is_array($data) && isset($data[$localizedField])) {
            return $data[$localizedField];
        } elseif (is_object($data) && property_exists($data, $localizedField)) {
            return $data->$localizedField;
        }
        
        // Fallback to default language
        $fallbackField = $fieldName . '_' . $this->fallbackLanguage;
        
        if (is_array($data) && isset($data[$fallbackField])) {
            return $data[$fallbackField];
        } elseif (is_object($data) && property_exists($data, $fallbackField)) {
            return $data->$fallbackField;
        }
        
        return '';
    }
    
    /**
     * Get language switch URL
     */
    public function getSwitchUrl($targetLang = null)
    {
        $targetLang = $targetLang ?? $this->getOppositeLanguage();
        $currentUrl = $_SERVER['REQUEST_URI'];
        
        // Remove existing lang parameter
        $currentUrl = preg_replace('/[?&]lang=[^&]*/', '', $currentUrl);
        
        // Add new lang parameter
        $separator = strpos($currentUrl, '?') !== false ? '&' : '?';
        
        return $currentUrl . $separator . 'lang=' . $targetLang;
    }
    
    /**
     * Magic method to allow direct translation calls
     */
    public function __call($method, $args)
    {
        if ($method === 't' || $method === 'trans') {
            return $this->get($args[0], $args[1] ?? []);
        }
        
        throw new \Exception("Method {$method} not found in Language class");
    }
}

