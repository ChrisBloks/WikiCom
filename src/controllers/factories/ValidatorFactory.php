<?php
namespace Wiki\controllers\factories;

use Wiki\tools\interfaces\iValidator,
Wiki\controllers\validators\BaseValidator;

enum ValidatorFactory: string
{
    case VTEXT = 'text';
    case VEMAIL = 'email';
    case VPASSWORD = 'password';
    case VNEWPASS = 'new_password';

    public function createValidator(): iValidator
    {
        return match ($this) {
            self::VTEXT => new BaseValidator(),
            self::VEMAIL => new BaseValidator(),
            self::VPASSWORD => new BaseValidator(),
            self::VNEWPASSWORD => new NewPasswordValidator()
        };
    }
}
