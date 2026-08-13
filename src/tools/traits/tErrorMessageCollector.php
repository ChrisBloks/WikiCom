<?php
//require_once "/tools/traits/tErrorMessageCollector.php";

trait tErrorMessageCollector
{
    private array $errors = [];

    protected function logError(string $message): void
    {
        $this->errors[] = $message;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
    public function emptyErrors(): void
    {
        unset($this->errors);
    }
}