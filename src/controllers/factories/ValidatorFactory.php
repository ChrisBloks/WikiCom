<?php
namespace Wiki\controllers\factories;

class Validator
{
    protected array $validatorlist = [];

    public function useValidators(array $field_info)
    {
        $this-> getValidators($field_info);
    }

    protected function getValidators(array $field_info)
    {
        foreach ($field_info as $field){
            if (! array_key_exists($field['type'],$this->validatorlist))
                {

                }

        }
    }

}

enum ValidatorFactory
{
    case 
}