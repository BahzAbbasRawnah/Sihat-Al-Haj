<?php
/**
 * Multilingual Helper Functions
 * 
 * Helper functions to support multilingual content from database
 */

if (!function_exists('getLocalizedField')) {
    /**
     * Get localized field value from database record
     * 
     * @param array $record Database record
     * @param string $fieldName Base field name (without language suffix)
     * @param string|null $language Language code (ar or en), defaults to current language
     * @return string Localized field value
     */
    function getLocalizedField($record, $fieldName, $language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        $localizedFieldName = $fieldName . '_' . $language;
        
        // Return localized field if exists
        if (isset($record[$localizedFieldName]) && !empty($record[$localizedFieldName])) {
            return $record[$localizedFieldName];
        }
        
        // Fallback to opposite language
        $fallbackLang = $language === 'ar' ? 'en' : 'ar';
        $fallbackFieldName = $fieldName . '_' . $fallbackLang;
        
        if (isset($record[$fallbackFieldName]) && !empty($record[$fallbackFieldName])) {
            return $record[$fallbackFieldName];
        }
        
        // Return empty string if no translation found
        return '';
    }
}

if (!function_exists('getCurrentLanguage')) {
    /**
     * Get current language from session or default
     * 
     * @return string Current language code (ar or en)
     */
    function getCurrentLanguage()
    {
        return $_SESSION['language'] ?? 'ar';
    }
}

if (!function_exists('getOppositeLanguage')) {
    /**
     * Get opposite language
     * 
     * @param string|null $language Current language, defaults to session language
     * @return string Opposite language code
     */
    function getOppositeLanguage($language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        return $language === 'ar' ? 'en' : 'ar';
    }
}

if (!function_exists('localizeRecords')) {
    /**
     * Localize multiple database records
     * 
     * @param array $records Array of database records
     * @param array $fields Array of field names to localize
     * @param string|null $language Language code
     * @return array Localized records
     */
    function localizeRecords($records, $fields, $language = null)
    {
        if (empty($records)) {
            return [];
        }
        
        $localizedRecords = [];
        foreach ($records as $record) {
            $localizedRecords[] = localizeRecord($record, $fields, $language);
        }
        
        return $localizedRecords;
    }
}

if (!function_exists('localizeRecord')) {
    /**
     * Localize a single database record
     * 
     * @param array $record Database record
     * @param array $fields Array of field names to localize
     * @param string|null $language Language code
     * @return array Localized record with added localized fields
     */
    function localizeRecord($record, $fields, $language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        $localizedRecord = $record;
        
        foreach ($fields as $field) {
            $localizedRecord[$field] = getLocalizedField($record, $field, $language);
        }
        
        return $localizedRecord;
    }
}

if (!function_exists('getDirectionClass')) {
    /**
     * Get text direction class based on language
     * 
     * @param string|null $language Language code
     * @return string Direction class (rtl or ltr)
     */
    function getDirectionClass($language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        return $language === 'ar' ? 'rtl' : 'ltr';
    }
}

if (!function_exists('getTextAlignClass')) {
    /**
     * Get text alignment class based on language
     * 
     * @param string|null $language Language code
     * @return string Text alignment class
     */
    function getTextAlignClass($language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        return $language === 'ar' ? 'text-right' : 'text-left';
    }
}

if (!function_exists('formatLocalizedDate')) {
    /**
     * Format date according to language
     * 
     * @param string $date Date string
     * @param string|null $language Language code
     * @return string Formatted date
     */
    function formatLocalizedDate($date, $language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        $timestamp = strtotime($date);
        
        if ($language === 'ar') {
            // Arabic date format
            return date('Y/m/d', $timestamp);
        } else {
            // English date format
            return date('M d, Y', $timestamp);
        }
    }
}

if (!function_exists('formatLocalizedDateTime')) {
    /**
     * Format datetime according to language
     * 
     * @param string $datetime Datetime string
     * @param string|null $language Language code
     * @return string Formatted datetime
     */
    function formatLocalizedDateTime($datetime, $language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        $timestamp = strtotime($datetime);
        
        if ($language === 'ar') {
            // Arabic datetime format
            return date('Y/m/d H:i', $timestamp);
        } else {
            // English datetime format
            return date('M d, Y h:i A', $timestamp);
        }
    }
}

if (!function_exists('getLocalizedNumber')) {
    /**
     * Format number according to language
     * 
     * @param float|int $number Number to format
     * @param int $decimals Number of decimal places
     * @param string|null $language Language code
     * @return string Formatted number
     */
    function getLocalizedNumber($number, $decimals = 0, $language = null)
    {
        if ($language === null) {
            $language = getCurrentLanguage();
        }
        
        if ($language === 'ar') {
            // Arabic number format
            return number_format($number, $decimals, '.', '،');
        } else {
            // English number format
            return number_format($number, $decimals, '.', ',');
        }
    }
}

if (!function_exists('__')) {
    /**
     * Translation helper function (shorthand)
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in translation
     * @return string Translated string
     */
    function __($key, $params = [])
    {
        global $language;
        
        if ($language && method_exists($language, 'get')) {
            return $language->get($key, $params);
        }
        
        return $key;
    }
}
