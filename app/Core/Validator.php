<?php

namespace App\Core;

/**
 * Validator
 * 
 * Handles form validation and data validation
 */
class Validator
{
    private $language;
    private $errors = [];
    private $data = [];
    
    public function __construct($data = [])
    {
        $this->language = new Language();
        $this->data = $data;
    }
    
    /**
     * Validate data against rules
     */
    public function validate($rules)
    {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $fieldRules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            
            foreach ($fieldRules as $rule) {
                $this->validateField($field, $value, $rule);
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Validate individual field
     */
    private function validateField($field, $value, $rule)
    {
        // Parse rule and parameters
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $parameters = isset($ruleParts[1]) ? explode(',', $ruleParts[1]) : [];
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, 'required');
                }
                break;
                
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'email');
                }
                break;
                
            case 'min':
                $min = (int)$parameters[0];
                if (!empty($value) && strlen($value) < $min) {
                    $this->addError($field, 'min', ['min' => $min]);
                }
                break;
                
            case 'max':
                $max = (int)$parameters[0];
                if (!empty($value) && strlen($value) > $max) {
                    $this->addError($field, 'max', ['max' => $max]);
                }
                break;
                
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'numeric');
                }
                break;
                
            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, 'integer');
                }
                break;
                
            case 'alpha':
                if (!empty($value) && !preg_match('/^[a-zA-Z\u0600-\u06FF\s]+$/u', $value)) {
                    $this->addError($field, 'alpha');
                }
                break;
                
            case 'alpha_num':
                if (!empty($value) && !preg_match('/^[a-zA-Z0-9\u0600-\u06FF\s]+$/u', $value)) {
                    $this->addError($field, 'alpha_num');
                }
                break;
                
            case 'phone':
                if (!empty($value) && !preg_match('/^[\+]?[0-9\s\-\(\)]+$/', $value)) {
                    $this->addError($field, 'phone');
                }
                break;
                
            case 'date':
                if (!empty($value) && !strtotime($value)) {
                    $this->addError($field, 'date');
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($this->data[$confirmField] ?? null)) {
                    $this->addError($field, 'confirmed');
                }
                break;
                
            case 'unique':
                if (!empty($value)) {
                    $table = $parameters[0];
                    $column = $parameters[1] ?? $field;
                    $except = $parameters[2] ?? null;
                    
                    if ($this->isUnique($table, $column, $value, $except)) {
                        $this->addError($field, 'unique');
                    }
                }
                break;
                
            case 'exists':
                if (!empty($value)) {
                    $table = $parameters[0];
                    $column = $parameters[1] ?? $field;
                    
                    if (!$this->exists($table, $column, $value)) {
                        $this->addError($field, 'exists');
                    }
                }
                break;
                
            case 'in':
                if (!empty($value) && !in_array($value, $parameters)) {
                    $this->addError($field, 'in', ['values' => implode(', ', $parameters)]);
                }
                break;
                
            case 'regex':
                $pattern = $parameters[0];
                if (!empty($value) && !preg_match($pattern, $value)) {
                    $this->addError($field, 'regex');
                }
                break;
                
            case 'file':
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                    $this->validateFile($field, $_FILES[$field]);
                }
                break;
                
            case 'image':
                if (isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE) {
                    $this->validateImage($field, $_FILES[$field]);
                }
                break;
        }
    }
    
    /**
     * Validate file upload
     */
    private function validateFile($field, $file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->addError($field, 'file_upload_error');
            return;
        }
        
        // Check file size (default 10MB)
        $maxSize = 10 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            $this->addError($field, 'file_too_large', ['max' => '10MB']);
        }
    }
    
    /**
     * Validate image upload
     */
    private function validateImage($field, $file)
    {
        $this->validateFile($field, $file);
        
        if (!empty($this->errors[$field])) {
            return;
        }
        
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            $this->addError($field, 'invalid_image_type');
        }
        
        // Verify it's actually an image
        $imageInfo = getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            $this->addError($field, 'invalid_image');
        }
    }
    
    /**
     * Check if value is unique in database
     */
    private function isUnique($table, $column, $value, $except = null)
    {
        $database = new Database();
        
        $sql = "SELECT COUNT(*) as count FROM `$table` WHERE `$column` = :value";
        $params = [':value' => $value];
        
        if ($except) {
            $sql .= " AND id != :except";
            $params[':except'] = $except;
        }
        
        $result = $database->query($sql, $params);
        return ($result[0]['count'] ?? 0) > 0;
    }
    
    /**
     * Check if value exists in database
     */
    private function exists($table, $column, $value)
    {
        $database = new Database();
        
        $sql = "SELECT COUNT(*) as count FROM `$table` WHERE `$column` = :value";
        $result = $database->query($sql, [':value' => $value]);
        
        return ($result[0]['count'] ?? 0) > 0;
    }
    
    /**
     * Add validation error
     */
    private function addError($field, $rule, $parameters = [])
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        
        $message = $this->getErrorMessage($field, $rule, $parameters);
        $this->errors[$field][] = $message;
    }
    
    /**
     * Get error message
     */
    private function getErrorMessage($field, $rule, $parameters = [])
    {
        $fieldName = $this->getFieldName($field);
        
        $messages = [
            'required' => $this->language->get('validation.required', ['field' => $fieldName]),
            'email' => $this->language->get('validation.email', ['field' => $fieldName]),
            'min' => $this->language->get('validation.min', array_merge(['field' => $fieldName], $parameters)),
            'max' => $this->language->get('validation.max', array_merge(['field' => $fieldName], $parameters)),
            'numeric' => $this->language->get('validation.numeric', ['field' => $fieldName]),
            'integer' => $this->language->get('validation.integer', ['field' => $fieldName]),
            'alpha' => $this->language->get('validation.alpha', ['field' => $fieldName]),
            'alpha_num' => $this->language->get('validation.alpha_num', ['field' => $fieldName]),
            'phone' => $this->language->get('validation.phone', ['field' => $fieldName]),
            'date' => $this->language->get('validation.date', ['field' => $fieldName]),
            'confirmed' => $this->language->get('validation.confirmed', ['field' => $fieldName]),
            'unique' => $this->language->get('validation.unique', ['field' => $fieldName]),
            'exists' => $this->language->get('validation.exists', ['field' => $fieldName]),
            'in' => $this->language->get('validation.in', array_merge(['field' => $fieldName], $parameters)),
            'regex' => $this->language->get('validation.regex', ['field' => $fieldName]),
            'file_upload_error' => $this->language->get('validation.file_upload_error', ['field' => $fieldName]),
            'file_too_large' => $this->language->get('validation.file_too_large', array_merge(['field' => $fieldName], $parameters)),
            'invalid_image_type' => $this->language->get('validation.invalid_image_type', ['field' => $fieldName]),
            'invalid_image' => $this->language->get('validation.invalid_image', ['field' => $fieldName])
        ];
        
        return $messages[$rule] ?? $this->language->get('validation.invalid', ['field' => $fieldName]);
    }
    
    /**
     * Get human-readable field name
     */
    private function getFieldName($field)
    {
        // Convert snake_case to readable format
        $name = str_replace('_', ' ', $field);
        $name = ucwords($name);
        
        // Try to get translated field name
        $translationKey = 'fields.' . $field;
        $translated = $this->language->get($translationKey);
        
        if ($translated !== $translationKey) {
            return $translated;
        }
        
        return $name;
    }
    
    /**
     * Get all errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * Get errors for specific field
     */
    public function getError($field)
    {
        return $this->errors[$field] ?? [];
    }
    
    /**
     * Get first error for field
     */
    public function getFirstError($field)
    {
        $errors = $this->getError($field);
        return $errors[0] ?? null;
    }
    
    /**
     * Check if field has errors
     */
    public function hasError($field)
    {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }
    
    /**
     * Check if validation passed
     */
    public function passes()
    {
        return empty($this->errors);
    }
    
    /**
     * Check if validation failed
     */
    public function fails()
    {
        return !$this->passes();
    }
    
    /**
     * Static validation method
     */
    public static function make($data, $rules)
    {
        $validator = new self($data);
        $validator->validate($rules);
        return $validator;
    }
}

