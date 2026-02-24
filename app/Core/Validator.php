<?php

namespace App\Core;

class Validator
{
    private array $errors = [];
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;

            foreach ($fieldRules as $rule) {
                if (is_string($rule)) {
                    $parts = explode(':', $rule, 2);
                    $ruleName = $parts[0];
                    $param = $parts[1] ?? null;
                } else {
                    continue;
                }

                $label = str_replace('_', ' ', $field);

                switch ($ruleName) {
                    case 'required':
                        if ($value === null || $value === '') {
                            $this->errors[$field][] = ucfirst($label) . " is required.";
                        }
                        break;

                    case 'email':
                        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $this->errors[$field][] = ucfirst($label) . " must be a valid email.";
                        }
                        break;

                    case 'url':
                        if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $this->errors[$field][] = ucfirst($label) . " must be a valid URL.";
                        }
                        break;

                    case 'min':
                        if ($value && mb_strlen((string) $value) < (int) $param) {
                            $this->errors[$field][] = ucfirst($label) . " must be at least $param characters.";
                        }
                        break;

                    case 'max':
                        if ($value && mb_strlen((string) $value) > (int) $param) {
                            $this->errors[$field][] = ucfirst($label) . " must not exceed $param characters.";
                        }
                        break;

                    case 'integer':
                        if ($value !== null && $value !== '' && !ctype_digit((string) $value)) {
                            $this->errors[$field][] = ucfirst($label) . " must be an integer.";
                        }
                        break;

                    case 'slug':
                        if ($value && !preg_match('/^[a-z0-9\-]+$/', $value)) {
                            $this->errors[$field][] = ucfirst($label) . " must only contain lowercase letters, numbers, and hyphens.";
                        }
                        break;

                    case 'in':
                        $allowed = explode(',', $param);
                        if ($value && !in_array($value, $allowed, true)) {
                            $this->errors[$field][] = ucfirst($label) . " must be one of: " . implode(', ', $allowed) . ".";
                        }
                        break;

                    case 'unique':
                        // param format: table,column or table,column,ignore_id
                        $parts2 = explode(',', $param);
                        $table = $parts2[0];
                        $column = $parts2[1] ?? $field;
                        $ignoreId = $parts2[2] ?? null;

                        if ($value) {
                            $sql = "SELECT id FROM $table WHERE $column = ?";
                            $params2 = [$value];
                            if ($ignoreId) {
                                $sql .= " AND id != ?";
                                $params2[] = $ignoreId;
                            }
                            $stmt = Database::query($sql, $params2);
                            if ($stmt->fetch()) {
                                $this->errors[$field][] = ucfirst($label) . " already exists.";
                            }
                        }
                        break;

                    case 'match':
                        $matchField = $param;
                        $matchValue = $this->data[$matchField] ?? null;
                        if ($value !== $matchValue) {
                            $this->errors[$field][] = ucfirst($label) . " does not match.";
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }
        return null;
    }
}
