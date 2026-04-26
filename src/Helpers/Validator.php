<?php
declare(strict_types=1);
namespace App\Helpers;

class Validator
{
    private array $errors = [];
    private array $data   = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): static
    {
        if (empty($this->data[$field]) && $this->data[$field] !== '0') {
            $this->errors[$field] = ($label ?: $field) . ' is required.';
        }
        return $this;
    }

    public function min(string $field, int $min, string $label = ''): static
    {
        if (isset($this->data[$field]) && strlen((string)$this->data[$field]) < $min) {
            $this->errors[$field] = ($label ?: $field) . " must be at least {$min} characters.";
        }
        return $this;
    }

    public function max(string $field, int $max, string $label = ''): static
    {
        if (isset($this->data[$field]) && strlen((string)$this->data[$field]) > $max) {
            $this->errors[$field] = ($label ?: $field) . " must not exceed {$max} characters.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): static
    {
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = ($label ?: $field) . ' must be a valid email address.';
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): static
    {
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = ($label ?: $field) . ' must be a number.';
        }
        return $this;
    }

    public function inList(string $field, array $list, string $label = ''): static
    {
        if (isset($this->data[$field]) && !in_array($this->data[$field], $list, true)) {
            $this->errors[$field] = ($label ?: $field) . ' has an invalid value.';
        }
        return $this;
    }

    public function passes(): bool { return empty($this->errors); }
    public function fails(): bool  { return !$this->passes(); }
    public function errors(): array { return $this->errors; }
    public function firstError(): string { return reset($this->errors) ?: ''; }
}
