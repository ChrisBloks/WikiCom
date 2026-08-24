<?php

interface iValidator{
    public function validate(string $page_name): bool;
    public function validate_fields(array $field_inputs): bool;
}