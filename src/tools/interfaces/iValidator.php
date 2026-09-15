<?php

namespace Wiki\tools\interfaces;

interface iValidator
{
    public function validate(string $name, bool $optional, ?string $error_disp_name): bool;
    public function getFieldInputs(): array;
    public function validateFields(array $field_inputs, string $error_disp_name): bool;
}
