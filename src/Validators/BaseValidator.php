<?php

namespace App\Validators;

class BaseValidator
{
    protected array $errors = [];
    protected array $data = [];

    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];
        $this->data = $data;

        foreach ($rules as $field => $ruleSet) {
            $this->validateField($field, $ruleSet);
        }

        return empty($this->errors);
    }

    protected function validateField(string $field, string $ruleSet): void
    {
        $rules = explode('|', $ruleSet);

        foreach ($rules as $rule) {
            if (strpos($rule, ':') !== false) {
                [$ruleName, $parameter] = explode(':', $rule, 2);
                $this->applyRule($field, $ruleName, $parameter);
            } else {
                $this->applyRule($field, $rule);
            }

            // Stop validation on first error for this field
            if (isset($this->errors[$field])) {
                break;
            }
        }
    }

    protected function applyRule(string $field, string $rule, ?string $parameter = null): void
    {
        $value = $this->data[$field] ?? null;

        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                }
                break;

            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = 'Invalid email format';
                }
                break;

            case 'min':
                if ($value && strlen($value) < (int)$parameter) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$parameter} characters";
                }
                break;

            case 'max':
                if ($value && strlen($value) > (int)$parameter) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must not exceed {$parameter} characters";
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($this->data[$confirmField] ?? null)) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . 's do not match';
                }
                break;

            case 'regex':
                if ($value && !preg_match($parameter, $value)) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' format is invalid';
                }
                break;

            case 'alpha':
                if ($value && !preg_match('/^[a-zA-Z\s]+$/', $value)) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must contain only letters';
                }
                break;

            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be a number';
                }
                break;

            case 'url':
                if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$field] = 'Invalid URL format';
                }
                break;

            case 'array':
                if ($value && !is_array($value)) {
                    $this->errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be an array';
                }
                break;

            case 'image':
                if ($value && isset($value['error']) && $value['error'] === UPLOAD_ERR_OK) {
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                    if (!in_array($value['type'], $allowedMimes)) {
                        $this->errors[$field] = 'File must be an image (jpg, png, gif, webp)';
                    }
                }
                break;

            case 'file_max':
                if ($value && isset($value['size'])) {
                    $maxSize = (int)$parameter * 1024 * 1024; // MB to bytes
                    if ($value['size'] > $maxSize) {
                        $this->errors[$field] = "File size must not exceed {$parameter}MB";
                    }
                }
                break;

            case 'strong_password':
                if ($value) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $this->errors[$field] = 'Password must contain at least one uppercase letter';
                    } elseif (!preg_match('/[a-z]/', $value)) {
                        $this->errors[$field] = 'Password must contain at least one lowercase letter';
                    } elseif (!preg_match('/[0-9]/', $value)) {
                        $this->errors[$field] = 'Password must contain at least one number';
                    }
                }
                break;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getError(string $field): ?string
    {
        return $this->errors[$field] ?? null;
    }
}