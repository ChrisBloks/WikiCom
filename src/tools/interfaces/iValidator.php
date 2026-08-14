<?php

interface iValidator{
    public function validate(array $field_info): bool;
}