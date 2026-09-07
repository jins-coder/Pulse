<?php

declare(strict_types=1);

namespace Pulse\Validation;

class Validator
{
    protected array $errors = [];

    public function __construct(
        protected array $data,
        protected array $rules
    ) {}

    public static function make(array $data, array $rules): self
    {
        return new self($data, $rules);
    }

    public function validate(): array
    {
        $this->errors = [];

        foreach ($this->rules as $field => $ruleString) {
            $rulesList = is_array($ruleString) ? $ruleString : explode('|', $ruleString);
            $value = $this->data[$field] ?? null;

            foreach ($rulesList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$rule, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                }

                $this->applyRule($field, $value, $rule, $params);
            }
        }

        if (!empty($this->errors)) {
            throw new ValidationException($this->errors);
        }

        return $this->data;
    }

    public function passes(): bool
    {
        try {
            $this->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    protected function applyRule(string $field, mixed $value, string $rule, array $params): void
    {
        switch ($rule) {
            case 'required':
                if ($value === null || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "The {$field} field is required.");
                }
                break;
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$field} must be a valid email.");
                }
                break;
            case 'min':
                $min = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) < $min) {
                    $this->addError($field, "The {$field} must be at least {$min} characters.");
                } elseif (is_numeric($value) && (float)$value < $min) {
                    $this->addError($field, "The {$field} must be at least {$min}.");
                }
                break;
            case 'max':
                $max = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) > $max) {
                    $this->addError($field, "The {$field} may not exceed {$max} characters.");
                }
                break;
        }
    }

    protected function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }
}

class ValidationException extends \Exception
{
    public function __construct(public readonly array $errors)
    {
        parent::__construct("The given data failed validation.");
    }
}
