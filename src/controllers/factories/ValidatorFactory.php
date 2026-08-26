<?php
namespace Wiki\controllers\factories;

use Wiki\tools\interfaces\iValidator,
    Wiki\controllers\validators\BaseValidator,
    Wiki\controllers\validators\NewPasswordValidator,
    Wiki\controllers\validators\EmailValidator,
    Wiki\controllers\validators\ImgValidator;
    

enum ValidatorFactory: string
{
    case VTEXT = 'text';
    case VEMAIL = 'email';
    case VPASSWORD = 'password';
    case VNEWPASS = 'new_password';
    case VTEXTAREA = 'textarea';
    case VFILE = 'file';

    public function createValidator(): iValidator
    {
        return match ($this) {
            self::VTEXT => new BaseValidator(),
            self::VEMAIL => new EmailValidator(),
            self::VPASSWORD => new BaseValidator(),
            self::VTEXTAREA => new BaseValidator(),
            self::VNEWPASS => new NewPasswordValidator(),
            self::VFILE => new ImgValidator()
        };
    }
}