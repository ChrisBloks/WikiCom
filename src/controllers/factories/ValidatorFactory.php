<?php
namespace Wiki\controllers\factories;

use Wiki\tools\interfaces\iValidator,
    Wiki\controllers\validators\BaseValidator;

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
                    $this-> validatorlist[$field['type']] = ValidatorFactory::from($field['type'])->createValidator();
                }
        }
    }

}

enum ValidatorFactory : string
{
    case BASE_ = 'text';

    public function createValidator() : iValidator
    {
        return match($this)
        {
        self::BASE_ => new BaseValidator()
        };
    }
}