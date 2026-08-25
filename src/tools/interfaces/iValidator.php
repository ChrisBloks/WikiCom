<?php

interface iValidator{
    public function validate(string $page_name): bool;
    public function validateFields(array $field_inputs): bool;
}