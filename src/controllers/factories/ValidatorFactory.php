<?php
namespace Wiki\controllers\factories;

use Wiki\tools\interfaces\iValidator,
    Wiki\controllers\validators\TextValidator,
    Wiki\controllers\validators\NewPasswordValidator,
    Wiki\controllers\validators\EmailValidator,
    Wiki\controllers\validators\ImgValidator,
    Wiki\controllers\validators\CheckBoxValidator,
    Wiki\controllers\validators\SortValidator;
    

enum ValidatorFactory: string
{
    case VTEXT = 'text';
    case VEMAIL = 'email';
    case VPASSWORD = 'password';
    case VNEWPASS = 'new_password';
    case VTEXTAREA = 'textarea';
    case VFILE = 'file';
    case VSORT = 'select';
    case VCHECKBOX = 'checkboxgroup';

    public function createValidator(): iValidator
    {
        return match ($this) {
            self::VTEXT => new TextValidator(),
            self::VEMAIL => new EmailValidator(),
            self::VPASSWORD => new TextValidator(),
            self::VTEXTAREA => new TextValidator(),
            self::VNEWPASS => new NewPasswordValidator(),
            self::VFILE => new ImgValidator(),
            self::VSORT => new SortValidator(),
            self::VCHECKBOX => new CheckBoxValidator()
        };
    }
}